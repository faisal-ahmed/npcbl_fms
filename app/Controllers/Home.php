<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    protected UserModel $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * Centralized Authentication Check
     */
    private function checkAuth(): ?RedirectResponse
    {
        return $this->restrictAccess();
    }

    public function index(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $userId = $this->getUserId();
        $currentYear = date('Y');

        // 1. Get all leave types (CL, SL, EL, etc.)
        $leaveTypes = $this->userModel->getLeaveType();

        // 2. Get this user's specific balance records for 2026
        $balances = $this->userModel->getUserLeaveBalances($userId, $currentYear);

        $leaveStats = [];
        $colors = ['progress-bar-success', 'progress-bar-primary', 'progress-bar-info', 'progress-bar-warning', 'progress-bar-danger'];

        foreach ($leaveTypes as $index => $type) {
            $typeId = $type['type_id']; // From Model select alias

            // 3. Match balance record or set defaults if no record exists yet
            $allocated = 0;
            $taken = 0;

            if (isset($balances[$typeId])) {
                $allocated = (int)$balances[$typeId]['np_hrm_tbl_clm_allocated_days'];
                $taken     = (int)$balances[$typeId]['np_hrm_tbl_clm_taken_days'];
            }

            // 4. Calculate Remaining & Percentage
            $remaining = $allocated - $taken;
            $percent   = ($allocated > 0) ? round(($taken / $allocated) * 100) : 0;

            $leaveStats[] = [
                'type_name' => $type['type_name'],
                'allocated' => $allocated,
                'spent'     => $taken,
                'remaining' => $remaining,
                'percent'   => $percent,
                'color'     => $colors[$index % count($colors)]
            ];
        }

        $data = [
            'menu'         => 'home',
            'submenu'      => 'homeIndex',
            'session_data' => $this->getFullSession(),
            'user_data'    => $this->userModel->getUserData($userId),
            'leave_stats'  => $leaveStats,
        ];

        return $this->viewLoad('home/index', $data);
    }

    public function updatePassword(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = ['menu' => 'home', 'submenu' => 'updatePassword'];

        if ($this->request->is('post')) {
            $newPassword     = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_new_password');

            if ($newPassword !== $confirmPassword) {
                $data['error'] = "New password and confirm password do not match.";
            } else {
                $result = $this->userModel->updatePassword(
                    $this->getUserId(),
                    $this->request->getPost('current_password'),
                    $newPassword
                );

                if ($result === true) $data['success'] = "Password updated successfully.";
                else $data['error'] = $result;
            }
        }

        return $this->viewLoad('home/updatePassword', $data);
    }

    public function logout(): RedirectResponse
    {
        // 1. Update Database Session to 'inactive'
        $sessionId = session_id();
        if ($sessionId) {
            $db = \Config\Database::connect();
            $db->table('np_hrm_tbl_auth_session')
                ->where('np_hrm_tbl_clm_session_id', $sessionId)
                ->update(['np_hrm_tbl_clm_status' => 'inactive']);
        }

        // 2. Clear Local Session
        $this->clearSession();

        return redirect()->to(base_url('hrm'));
    }

    /**
     * Display Personal Information
     * Uses UserModel::getUserData() to fetch basic + extended + addresses
     */
    public function personalInfo(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $userId = $this->getUserId();
        $userDetails = $this->userModel->getUserData($userId);

        if (empty($userDetails)) {
            return redirect()->to(base_url('home'))->with('error', 'Profile data not found.');
        }

        $data = [
            'menu'         => 'home',
            'submenu'      => 'personalInfo',
            'session_data' => $this->getFullSession(),
            'user'         => $userDetails, // Access fields via $user['Name_English'], etc.
            'departments'  => function_exists('get_npp_departments') ? get_npp_departments() : [],
            'districts'    => function_exists('get_all_districts') ? get_all_districts() : [],
        ];

        return $this->viewLoad('common/personal_info', $data);
    }

    public function manualLeaveEntry(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = [
            'menu'     => 'admin',
            'submenu'  => 'manualLeave',
            'allUsers' => $this->userModel->getAllUsersUntilGrade(),
            'leaveTypes' => $this->userModel->getLeaveType(),
            'success'  => null,
            'error'    => null
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $adminId   = $this->getUserId();

            // Satisfy the NOT NULL constraint for session_id
            $currentSessionId = session_id() ?: 'admin_manual_'.time();

            $insertData = [
                'np_hrm_tbl_clm_user_id'              => $this->request->getPost('target_user_id'),
                'np_hrm_tbl_clm_leave_type_id'        => $this->request->getPost('leave_type'),
                'np_hrm_tbl_clm_start_date'           => $this->request->getPost('start_date'),
                'np_hrm_tbl_clm_end_date'             => $this->request->getPost('end_date'),
                'np_hrm_tbl_clm_total_days'           => (int)$this->request->getPost('total_days'),
                'np_hrm_tbl_clm_reason'               => $this->request->getPost('reason'),
                'np_hrm_tbl_clm_status'               => 2, // Approved
                'np_hrm_tbl_clm_approver_id'          => $adminId,
                'np_hrm_tbl_clm_approver_remarks'     => "Manual Entry by Admin: " . $this->request->getPost('hr_remarks'),
                'np_hrm_tbl_clm_approver_action_time' => time(), // Bigint in schema
                'np_hrm_tbl_clm_submission_time'      => time(), // Bigint in schema
                'np_hrm_tbl_clm_session_id'           => $currentSessionId, // REQUIRED NOT NULL
                'np_hrm_tbl_clm_created_at'           => time(),
                'np_hrm_tbl_clm_updated_at'           => time()
            ];

            // Process insertion
            if ($this->userModel->insertManualLeave($insertData)) {
                $data['success'] = "Leave entry successfully saved and balance updated.";
            } else {
                $data['error'] = "Critical Error: Database rejected the entry. Check if the session_id or user balance row exists.";
            }
        }

        return $this->viewLoad("home/manualLeaveEntry", $data);
    }

    public function updateUserProfile(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = [
            'menu'       => 'admin',
            'submenu'    => 'updateUserProfile',
            'allUsers'   => $this->userModel->getAllUsersUntilGrade(),
            'leaveTypes' => $this->userModel->getLeaveType(),
            'success'    => null,
            'error'      => null
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $targetUserId = $this->request->getPost('target_user_id');

            $updateData = [];
            $accountStatus = $this->request->getPost('account_status');
            $resetPass = $this->request->getPost('reset_password');

            $updateData['np_hrm_tbl_clm_status'] = ($accountStatus === 'active') ? 1 : 0;

            if ($resetPass === 'yes') {
                $updateData['np_hrm_tbl_clm_password_hash'] = password_hash(DEFAULT_PASSWORD, PASSWORD_DEFAULT);
            }

            if ($this->userModel->updateAccountSettings($targetUserId, $updateData)) {
                $data['success'] = "User account settings updated successfully.";
            } else {
                $data['error'] = "Failed to update account settings.";
            }
        }

        return $this->viewLoad("home/updateUserProfile", $data);
    }

    public function myTeamMembers(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = [
            'menu'    => 'myTeam',
            'submenu' => 'myTeam',
            'success' => null,
            'error'   => null
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $targetUserId = $this->request->getPost('user_id');
            $actionFlag   = $this->request->getPost('action_flag');

            if ($targetUserId && in_array($actionFlag, ['confirm', 'cancel'], true)) {
                $isProcessed = $this->userModel->updateEmployeeApprovalStatus($targetUserId, $actionFlag);

                if ($isProcessed) {
                    $statusText      = ($actionFlag === 'confirm') ? 'approved' : 'cancelled';
                    $data['success'] = "Employee request successfully {$statusText}.";
                } else {
                    $data['error']   = "Failed to update approval status. Please try again.";
                }
            }
        }

        $data['records'] = $this->userModel->getSupervisorApproverMasterList($this->getUserId());

        return $this->viewLoad("home/supervisorApproverList", $data);
    }

    public function supervisorApproverList(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = [
            'type'    => 'all',
            'menu'    => 'admin',
            'submenu' => 'supervisorApproverList',
            'records' => $this->userModel->getSupervisorApproverMasterList()
        ];

        return $this->viewLoad("home/supervisorApproverList", $data);
    }

    public function exportSupervisorApproverExcel()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $records = $this->userModel->getSupervisorApproverMasterList();
        $fileName = "Supervisor_Approver_List_" . date('Y-m-d') . ".csv";

        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=$fileName");

        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM for Bangla

        fputcsv($file, [
            'Sl', 'Employee Name', 'Payroll ID', 'Bio ID', 'Designation', 'Email',
            'Rem. Leave', 'Year', 'Supervisor', 'Sup. Desig', 'Sup. Payroll', 'Sup. Bio',
            'Approver', 'App. Desig', 'App. Payroll', 'App. Bio'
        ]);

        $i = 1;
        foreach ($records as $r) {
            fputcsv($file, [
                $i++,
                $r['emp_name'],
                '="' . $r['emp_payroll'] . '"',
                '="' . $r['emp_bio'] . '"',
                $r['emp_desig'],
                $r['emp_email'],
                $r['remaining_leave'] ?? 0,
                $r['leave_year'] ?? date('Y'),
                $r['supervisor_name'] ?? 'N/A',
                $r['supervisor_desig'] ?? 'N/A',
                '="' . ($r['supervisor_payroll'] ?? '') . '"',
                '="' . ($r['supervisor_bio'] ?? '') . '"',
                $r['approver_name'] ?? 'N/A',
                $r['approver_desig'] ?? 'N/A',
                '="' . ($r['approver_payroll'] ?? '') . '"',
                '="' . ($r['approver_bio'] ?? '') . '"',
            ]);
        }
        fclose($file);
        exit;
    }

    /**
     * AJAX method to fetch user info - Secure POST version
     */
    public function ajaxGetUserLeaveProfile(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(403);

        $userId = $this->request->getPost('user_id');
        $details = $this->userModel->getAdminUserLeaveProfile($userId, date('Y'));

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $details,
            'token'  => csrf_hash()
        ]);
    }

    /**
     * Step 1: Validate Input & Upload Image
     */
    private function handleStep1Upload(array &$data): string|RedirectResponse
    {
        $validation = \Config\Services::validation();
        // Assuming this method exists in BaseController
        $rules = $this->getValidationRulesForRegistration();
        $postData = $this->request->getPost();

        // Conditional Validation
        if (($postData['marital_status'] ?? '') === 'Married') {
            $rules['spouse_name']   = 'required|min_length[3]';
            $rules['spouse_mobile'] = 'required|numeric|min_length[10]|max_length[15]';
        }

        $rules['official_picture'] = 'uploaded[official_picture]|is_image[official_picture]|max_size[official_picture,2048]';

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            $data['validation'] = $validation;
            $data['error']      = 'Please fix the errors below and try again.';
            return $this->viewLoad('common/personal_info', $data);
        }

        // Handle File Upload
        $file = $this->request->getFile('official_picture');

        if ($file->isValid() && !$file->hasMoved()) {
            // Use FCPATH for public directory access
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads', $newName);

            $postData['official_picture'] = base_url('uploads/' . $newName);

            // Pass data to Step 2 via Flashdata
            $this->session->setFlashdata('formData', $postData);
            $this->session->setFlashdata('step', '2');

            return redirect()->to(current_url());
        }

        $data['error'] = 'File upload failed.';
        return $this->viewLoad('common/personal_info', $data);
    }

    /**
     * Step 2: Final Confirmation & Save to DB
     */
    private function handleStep2Save(array &$data): void
    {
        if (strtolower($this->request->getPost('confirm')) === 'yes') {
            $formData = $this->request->getPost();

            // Save to DB
            $status = $this->userModel->saveRegistration($formData, $formData['official_picture']);

            if ($status === true) {
                $data['successSubmission'] = true;
                $data['success'] = 'Thank You! Your information has been updated successfully!';
            } else {
                $data['error'] = $status;
            }
        } else {
            $data['error'] = 'Submission cancelled by user.';
        }

        // Reset to step 1 after completion or cancellation
        $data['step'] = '1';
    }
}