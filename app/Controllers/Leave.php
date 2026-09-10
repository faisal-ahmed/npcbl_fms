<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;
use JetBrains\PhpStorm\NoReturn;

class Leave extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): RedirectResponse
    {
        return redirect()->to(base_url('leave/leave-report'));
    }

    public function leaveReport(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = $this->getCommonFormData();
        $data['submenu'] = 'personalLeaveReport';
        $userId = $this->getUserId();

        // Handle Cancel Action
        if ($this->request->getPost('action') === 'cancel') {
            $cancelResult = $this->processCancel($this->request->getPost('request_id'));
            if ($cancelResult['status'] === 'success') {
                $data['success'] = $cancelResult['message'];
            } else {
                $data['error'] = $cancelResult['message'];
            }
        }

        $selectedYear = $this->request->getGet('year') ?? date('Y');
        $startOfYear = "$selectedYear-01-01";
        $endOfYear   = "$selectedYear-12-31";

        // Fetches all user requests for the year regardless of status
        $data['leaveHistory'] = $this->userModel->getLeaveRequestDetails(
            $userId, null, null, $startOfYear, $endOfYear
        );

        $data['selectedYear'] = $selectedYear;
        $data['years'] = range(date('Y'), date('Y')-2);

        return $this->viewLoad("leave/leaveReport", $data);
    }

    public function alternative(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = $this->getCommonFormData();
        $data['submenu'] = 'alternative';
        $userId = $this->getUserId();

        $selectedYear = $this->request->getGet('year') ?? date('Y');
        $startOfYear = "$selectedYear-01-01";
        $endOfYear   = "$selectedYear-12-31";

        // Pull leaves where the logged-in user is the alternative personnel
        $data['leaveHistory'] = $this->userModel->getAlternativeLeaveRequests(
            $userId, $startOfYear, $endOfYear
        );

        $data['selectedYear'] = $selectedYear;
        $data['years'] = range(date('Y'), date('Y')-2);

        return $this->viewLoad("leave/alternativeReport", $data);
    }

    public function statistics(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = $this->getCommonFormData();
        $data['submenu'] = 'statistics';
        $userId = $this->getUserId();

        $selectedYear = $this->request->getGet('year') ?? date('Y');
        $approvedLeaves = $this->userModel->getApprovedLeavesByYear($userId, $selectedYear);

        $today = date('Y-m-d');
        $data['enjoyedLeaves'] = [];
        $data['upcomingLeaves'] = [];

        foreach ($approvedLeaves as $leave) {
            if ($leave['np_hrm_tbl_clm_end_date'] < $today) {
                $data['enjoyedLeaves'][] = $leave;
            } else {
                $data['upcomingLeaves'][] = $leave;
            }
        }

        $data['selectedYear'] = $selectedYear;
        $data['years'] = range(date('Y'), date('Y') - 5);

        return $this->viewLoad("leave/statistics", $data);
    }

    #[NoReturn] public function export_csv(): void
    {
        // Ensure user is authorized
        if ($this->checkAuth()) {
            exit("Unauthorized access");
        }

        $userId = $this->getUserId();
        $selectedYear = $this->request->getGet('year') ?? date('Y');

        // Fetch the data using your existing model method
        $approvedLeaves = $this->userModel->getApprovedLeavesByYear($userId, $selectedYear);

        $filename = "Leave_Statistics_" . $selectedYear . ".csv";

        // Set Browser Headers for Download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV Headers (matching your table columns)
        fputcsv($output, [
            'Sl',
            'Leave Type',
            'Start Date',
            'End Date',
            'Total Days',
            'Station Leave',
            'Alternate Person',
            'Reason',
            'Status'
        ]);

        // Populate Data
        if (!empty($approvedLeaves)) {
            $i = 1;
            foreach ($approvedLeaves as $row) {
                $stationLeave = ($row['np_hrm_tbl_clm_station_leave'] == 0) ? "Yes" : "No";

                fputcsv($output, [
                    $i++,
                    $row['leave_type_name'],
                    $row['np_hrm_tbl_clm_start_date'],
                    $row['np_hrm_tbl_clm_end_date'],
                    $row['np_hrm_tbl_clm_total_days'],
                    $stationLeave,
                    $row['alternate_name'] ?? 'N/A',
                    $row['np_hrm_tbl_clm_reason'],
                    'Approved'
                ]);
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Inbox: 0 (Pending Sup) and 1 (Pending App)
     */
    public function leaveApproval(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $userId = $this->getUserId();
        $data = $this->getCommonFormData();
        $data['submenu'] = 'approvals';

        if (strtolower($this->request->getMethod()) === 'post') {
            $reqId   = $this->request->getPost('request_id');
            $action  = $this->request->getPost('action');
            $comment = $this->request->getPost('comment');
            $role    = $this->request->getPost('role');

            // 1. Fetch Leave Details before updating so we know who the applicant is
            $leaveRequest = $this->userModel->getLeaveRequestById($reqId);

            if ($this->userModel->processLeaveAction($reqId, $userId, $role, $action, $comment)) {

                // --- SMS NOTIFICATION LOGIC ---
                if ($leaveRequest) {
                    $applicantId   = $leaveRequest['user_id'];
                    $applicantName = $leaveRequest['full_name']; // Ensure your model join returns this
                    $leaveType     = ($leaveRequest['leave_type'] == CASUAL_LEAVE_ID) ? "Casual Leave" : "Leave";

                    $smsStatus = 0;
                    if ($action === 'reject') {
                        $smsStatus = 2; // Rejected
                    } else {
                        // If approved/recommended
                        $smsStatus = ($role === 'supervisor') ? 3 : 1;
                    }

                    // Send SMS to Applicant
                    notify_leave_status($applicantId, $applicantName, $leaveType, $smsStatus, $this->userModel);

                    // OPTIONAL: If supervisor recommends (status 3), notify the Approver too
                    if ($smsStatus === 3 && !empty($leaveRequest['approver_id'])) {
                        notify_leave_status($leaveRequest['approver_id'], $applicantName, $leaveType, 0, $this->userModel);
                    }
                }
                // --- END SMS LOGIC ---

                $statusMsg = ($action === 'approve')
                    ? ($role === 'supervisor' ? "Recommended successfully." : "Approved successfully.")
                    : "Request rejected.";

                $data['success'] = "Success: Leave Application $statusMsg";
            } else {
                $data['error'] = "Critical Error: Could not update request status.";
            }
        }

        $data['pendingLeaves'] = $this->userModel->getPendingApprovalsForUser($userId);

        return $this->viewLoad("leave/leaveApproval", $data);
    }

    /**
     * Archive: Status 2 (Final Approved) and 3 (Rejected)
     */
    public function leaveApprovalArchive(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = $this->getCommonFormData();
        $data['submenu'] = 'approvalArchive';
        $userId = $this->getUserId();

        $selectedYear = $this->request->getGet('year') ?? date('Y');
        $data['selectedYear'] = $selectedYear;

        // Uses the Model's logic for Status 2 and 3
        $data['archives'] = $this->userModel->getLeaveApprovalArchives($userId, $selectedYear);
        $data['years'] = range(date('Y'), date('Y')-2);

        return $this->viewLoad("leave/leaveApprovalArchive", $data);
    }

    public function setLeaveApprover(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $userId = $this->getUserId();
        $currentGrade = $this->userModel->getUserGrade($userId);
        $approverGrade = 9; // According to the new office order, changed on 14/07/2026
        $data = $this->getCommonFormData($currentGrade, $approverGrade);
        $data['submenu'] = 'setLeaveApprover';

        $existingConfig = $this->userModel->getApprovalSettings($userId);
        $data['existing_config'] = $existingConfig;
        $data['step'] = 1;
        $data['post_data'] = [];

        if (strtolower($this->request->getMethod()) === 'post') {

            if ($existingConfig) {
                return redirect()->back()->with('error', 'Settings are locked and cannot be modified.');
            }

            $currentStep  = $this->request->getPost('step');
            $supervisorId = $this->request->getPost('supervisor_id');
            $approverId   = $this->request->getPost('approver_id');

            if ($currentStep == 1) {
                $data['step'] = 2;
                $data['post_data'] = [
                    'supervisor_id'   => $supervisorId,
                    'approver_id'     => $approverId,
                    'supervisor_info' => $this->userModel->getUserData($supervisorId),
                    'approver_info'   => $this->userModel->getUserData($approverId)
                ];
            } elseif ($currentStep == 2) {
                $insertData = [
                    'np_hrm_tbl_clm_user_id'       => $userId,
                    'np_hrm_tbl_clm_supervisor_id' => $supervisorId,
                    'np_hrm_tbl_clm_approver_id'   => $approverId,
                    'np_hrm_tbl_clm_created_at'    => time(),
                    'np_hrm_tbl_clm_updated_at'    => time()
                ];

                if ($this->userModel->saveApprovalSettings($insertData)) {
                    return redirect()->to(base_url('leave/set-approver'))
                        ->with('success', 'Approvers have been successfully set and locked.');
                } else {
                    $data['error'] = "Critical Error: Could not save approval settings.";
                }
            }
        }

        return $this->viewLoad("leave/leaveApprover", $data);
    }

    public function applyForCL(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $userId = $this->getUserId();

        $config = $this->userModel->getApprovalSettings($userId);
        if (!$config) {
            return redirect()->to(base_url('home'))->with('error', 'You have not selected your Supervisor and Approver yet. Please select them first to apply for leave.');
        }

        $currentGrade = $this->userModel->getUserGrade($userId);
        $data = $this->getCommonFormData($currentGrade);
        $data['submenu'] = 'applyForCL';
        $currentYear = date('Y');
        $data['balances'] = $this->getFormattedBalances($userId, $currentYear);
        $data['cl_id'] = 1;
        $data['user_current_grade'] = $currentGrade;
        $data['sup_id'] = $config['np_hrm_tbl_clm_supervisor_id'];
        $data['app_id'] = $config['np_hrm_tbl_clm_approver_id'];

        if (strtolower($this->request->getMethod()) === 'post') {
            $data = $this->processLeaveSubmission($data);
        }

        return $this->viewLoad("leave/applyForCL", $data);
    }

    public function applyForOthers(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $userId = $this->getUserId();

        $config = $this->userModel->getApprovalSettings($userId);
        if (!$config) {
            return redirect()->to(base_url('home'))->with('error', 'You have not selected your Supervisor and Approver yet. Please select them first to apply for leave.');
        }

        $currentGrade = $this->userModel->getUserGrade($userId);
        $data = $this->getCommonFormData($currentGrade);
        $data['submenu'] = 'applyForOthers';
        $currentYear = date('Y');
        $data['balances'] = $this->getFormattedBalances($userId, $currentYear);
        $data['user_current_grade'] = $currentGrade;
        $data['sup_id'] = $config['np_hrm_tbl_clm_supervisor_id'];
        $data['app_id'] = $config['np_hrm_tbl_clm_approver_id'];

        if (strtolower($this->request->getMethod()) === 'post') {
            $data = $this->processLeaveSubmission($data);
        }

        return $this->viewLoad("leave/applyForOthers", $data);
    }

    // -------------------------------------------------------------------------
    // Internal
    // -------------------------------------------------------------------------

    /**
     * Centralized Authentication Check
     */
    private function checkAuth(): ?RedirectResponse
    {
        return $this->restrictAccess();
    }

    private function getFormattedBalances(int $userId, string $year): array
    {
        $rawBalances = $this->userModel->getUserLeaveBalances($userId, $year);
        $formatted = [];

        foreach ($rawBalances as $typeId => $bal) {
            $allocated = (int)$bal['np_hrm_tbl_clm_allocated_days'];
            $taken     = (int)$bal['np_hrm_tbl_clm_taken_days'];

            $formatted[$typeId] = [
                'allocated' => $allocated,
                'taken'     => $taken,
                'remaining' => $allocated - $taken
            ];
        }

        return $formatted;
    }

    private function processLeaveSubmission(array $data): array
    {
        // 1. Validation Logic
        $rules = $this->getValidationRulesForCLLeave();

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator;
            $data['error'] = "<strong>Please correct the following:</strong>" . $this->validator->listErrors();
            return $data;
        }

        // 2. Data Retrieval from Post
        $userId           = $this->getUserId();
        $typeId           = $this->request->getPost('leave_type');
        $totalDays        = (int)$this->request->getPost('total_days');
        $startStr         = $this->request->getPost('start_date');
        $endStr           = $this->request->getPost('end_date');
        $station_leave    = $this->request->getPost('station_leave');
        $address_in_leave = $this->request->getPost('address_in_leave');
        $alternate_id     = $this->request->getPost('alternate_id');
        $approver_id      = $this->request->getPost('approver_id');
        $reason           = $this->request->getPost('reason');

        // 3. Leave Balance Validation
        $currentYear = date('Y', strtotime($startStr));
        $balances    = $this->userModel->getUserLeaveBalances($userId, $currentYear);

        if (!isset($balances[$typeId])) {
            $data['error'] = "Error: No leave balance record found for this type for the year $currentYear.";
            return $data;
        }

        $allocated = (int)$balances[$typeId]['np_hrm_tbl_clm_allocated_days'];
        $taken     = (int)$balances[$typeId]['np_hrm_tbl_clm_taken_days'];
        $remaining = $allocated - $taken;

        if ($totalDays > $remaining) {
            $data['error'] = "Insufficient Balance! You requested $totalDays days, but you only have $remaining days remaining for $currentYear.";
            return $data;
        }

        // 4. Date Constraint Validation
        $tomorrow  = strtotime(date('Y-m-d', strtotime('+1 day')));
        $startTime = strtotime($startStr);
        $endTime   = strtotime($endStr);

        if ($startTime < $tomorrow) {
            $data['error'] = "Validation failed: Start date must be from tomorrow onwards.";
            return $data;
        }

        if ($endTime < $startTime) {
            $data['error'] = "Validation failed: End date cannot be before the start date.";
            return $data;
        }

        // 5. Prepare Form Data for Database
        $formData = [
            'user_id'            => $userId,
            'leave_type'         => $typeId,
            'start_date'         => $startStr,
            'end_date'           => $endStr,
            'total_days'         => $totalDays,
            'station_leave'      => $station_leave,
            'address_in_leave'   => $address_in_leave,
            'reason'             => $reason,
            'approver_id'        => $approver_id,
            'alternate_id'       => $alternate_id,
            'session_id'         => $this->getSessionID(),
            'user_current_grade' => $data['user_current_grade'],
        ];

        // Add supervisor if user grade requires it
        if ($data['user_current_grade'] > NO_SUPERVISOR_UPTO_GRADE) {
            $formData['supervisor_id'] = $this->request->getPost('supervisor_id');
        }

        // 6. Execution and Notification
        if ($this->userModel->applyForLeave($formData)) {

            /* Send SMS to supervisor and approver for the acknowledgement */
            /*

            $userBaseInfo = $this->userModel->getUserData($userId);
            $applicantName = $userBaseInfo['Name_English'] ?? 'An Employee';

            $leaveTypeName = ($typeId == CASUAL_LEAVE_ID) ? "Casual Leave" : "Leave";

            // Send SMS to Supervisor (Status 0 = Applied)
            if (!empty($formData['supervisor_id'])) {
                notify_leave_status(
                    $formData['supervisor_id'],
                    $applicantName,
                    $leaveTypeName,
                    0,
                    $this->userModel
                );
            }

            // Send SMS to Approver (Status 0 = Applied)
            if (!empty($formData['approver_id'])) {
                notify_leave_status(
                    $formData['approver_id'],
                    $applicantName,
                    $leaveTypeName,
                    0,
                    $this->userModel
                );
            }
            */

            // $data['success'] = "Leave application submitted successfully! Notification SMS has been sent to the concerned officers.";

            $data['success'] = "Leave application submitted successfully!";
            $_POST = [];
            $this->request->setGlobal('post', []);

        } else {
            $data['error'] = "Critical Error: Could not save leave request to the database.";
        }

        return $data;
    }

    private function getCommonFormData($grade = null, $approverGrade = null): array
    {
        return [
            'menu'         => 'leave',
            'session_data' => $this->getFullSession(),
            'departments'  => function_exists('get_npp_departments') ? get_npp_departments() : [],
            'leaveType'    => $this->userModel->getLeaveType(),
            'allUsers'     => $this->userModel->getAllUsersUntilGrade($grade),
            'supUsers'     => $this->userModel->getAllUsersUntilGrade(min($grade + 1, 9) ),
            'appUsers'     => $this->userModel->getAllUsersUntilGrade($approverGrade != null ? $approverGrade : min($grade,6) ),
            'alt_users'    => $this->userModel->getAllUsersUntilGrade($grade + 3), // Alternative personnel can be below 2 grades
            'userData'     => $this->userModel->getUserData($this->getUserId()),
            'success'      => null,
            'error'        => null,
            'validation'   => null,
            'formData'     => []
        ];
    }

    private function processCancel($requestId): array
    {
        if (!$requestId) {
            return ['status' => 'error', 'message' => 'Invalid Request ID.'];
        }

        $userId = $this->getUserId();
        if ($this->userModel->cancelLeaveRequest((int)$requestId, $userId)) {
            return ['status' => 'success', 'message' => 'Leave request cancelled successfully.'];
        } else {
            return ['status' => 'error', 'message' => 'Unable to cancel request. It may have already been processed.'];
        }
    }

    protected function ensureApproversSet(): ?RedirectResponse
    {
        $userId = $this->getUserId();
        $config = $this->userModel->getApprovalSettings($userId);

        if (!$config) {
            return redirect()->to(base_url('home'))->with('error', 'You have not selected your Supervisor and Approver yet. Please select them first to apply for leave.');
        }

        return null;
    }

    protected function getValidationRulesForCLLeave(): array
    {
        return [
            'leave_type' => [
                'label'  => 'Leave Type',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Please select the type of leave you are applying for.',
                ],
            ],
            'total_days' => [
                'label'  => 'Total Days',
                'rules'  => 'required|greater_than[0]',
                'errors' => [
                    'required'     => 'Total days cannot be empty.',
                    'greater_than' => 'Total days must be at least 1.',
                ],
            ],
            'approver_id' => [
                'label'  => 'Approver',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'You must select an Approving Officer.',
                ],
            ],
            'reason' => [
                'label'  => 'Reason for Leave',
                'rules'  => 'required|min_length[5]',
                'errors' => [
                    'required'   => 'Please provide a reason for your leave request.',
                    'min_length' => 'The reason must be at least 5 characters long.',
                ],
            ],
            'start_date' => [
                'label'  => 'Start Date',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => 'Please pick a start date.',
                    'valid_date' => 'The start date provided is not a valid date format.',
                ],
            ],
            'end_date' => [
                'label'  => 'End Date',
                'rules'  => 'required|valid_date',
                'errors' => [
                    'required'   => 'Please pick an end date.',
                    'valid_date' => 'The end date provided is not a valid date format.',
                ],
            ],
        ];
    }
}