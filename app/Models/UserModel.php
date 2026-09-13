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

    public function getUserNameById($userId)
    {
        $user = $this->db->table('np_hrm_tbl_user_basic_info')
            ->where('np_hrm_tbl_clm_user_id', $userId)
            ->get()
            ->getRowArray();

        return $user ? $user['np_hrm_tbl_clm_username'] : 'N/A';
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
    }
}