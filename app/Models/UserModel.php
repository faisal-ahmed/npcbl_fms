<?php

namespace App\Models;

use App\Models\BaseModel;
use Exception;
use Throwable;

class UserModel extends BaseModel
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function checkPayrollIdExists(string $payrollId): bool
    {
        return $this->db->table('np_hrm_tbl_user_basic_info')
                ->where('np_hrm_tbl_clm_payroll_id', $payrollId)
                ->countAllResults() > 0;
    }

    /**
     * Fetch User Profile with joined addresses
     */
    public function getUserData(int $user_id = 0, ?string $payroll_id = null): array
    {
        $builder = $this->db->table('np_hrm_tbl_user_basic_info c');
        $builder->select([
            'c.np_hrm_tbl_clm_user_id AS User_ID',
            'c.np_hrm_tbl_clm_payroll_id AS Payroll_ID',
            'c.np_hrm_tbl_clm_profile_picture AS Profile_Picture',
            'ex.np_hrm_tbl_clm_name_en AS Name_English',
            'ex.np_hrm_tbl_clm_name_bn AS Name_Bangla',
            'c.np_hrm_tbl_clm_official_email AS Official_Email',
            'c.np_hrm_tbl_clm_personal_email AS Personal_Email',
            'c.np_hrm_tbl_clm_status AS User_Status',
            'ex.np_hrm_tbl_clm_contact_number AS Contact_Number',
            'ex.np_hrm_tbl_clm_joining_date AS Joining_Date',
            'ex.np_hrm_tbl_clm_joining_department AS Joining_Department',
            'ex.np_hrm_tbl_clm_designation_npcbl AS Designation_NPCBL',
            'ex.np_hrm_tbl_clm_designation_crnpp AS Designation_CRNPP',
            'ex.np_hrm_tbl_clm_designation_rnpp AS Designation_RNPP',
            'ex.np_hrm_tbl_clm_father_name AS Father_Name',
            'ex.np_hrm_tbl_clm_father_mobile AS Father_Mobile',
            'ex.np_hrm_tbl_clm_mother_name AS Mother_Name',
            'ex.np_hrm_tbl_clm_mother_mobile AS Mother_Mobile',
            'ex.np_hrm_tbl_clm_date_of_birth AS Date_Of_Birth',
            'ex.np_hrm_tbl_clm_birth_district AS Birth_District',
            'ex.np_hrm_tbl_clm_gender AS Gender',
            'ex.np_hrm_tbl_clm_religion AS Religion',
            'ex.np_hrm_tbl_clm_blood_group AS Blood_Group',
            'ex.np_hrm_tbl_clm_marital_status AS Marital_Status',
            'ex.np_hrm_tbl_clm_spouse_name AS Spouse_Name',
            'ex.np_hrm_tbl_clm_spouse_mobile AS Spouse_Mobile',
            'ex.np_hrm_tbl_clm_emergency_contact_name AS Emergency_Contact_Name',
            'ex.np_hrm_tbl_clm_emergency_contact_mobile AS Emergency_Contact_Mobile',
            'ex.np_hrm_tbl_clm_emergency_relation AS Emergency_Contact_Relation',
            'ex.np_hrm_tbl_clm_gate_pass AS Gate_Pass_No',
            'ex.np_hrm_tbl_clm_biometric_serial AS BioMetric_Serial',
            'ex.np_hrm_tbl_clm_nid AS NID',
            'ex.np_hrm_tbl_clm_shop_rnpp AS Shop_RNPP',
            'ex.np_hrm_tbl_clm_division_rnpp AS Division_RNPP',
            'ex.np_hrm_tbl_clm_training_group_no AS Training_Group_No',
            'ex.np_hrm_tbl_clm_training_group_name AS Training_Group_Name',
            'ex.np_hrm_tbl_clm_training_duration AS Training_Duration',

            'ad.np_hrm_tbl_clm_address_line1 AS Present_Address_Line_1',
            'ad.np_hrm_tbl_clm_address_line2 AS Present_Address_Line_2',
            'ad.np_hrm_tbl_clm_post_office AS Present_Post_Office',
            'ad.np_hrm_tbl_clm_police_station AS Present_Police_Station',
            'ad.np_hrm_tbl_clm_district AS Present_District',

            'ad2.np_hrm_tbl_clm_address_line1 AS Permanent_Address_Line_1',
            'ad2.np_hrm_tbl_clm_address_line2 AS Permanent_Address_Line_2',
            'ad2.np_hrm_tbl_clm_post_office AS Permanent_Post_Office',
            'ad2.np_hrm_tbl_clm_police_station AS Permanent_Police_Station',
            'ad2.np_hrm_tbl_clm_district AS Permanent_District',

            'ad3.np_hrm_tbl_clm_address_line1 AS Mailing_Address_Line_1',
            'ad3.np_hrm_tbl_clm_address_line2 AS Mailing_Address_Line_2',
            'ad3.np_hrm_tbl_clm_post_office AS Mailing_Post_Office',
            'ad3.np_hrm_tbl_clm_police_station AS Mailing_Police_Station',
            'ad3.np_hrm_tbl_clm_district AS Mailing_District',
        ]);

        $builder->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = c.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_address ad', 'ad.np_hrm_tbl_clm_address_id = ex.np_hrm_tbl_clm_present_address_id', 'left');
        $builder->join('np_hrm_tbl_address ad2', 'ad2.np_hrm_tbl_clm_address_id = ex.np_hrm_tbl_clm_permanent_address_id', 'left');
        $builder->join('np_hrm_tbl_address ad3', 'ad3.np_hrm_tbl_clm_address_id = ex.np_hrm_tbl_clm_mailing_address_id', 'left');

        if ($user_id !== 0) $builder->where('c.np_hrm_tbl_clm_user_id', $user_id);
        if ($payroll_id) $builder->where('c.np_hrm_tbl_clm_payroll_id', $payroll_id);

        return $builder->get()->getRowArray() ?? [];
    }

    public function getAllUsersUntilGrade($grade = null): array
    {
        $builder = $this->db->table('np_hrm_tbl_user_basic_info c')
            ->select("c.np_hrm_tbl_clm_user_id AS user_id, ex.np_hrm_tbl_clm_name_en AS full_name, ex.np_hrm_tbl_clm_designation_npcbl AS designation, c.np_hrm_tbl_clm_official_email AS email, np_hrm_tbl_clm_payroll_id AS office_id")
            ->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = c.np_hrm_tbl_clm_user_id');

        if ($grade !== null) {
            $builder->where('c.np_hrm_tbl_clm_grade <', ($grade + 1)); // To include own grade to remove conflict for ET/AM.
        }

        return $builder->orderBy('c.np_hrm_tbl_clm_grade', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getUserGrade($userId)
    {
        $user = $this->db->table('np_hrm_tbl_user_basic_info')
            ->select('np_hrm_tbl_clm_grade')
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->get()
            ->getRow();

        return $user ? $user->np_hrm_tbl_clm_grade : null;
    }

    /**
     * Fetches a specific leave request with applicant details
     */
    public function getLeaveRequestById(int $reqId): ?array
    {
        return $this->db->table('np_hrm_tbl_leave_request l')
            ->select('
            l.*, 
            l.np_hrm_tbl_clm_leave_type_id AS leave_type,
            b.np_hrm_tbl_clm_user_id AS user_id, 
            b.np_hrm_tbl_clm_official_email AS email,
            ex.np_hrm_tbl_clm_name_en AS full_name
        ')
            ->join('np_hrm_tbl_user_basic_info b', 'b.np_hrm_tbl_clm_user_id = l.np_hrm_tbl_clm_user_id')
            ->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = b.np_hrm_tbl_clm_user_id')
            ->where('l.np_hrm_tbl_clm_leave_request_id', $reqId)
            ->get()
            ->getRowArray();
    }

    public function getLeaveType(): array
    {
        return $this->db->table('np_hrm_tbl_leave_type')
            ->select('np_hrm_tbl_clm_leave_type_id AS type_id, np_hrm_tbl_clm_leave_type_name AS type_name')
            ->orderBy('type_name', 'ASC')
            ->get()->getResultArray();
    }

    public function cancelLeaveRequest(int $requestId, int $userId): bool
    {
        return $this->db->table('np_hrm_tbl_leave_request')
            ->where('np_hrm_tbl_clm_leave_request_id', $requestId)
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->whereIn('np_hrm_tbl_clm_status', [0, 1])
            ->update([
                'np_hrm_tbl_clm_status'     => 4,
                'np_hrm_tbl_clm_updated_at' => time()
            ]);
    }

    /**
     * Fetches the role name from the database based on Role ID
     */
    public function getRoleTitleById(int $roleId): string
    {
        $role = $this->db->table('np_hrm_tbl_role')
            ->select('np_hrm_tbl_clm_role_name')
            ->where('np_hrm_tbl_clm_role_id', $roleId)
            ->get()
            ->getRow();

        return $role ? $role->np_hrm_tbl_clm_role_name : 'UNKNOWN_ROLE';
    }

    public function getApprovedLeavesByYear(int $userId, int $year): array
    {
        $builder = $this->db->table('np_hrm_tbl_leave_request lr');
        $builder->select([
            'lr.*',
            'lt.np_hrm_tbl_clm_leave_type_name AS leave_type_name',
            // Alternate Person Details
            'alt.np_hrm_tbl_clm_name_en AS alternate_name',
            'alt.np_hrm_tbl_clm_contact_number AS alternate_mobile',
            'alt.np_hrm_tbl_clm_designation_npcbl AS alternate_designation_npcbl',
            // Supervisor Details
            'sup.np_hrm_tbl_clm_name_en AS supervisor_name',
            'sup.np_hrm_tbl_clm_contact_number AS supervisor_mobile',
            'sup.np_hrm_tbl_clm_designation_npcbl AS supervisor_designation_npcbl',
            // Approver Details
            'app.np_hrm_tbl_clm_name_en AS approver_name',
            'app.np_hrm_tbl_clm_contact_number AS approver_mobile',
            'app.np_hrm_tbl_clm_designation_npcbl AS approver_designation_npcbl'
        ]);

        // Joins
        $builder->join('np_hrm_tbl_leave_type lt', 'lt.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id');
        $builder->join('np_hrm_tbl_user_extended_info alt', 'alt.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_alternate_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info sup', 'sup.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_supervisor_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info app', 'app.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_approver_id', 'left');

        // Filtering
        $builder->where('lr.np_hrm_tbl_clm_user_id', $userId);
        $builder->where('lr.np_hrm_tbl_clm_status', 2); // Status 2 for Approved
        $builder->where("YEAR(lr.np_hrm_tbl_clm_start_date)", $year);

        $builder->orderBy('lr.np_hrm_tbl_clm_start_date', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Fetches leave requests where the logged-in user is assigned as the alternative personnel
     */
    public function getAlternativeLeaveRequests(int $userId, string $startDate = null, string $endDate = null): array
    {
        $builder = $this->db->table('np_hrm_tbl_leave_request lr');
        $builder->select([
            'lr.*',
            'lt.np_hrm_tbl_clm_leave_type_name AS leave_type_name',
            // Applicant Details from extended info
            'u.np_hrm_tbl_clm_name_en AS applicant_name',
            'u.np_hrm_tbl_clm_designation_npcbl AS applicant_designation',
            'u.np_hrm_tbl_clm_contact_number AS applicant_mobile',
            // Supervisor Details
            'sup.np_hrm_tbl_clm_name_en AS supervisor_name',
            'sup.np_hrm_tbl_clm_contact_number AS supervisor_mobile',
            'sup.np_hrm_tbl_clm_designation_npcbl AS supervisor_designation_npcbl',
            // Approver Details
            'app.np_hrm_tbl_clm_name_en AS approver_name',
            'app.np_hrm_tbl_clm_contact_number AS approver_mobile',
            'app.np_hrm_tbl_clm_designation_npcbl AS approver_designation_npcbl'
        ]);

        // Joins using your updated table names
        $builder->join('np_hrm_tbl_leave_type lt', 'lt.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id');
        $builder->join('np_hrm_tbl_user_extended_info u', 'u.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id');
        $builder->join('np_hrm_tbl_user_extended_info sup', 'sup.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_supervisor_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info app', 'app.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_approver_id', 'left');

        // Updated column name: np_hrm_tbl_clm_alternate_id
        $builder->where('lr.np_hrm_tbl_clm_alternate_id', $userId);

        if ($startDate && $endDate) {
            $builder->where('lr.np_hrm_tbl_clm_start_date >=', $startDate);
            $builder->where('lr.np_hrm_tbl_clm_start_date <=', $endDate);
        }

        $builder->orderBy('lr.np_hrm_tbl_clm_created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Detailed Leave Search with Handler Context
     */
    public function getLeaveRequestDetails($user_id = null, $req_id = null, $type_id = null, $start = null, $end = null, $status = null, $stage = null, $supervisor_id = null, $approver_id = null): array
    {
        $builder = $this->db->table('np_hrm_tbl_leave_request lr');

        $builder->select([
            'lr.*',
            'lt.np_hrm_tbl_clm_leave_type_name AS leave_type_name',
            'ex.np_hrm_tbl_clm_name_en AS name_english',
            'ub.np_hrm_tbl_clm_payroll_id',
            'sup.np_hrm_tbl_clm_name_en AS supervisor_name',
            'sup.np_hrm_tbl_clm_contact_number AS supervisor_mobile',
            'sup.np_hrm_tbl_clm_designation_npcbl AS supervisor_designation_npcbl',
            'app.np_hrm_tbl_clm_name_en AS approver_name',
            'app.np_hrm_tbl_clm_contact_number AS approver_mobile',
            'app.np_hrm_tbl_clm_designation_npcbl AS approver_designation_npcbl',
            'alt.np_hrm_tbl_clm_name_en AS alternate_name',
            'alt.np_hrm_tbl_clm_contact_number AS alternate_mobile',
            'alt.np_hrm_tbl_clm_designation_npcbl AS alternate_designation_npcbl'
        ]);

        $builder->join('np_hrm_tbl_leave_type lt', 'lt.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id', 'left');
        $builder->join('np_hrm_tbl_user_basic_info ub', 'ub.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info sup', 'sup.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_supervisor_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info app', 'app.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_approver_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info alt', 'alt.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_alternate_id', 'left');

        if ($user_id) $builder->where('lr.np_hrm_tbl_clm_user_id', $user_id);
        if ($req_id) $builder->where('lr.np_hrm_tbl_clm_leave_request_id', $req_id);
        if ($type_id) $builder->where('lr.np_hrm_tbl_clm_leave_type_id', $type_id);
        if ($status !== null) $builder->where('lr.np_hrm_tbl_clm_status', $status);
        if ($supervisor_id) $builder->where('lr.np_hrm_tbl_clm_supervisor_id', $supervisor_id);
        if ($approver_id) $builder->where('lr.np_hrm_tbl_clm_approver_id', $approver_id);

        if ($start) $builder->where('lr.np_hrm_tbl_clm_start_date >=', $start);
        if ($end)   $builder->where('lr.np_hrm_tbl_clm_start_date <=', $end);

        $builder->orderBy('lr.np_hrm_tbl_clm_leave_request_id', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Approval Inbox: Status 0 for Supervisor, Status 1 for Approver
     */
    public function getPendingApprovalsForUser(int $userId): array
    {
        $currentYear = date('Y');
        $builder = $this->db->table('np_hrm_tbl_leave_request lr');

        $builder->select([
            'lr.*',
            'lt.np_hrm_tbl_clm_leave_type_name as type_name',
            'ex.np_hrm_tbl_clm_name_en as applicant_name',
            'ex.np_hrm_tbl_clm_designation_npcbl as applicant_designation',
            'ex.np_hrm_tbl_clm_contact_number as applicant_mobile',
            'ub.np_hrm_tbl_clm_official_email as applicant_email',
            'lb.np_hrm_tbl_clm_allocated_days',
            'lb.np_hrm_tbl_clm_taken_days',
            'alt.np_hrm_tbl_clm_name_en AS alternate_name',
            'alt.np_hrm_tbl_clm_contact_number AS alternate_mobile',
            'alt.np_hrm_tbl_clm_designation_npcbl AS alternate_designation_npcbl'
        ]);

        $builder->join('np_hrm_tbl_leave_type lt', 'lt.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id');
        $builder->join('np_hrm_tbl_user_basic_info ub', 'ub.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id');
        $builder->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id');
        $builder->join('np_hrm_tbl_leave_balance lb',
            "lb.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id " .
            "AND lb.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id " .
            "AND lb.np_hrm_tbl_clm_year = $currentYear",
            'left'
        );
        $builder->join('np_hrm_tbl_user_extended_info alt', 'alt.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_alternate_id', 'left');

        /**
         * Logic:
         * 1. If I am the supervisor AND status is 0, it's my turn.
         * 2. If I am the approver AND status is 1, it's my turn.
         */
        $builder->groupStart()
            ->groupStart()
            ->where('lr.np_hrm_tbl_clm_supervisor_id', $userId)
            ->where('lr.np_hrm_tbl_clm_status', 0) // Stage 1 logic
            ->groupEnd()
            ->orGroupStart()
            ->where('lr.np_hrm_tbl_clm_approver_id', $userId)
            ->where('lr.np_hrm_tbl_clm_status', 1) // Stage 2 logic
            ->groupEnd()
            ->groupEnd();

        // Order by newest first so the user sees fresh requests at the top
        $builder->orderBy('lr.np_hrm_tbl_clm_created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Finalized Requests Archive: Status 2 (Approved) or 3 (Rejected)
     */
    public function getLeaveApprovalArchives(int $userId, string $year): array
    {
        $builder = $this->db->table('np_hrm_tbl_leave_request lr');

        $builder->select([
            'lr.*',
            'lt.np_hrm_tbl_clm_leave_type_name AS leave_type_name',
            'ex.np_hrm_tbl_clm_name_en AS applicant_name',
            'ex.np_hrm_tbl_clm_designation_npcbl AS applicant_designation',
            'ex.np_hrm_tbl_clm_contact_number AS applicant_mobile',
            'ub.np_hrm_tbl_clm_official_email AS applicant_email',
            'alt.np_hrm_tbl_clm_name_en AS alternate_name',
            'alt.np_hrm_tbl_clm_contact_number AS alternate_mobile',
            'alt.np_hrm_tbl_clm_designation_npcbl AS alternate_designation_npcbl'
        ]);

        $builder->join('np_hrm_tbl_leave_type lt', 'lt.np_hrm_tbl_clm_leave_type_id = lr.np_hrm_tbl_clm_leave_type_id', 'left');
        $builder->join('np_hrm_tbl_user_basic_info ub', 'ub.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info alt', 'alt.np_hrm_tbl_clm_user_id = lr.np_hrm_tbl_clm_alternate_id', 'left');

        $builder->groupStart()
            ->where('lr.np_hrm_tbl_clm_supervisor_id', $userId)
            ->orWhere('lr.np_hrm_tbl_clm_approver_id', $userId)
            ->groupEnd();

        $builder->whereIn('lr.np_hrm_tbl_clm_status', [1, 2, 3]);
        $builder->where("YEAR(lr.np_hrm_tbl_clm_start_date)", $year);
        $builder->orderBy('lr.np_hrm_tbl_clm_leave_request_id', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Updates user account status or resets password
     */
    public function updateAccountSettings($userId, $data)
    {
        return $this->db->table('np_hrm_tbl_user_basic_info')
        ->where('np_hrm_tbl_clm_user_id', $userId)
        ->update($data);
    }

    /**
     * Process Leave: 0->1 (Sup Approve), 1->2 (App Approve), x->3 (Reject)
     * Updates Leave Balance on final approval.
     */
    public function processLeaveAction($reqId, $userId, $role, $action, $comment): bool
    {
        $this->db->transBegin();

        try {
            $data = [];
            if ($role === 'supervisor') {
                // Stage 1 Approval
                $data['np_hrm_tbl_clm_stage_order'] = ($action === 'approve') ? 2 : 4;
                $data['np_hrm_tbl_clm_supervisor_remarks'] = $comment;
                $data['np_hrm_tbl_clm_status'] = ($action === 'approve') ? 1 : 3;
                $data['np_hrm_tbl_clm_supervisor_action_time'] = time();
                $data['np_hrm_tbl_clm_updated_at'] = time();
            } else {
                // Stage 2 (Final) Approval
                $data['np_hrm_tbl_clm_stage_order'] = ($action === 'approve') ? 3 : 4;
                $data['np_hrm_tbl_clm_approver_remarks'] = $comment;
                $data['np_hrm_tbl_clm_status'] = ($action === 'approve') ? 2 : 3;
                $data['np_hrm_tbl_clm_updated_at'] = time();

                // Update balance only if the final approver says 'approve'
                if ($action === 'approve') {
                    $request = $this->db->table('np_hrm_tbl_leave_request')
                        ->where('np_hrm_tbl_clm_leave_request_id', $reqId)
                        ->get()->getRowArray();

                    if ($request) {
                        $year = date('Y', strtotime($request['np_hrm_tbl_clm_start_date']));

                        $this->db->table('np_hrm_tbl_leave_balance')
                            ->where([
                                'np_hrm_tbl_clm_user_id'       => $request['np_hrm_tbl_clm_user_id'],
                                'np_hrm_tbl_clm_leave_type_id' => $request['np_hrm_tbl_clm_leave_type_id'],
                                'np_hrm_tbl_clm_year'          => $year
                            ])
                            ->set('np_hrm_tbl_clm_taken_days', 'np_hrm_tbl_clm_taken_days + ' . (int)$request['np_hrm_tbl_clm_total_days'], false)
                            ->set('np_hrm_tbl_clm_last_updated', time())
                            ->update();
                    }
                }
            }

            $this->db->table('np_hrm_tbl_leave_request')
                ->where('np_hrm_tbl_clm_leave_request_id', $reqId)
                ->update($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return false;
            }

            $this->db->transCommit();
            return true;

        } catch (\Throwable $e) {
            $this->db->transRollback();
            return false;
        }
    }

    public function applyForLeave(array $formData): bool|int
    {
        $data = [
            'np_hrm_tbl_clm_user_id'         => $formData['user_id'],
            'np_hrm_tbl_clm_leave_type_id'   => $formData['leave_type'],
            'np_hrm_tbl_clm_start_date'      => $formData['start_date'],
            'np_hrm_tbl_clm_end_date'        => $formData['end_date'],
            'np_hrm_tbl_clm_total_days'      => $formData['total_days'],
            'np_hrm_tbl_clm_reason'          => $formData['reason'],
            'np_hrm_tbl_clm_station_leave'   => $formData['station_leave'],
            'np_hrm_tbl_clm_address_in_leave'=> $formData['address_in_leave'],
            'np_hrm_tbl_clm_approver_id'     => $formData['approver_id'],
            'np_hrm_tbl_clm_alternate_id'    => $formData['alternate_id'],
            'np_hrm_tbl_clm_session_id'      => $formData['session_id'],
            'np_hrm_tbl_clm_submission_time' => time(),
            'np_hrm_tbl_clm_created_at'      => time(),
        ];

        if ($formData['user_current_grade'] > NO_SUPERVISOR_UPTO_GRADE) {
            $data['np_hrm_tbl_clm_supervisor_id'] = $formData['supervisor_id'];
            $data['np_hrm_tbl_clm_status'] = 0;
        } else {
            $data['np_hrm_tbl_clm_status'] = 1;
        }

        $builder = $this->db->table('np_hrm_tbl_leave_request');
        if ($builder->insert($data)) {
            return $this->db->insertID();
        }
        return false;
    }

    /**
     * Fetches all leave balances for a user in a specific year
     * Uses the exact schema: np_hrm_tbl_clm_allocated_days & np_hrm_tbl_clm_taken_days
     */
    public function getUserLeaveBalances($userId, $year): array
    {
        $results = $this->db->table('np_hrm_tbl_leave_balance')
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->where('np_hrm_tbl_clm_year', $year)
            ->get()
            ->getResultArray();

        $balances = [];
        foreach ($results as $row) {
            // Map by the Leave Type ID
            $balances[$row['np_hrm_tbl_clm_leave_type_id']] = $row;
        }
        return $balances;
    }

    public function getApprovalSettings($userId)
    {
        return $this->db->table('np_hrm_tbl_user_approvals')
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function saveApprovalSettings($data)
    {
        return $this->db->table('np_hrm_tbl_user_approvals')->insert($data);
    }

    public function getUserNameById($userId)
    {
        $user = $this->db->table('np_hrm_tbl_user_basic_info')
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->get()
            ->getRowArray();

        return $user ? $user['np_hrm_tbl_clm_username'] : 'N/A';
    }

    /**
     * Fetch full profile and leave balance details for Admin Manual Entry
     */
    public function getAdminUserLeaveProfile($userId, $year)
    {
        $profile = $this->getUserData($userId);
        $balances = $this->getUserLeaveBalances($userId, $year);

        return [
            'profile'  => $profile,
            'balances' => $balances
        ];
    }

    /**
     * Direct insertion for Admin Manual Leave
     */
    /**
     * Direct insertion for Admin Manual Leave with Balance Update
     */
    public function insertManualLeave($data)
    {
        $this->db->transStart();

        // 1. Insert the Leave Request
        $this->db->table('np_hrm_tbl_leave_request')->insert($data);

        // 2. Check if a balance record exists for this year/type before updating
        $check = $this->db->table('np_hrm_tbl_leave_balance')
            ->where('np_hrm_tbl_clm_user_id', $data['np_hrm_tbl_clm_user_id'])
            ->where('np_hrm_tbl_clm_leave_type_id', $data['np_hrm_tbl_clm_leave_type_id'])
            ->where('np_hrm_tbl_clm_year', date('Y'))
            ->get()->getRow();

        if ($check) {
            $this->db->table('np_hrm_tbl_leave_balance')
                ->where('np_hrm_tbl_clm_user_id', $data['np_hrm_tbl_clm_user_id'])
                ->where('np_hrm_tbl_clm_leave_type_id', $data['np_hrm_tbl_clm_leave_type_id'])
                ->where('np_hrm_tbl_clm_year', date('Y'))
                ->set('np_hrm_tbl_clm_taken_days', 'np_hrm_tbl_clm_taken_days + ' . (int)$data['np_hrm_tbl_clm_total_days'], false)
                ->set('np_hrm_tbl_clm_last_updated', time()) // Added last_updated as per your schema
                ->update();
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function getSupervisorApproverMasterList($userId = null)
    {
        $currentYear = date('Y');

        $builder = $this->db->table('np_hrm_tbl_user_basic_info as u')
            ->select('
        u.np_hrm_tbl_clm_user_id as emp_id,
        u.np_hrm_tbl_clm_payroll_id as emp_payroll,
        u.np_hrm_tbl_clm_profile_picture as emp_profile_pic,
        ue.np_hrm_tbl_clm_name_en as emp_name,
        ue.np_hrm_tbl_clm_biometric_serial as emp_bio,
        ue.np_hrm_tbl_clm_designation_npcbl as emp_desig,
        u.np_hrm_tbl_clm_official_email as emp_email,
        (lb.np_hrm_tbl_clm_allocated_days - lb.np_hrm_tbl_clm_taken_days) as remaining_leave,
        lb.np_hrm_tbl_clm_year as leave_year,
        sup_e.np_hrm_tbl_clm_name_en as supervisor_name,
        sup_e.np_hrm_tbl_clm_designation_npcbl as supervisor_desig,
        sup_b.np_hrm_tbl_clm_payroll_id as supervisor_payroll,
        sup_b.np_hrm_tbl_clm_profile_picture as supervisor_profile_picture,
        sup_e.np_hrm_tbl_clm_biometric_serial as supervisor_bio,
        ap.np_hrm_tbl_clm_supervisor_confirmation as emp_confirmation,
        app_e.np_hrm_tbl_clm_name_en as approver_name,
        app_e.np_hrm_tbl_clm_designation_npcbl as approver_desig,
        app_b.np_hrm_tbl_clm_payroll_id as approver_payroll,
        app_b.np_hrm_tbl_clm_profile_picture as approver_profile_picture,
        app_e.np_hrm_tbl_clm_biometric_serial as approver_bio
    ', false)
            ->join('np_hrm_tbl_user_extended_info ue', 'ue.np_hrm_tbl_clm_user_id = u.np_hrm_tbl_clm_user_id')
            ->join('np_hrm_tbl_leave_balance lb', "lb.np_hrm_tbl_clm_user_id = u.np_hrm_tbl_clm_user_id AND lb.np_hrm_tbl_clm_year = '$currentYear'", 'left')
            ->join('np_hrm_tbl_user_approvals ap', 'ap.np_hrm_tbl_clm_user_id = u.np_hrm_tbl_clm_user_id', 'left')
            ->join('np_hrm_tbl_user_basic_info sup_b', 'sup_b.np_hrm_tbl_clm_user_id = ap.np_hrm_tbl_clm_supervisor_id', 'left')
            ->join('np_hrm_tbl_user_extended_info sup_e', 'sup_e.np_hrm_tbl_clm_user_id = sup_b.np_hrm_tbl_clm_user_id', 'left')
            ->join('np_hrm_tbl_user_basic_info app_b', 'app_b.np_hrm_tbl_clm_user_id = ap.np_hrm_tbl_clm_approver_id', 'left')
            ->join('np_hrm_tbl_user_extended_info app_e', 'app_e.np_hrm_tbl_clm_user_id = app_b.np_hrm_tbl_clm_user_id', 'left');

        if ($userId !== null) {
            $builder->groupStart()
                ->where('ap.np_hrm_tbl_clm_supervisor_id', $userId)
                ->orWhere('ap.np_hrm_tbl_clm_approver_id', $userId)
                ->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Update employee approval status or remove record on cancellation.
     *
     * @param int|string $userId
     * @param string $actionFlag ('confirm' or 'cancel')
     * @return bool
     */
    public function updateEmployeeApprovalStatus($userId, string $actionFlag): bool
    {
        $builder = $this->db->table('np_hrm_tbl_user_approvals');

        if ($actionFlag === 'cancel') {
            return $builder->where('np_hrm_tbl_clm_user_id', $userId)->delete();
        }

        if ($actionFlag === 'confirm') {
            $updateData = [
                'np_hrm_tbl_clm_supervisor_confirmation' => 1,
                'np_hrm_tbl_clm_updated_at'              => time()
            ];

            return $builder->where('np_hrm_tbl_clm_user_id', $userId)->update($updateData);
        }

        return false;
    }

    public function login(array $formData): bool|string
    {
        $payrollId = trim($formData['payroll_id'] ?? '');
        $password  = $formData['password'] ?? '';

        $user = $this->db->table('np_hrm_tbl_user_basic_info')
            ->where('np_hrm_tbl_clm_payroll_id', $payrollId)
            ->where('np_hrm_tbl_clm_status', 1)
            ->get()->getRowArray();

        if (!$user || !password_verify($password, $user['np_hrm_tbl_clm_password_hash'])) {
            return 'Invalid Payroll ID or Password.';
        }

        $extended = $this->db->table('np_hrm_tbl_user_extended_info')
            ->where('np_hrm_tbl_clm_user_id', $user['np_hrm_tbl_clm_user_id'])
            ->get()->getRowArray();

        $sessionData = [
            'user_id'    => $user['np_hrm_tbl_clm_user_id'],
            'payroll_id' => $user['np_hrm_tbl_clm_payroll_id'],
            'name_en'    => $extended['np_hrm_tbl_clm_name_en'] ?? 'User',
            'role_id'    => $user['np_hrm_tbl_clm_role_id'],
            'email'      => $user['np_hrm_tbl_clm_official_email']
        ];

        $this->createUserSession($sessionData, $formData);
        return true;
    }

    protected function createUserSession(array $sessionData, array $postData): void
    {
        $session = service('session');
        $sid     = session_id();
        $expiry  = time() + (8 * 3600);

        $agentString = $this->request->getUserAgent()->getAgentString();
        $ip          = $this->request->getIPAddress();
        $timezone    = config('App')->appTimezone;

        $fingerprint = hash_hmac(
            'sha256',
            $agentString . $ip . $timezone,
            config('Encryption')->key // Uses your unique CI4 Encryption Key
        );

        $session->set(array_merge($sessionData, [
            'session_id'         => $sid,
            'session_expiry'     => $expiry,
            'is_logged_in'       => true,
            'user_fingerprint'   => $fingerprint
        ]));

        $this->storeSessionToDatabase($sessionData, $postData, $sid, $expiry);
    }

    private function storeSessionToDatabase(array $sData, array $pData, string $sid, int $exp): void
    {
        $request = service('request');
        $secFields = function_exists('get_npp_security_fields') ? get_npp_security_fields() : [];

        $data = [
            'np_hrm_tbl_clm_payroll_id' => $sData['payroll_id'],
            'np_hrm_tbl_clm_session_id' => $sid,
            'np_hrm_tbl_clm_created_at' => time(),
            'np_hrm_tbl_clm_session_expiry' => $exp,
            'np_hrm_tbl_clm_status' => 'active',
            'np_hrm_tbl_clm_request_ip' => $request->getIPAddress(),
            'np_hrm_tbl_clm_user_agent' => $request->getUserAgent()
        ];

        foreach ($secFields as $field) {
            $data['np_hrm_tbl_clm_' . $field] = $pData[$field] ?? 'N/A';
        }

        $this->db->table('np_hrm_tbl_auth_session')->insert($data);
    }

    public function saveRegistration(array $formData, string $imgUrl): bool|string
    {
        if ($this->checkPayrollIdExists($formData['payroll_id'])) {
            return 'Payroll ID already exists.';
        }

        $this->db->transBegin();
        try {
            $basic = [
                'np_hrm_tbl_clm_username'       => $formData['payroll_id'],
                'np_hrm_tbl_clm_payroll_id'     => $formData['payroll_id'],
                'np_hrm_tbl_clm_official_email' => $formData['official_email'],
                'np_hrm_tbl_clm_personal_email' => $formData['personal_email'],
                'np_hrm_tbl_clm_password_hash'  => password_hash('rnpp246', PASSWORD_DEFAULT),
                'np_hrm_tbl_clm_role_id'        => 3,
                'np_hrm_tbl_clm_profile_picture'=> $imgUrl,
                'np_hrm_tbl_clm_last_access_ip' => $this->getRealIpAddress()
            ];

            $this->db->table('np_hrm_tbl_user_basic_info')->insert($basic);
            $userId = $this->db->insertID();

            $addrIds = [
                'present'   => $this->insertAddress($formData, 'present'),
                'permanent' => $this->insertAddress($formData, 'permanent'),
                'mailing'   => $this->insertAddress($formData, 'mailing')
            ];

            $extended = [
                'np_hrm_tbl_clm_user_id'            => $userId,
                'np_hrm_tbl_clm_name_en'            => $formData['name_en'],
                'np_hrm_tbl_clm_marital_status'     => $formData['marital_status'],
                'np_hrm_tbl_clm_spouse_name'        => $formData['spouse_name'] ?? '',
                'np_hrm_tbl_clm_present_address_id' => $addrIds['present'],
                'np_hrm_tbl_clm_permanent_address_id'=> $addrIds['permanent'],
                'np_hrm_tbl_clm_mailing_address_id' => $addrIds['mailing'],
            ];

            $this->db->table('np_hrm_tbl_user_extended_info')->insert($extended);
            $this->db->transCommit();
            return true;
        } catch (Throwable $e) {
            $this->db->transRollback();
            return $e->getMessage();
        }
    }

    private function insertAddress(array $data, string $type): int
    {
        $prefix = $type . '_';
        $addr = [
            'np_hrm_tbl_clm_address_line1'   => $data[$prefix . 'address_line1'] ?? '',
            'np_hrm_tbl_clm_district'        => $data[$prefix . 'district'] ?? ''
        ];
        $this->db->table('np_hrm_tbl_address')->insert($addr);
        return $this->db->insertID();
    }

    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool|string
    {
        $user = $this->db->table('np_hrm_tbl_user_basic_info')->select('np_hrm_tbl_clm_password_hash')->where('np_hrm_tbl_clm_user_id', $userId)->get()->getRowArray();

        if (!$user || !password_verify($currentPassword, $user['np_hrm_tbl_clm_password_hash'])) {
            return "Current password is incorrect.";
        }

        return $this->db->table('np_hrm_tbl_user_basic_info')->where('np_hrm_tbl_clm_user_id', $userId)->update([
            'np_hrm_tbl_clm_password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'np_hrm_tbl_clm_updated_at'    => time(),
        ]) ? true : "Update failed.";
    }

    public function forgetPassword(array $formData): bool
    {
        $email = $formData['official_email'] ?? '';
        $user = $this->db->table('np_hrm_tbl_user_basic_info')->where('np_hrm_tbl_clm_official_email', $email)->get()->getRow();
        return $user ? true : false;
    }

    private function getRealIpAddress(): string
    {
        return service('request')->getIPAddress();
    }
}