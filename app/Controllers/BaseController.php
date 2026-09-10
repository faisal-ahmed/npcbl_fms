<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AclModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use Config\Services;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $session;
    protected $db;
    protected $helpers = ['mail', 'url', 'form'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        $this->session = Services::session();
        $dbGroup = (str_contains($_SERVER['SERVER_NAME'] ?? 'localhost', 'localhost')) ? 'default' : 'live';
        $this->db = \Config\Database::connect($dbGroup);
    }

    protected function viewLoad(string $view = null, array $data = []): string
    {
        $userModel = new UserModel();
        $aclModel  = new AclModel();

        $data['user_role']    = $this->getUserRole();
        $data['loggedIn']     = $this->isLoggedIn() ? 'true' : 'false';
        $data['session_data'] = $this->getFullSession();

        if ($data['loggedIn'] == 'true') {
            $data['user_data'] = $userModel->getUserData($this->getUserId());
            $data['user_permissions'] = $aclModel->getRolePermissionsArray((int)$data['user_role']) ?? [];
        } else {
            $data['user_permissions'] = [];
        }

        $output = view('common/header', $data);
        $output .= view('common/nav_top_banner', $data);

        if ($data['loggedIn'] == 'true') {
            $output .= view('common/nav_left_menu', $data);
        }

        if (!is_null($view)) {
            $output .= view($view, $data);
        }

        $output .= view('common/footer', $data);

        return $output;
    }

    protected function isLoggedIn(): bool
    {
        $userId = $this->session->get('user_id');
        $expiry = $this->session->get('session_expiry');
        $storedFingerprint = $this->session->get('user_fingerprint');

        if (!$userId || !$expiry || (time() > $expiry)) {
            return false;
        }

        $currentFingerprint = hash_hmac(
            'sha256',
            $this->request->getUserAgent()->getAgentString() .
            $this->request->getIPAddress() .
            config('App')->appTimezone,
            config('Encryption')->key
        );

        if (!hash_equals($storedFingerprint, $currentFingerprint)) {
            return false;
        }

        return true;
    }

    protected function redirectGeneralUser(): ?RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            $this->clearSession();
            return redirect()->to(base_url('hrm/login'));
        }
        return null;
    }

    /**
     * Authentication & Authorization Combined Check
     * Best called at the start of restricted controller methods.
     */
    protected function restrictAccess(): ?RedirectResponse
    {
        if (!$this->isLoggedIn()) {
            $this->clearSession();
            return redirect()->to(base_url('hrm/login'));
        }

        if (!$this->hasPermission()) {
            return redirect()->to(base_url('home'))->with('error', 'You do not have permission to view this resource.');
        }

        return null;
    }

    /**
     * Internal logic to check ACL
     */
    private function hasPermission(): bool
    {
        $uri = service('uri');

        $roleId     = (int)$this->getUserRole();
        $controller = $uri->getSegment(1);
        $method = (!empty($uri->getSegment(2))) ? $uri->getSegment(2) : 'index';

        $aclModel = new AclModel();
        return $aclModel->checkAccess($roleId, $controller, $method);
    }

    protected function getSessionID(): string
    {
        return $this->session->session_id;
    }

    protected function redirectLoggedInUser(): ?RedirectResponse
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('home'));
        }
        return null;
    }

    protected function clearSession(): void
    {
        if ($this->session->get('user_id')) {
            $this->session->destroy();
        }
    }

    protected function getFullSession(): array
    {
        $roleId = (int)($this->getUserRole() ?? 0);
        return [
            'user_id'        => $this->session->get('user_id'),
            'username'       => $this->session->get('username'),
            'payroll_id'     => $this->session->get('payroll_id'),
            'name_en'        => $this->session->get('name_en'),
            'name_bn'        => $this->session->get('name_bn'),
            'email'          => $this->session->get('email'),
            'role_id'        => $roleId,
            'role_title'     => $this->getRoleTitle($roleId),
            'session_id'     => $this->session->get('session_id'),
            'session_expiry' => $this->session->get('session_expiry'),
            'status'         => $this->session->get('status')
        ];
    }

    protected function getSessionAttr(string $attr): mixed
    {
        return $this->session->get($attr);
    }

    protected function getUserRole(): mixed
    {
        return $this->session->get('role_id');
    }

    protected function getUserId(): mixed
    {
        return $this->session->get('user_id');
    }

    protected function getRoleTitle(int $roleId): string
    {
        static $roleNames = [];

        if (isset($roleNames[$roleId])) {
            return $roleNames[$roleId];
        }

        $userModel = new UserModel();
        $title = $userModel->getRoleTitleById($roleId);
        $roleNames[$roleId] = $title;

        return $title;
    }

    protected function debug(mixed $array): void
    {
        echo "<pre style='background:#222; color:#0f0; padding:15px; border-radius:5px;'>";
        print_r($array);
        echo "</pre>";
    }

    protected function getValidationRulesForRegistration(): array
    {
        return [
            'name_en' => 'required|min_length[3]|max_length[100]',
            'name_bn' => 'required|min_length[3]|max_length[100]',
            'designation_npcbl' => 'required|min_length[3]|max_length[100]',
            'designation_crnpp' => 'required|min_length[3]|max_length[100]',
            'designation_rnpp' => 'required|min_length[3]|max_length[100]',
            'joining_department' => 'required|min_length[3]|max_length[100]',
            'shop_rnpp' => 'required|min_length[3]|max_length[100]',
            'division_rnpp' => 'required|min_length[3]|max_length[100]',
            'nid' => 'required|min_length[10]|max_length[18]',
            'training_group_no' => 'required|min_length[1]|max_length[18]',
            'training_group_name' => 'required|min_length[3]|max_length[250]',
            'training_duration' => 'required|min_length[1]|max_length[100]',
            'birth_district' => 'required|min_length[3]|max_length[100]',
            'joining_date' => 'required|valid_date',
            'dob' => 'required|valid_date',
            'gender' => 'required|in_list[Male,Female]',
            'religion' => 'required|in_list[Islam,Hinduism,Christianity,Buddhism,Other]',
            'blood_group' => 'required|in_list[A+,A-,B+,B-,O+,O-,AB+,AB-]',
            'marital_status' => 'required|in_list[Single,Married,Divorced,Widowed]',
            'father_name' => 'required|min_length[3]|max_length[100]',
            'father_mobile' => 'required|min_length[3]|max_length[18]',
            'mother_name' => 'required|min_length[3]|max_length[100]',
            'mother_mobile' => 'required|min_length[3]|max_length[18]',
            'emergency_contact_name' => 'required|min_length[3]|max_length[100]',
            'emergency_relation' => 'required|min_length[3]|max_length[100]',
            'emergency_contact_mobile' => 'required|min_length[8]|max_length[18]',
            'personal_contact' => 'required|numeric|min_length[10]|max_length[15]',
            'official_email' => 'required|valid_email',
            'personal_email' => 'permit_empty|valid_email',
            'payroll_id' => 'required|min_length[4]|max_length[50]',
            'biometric_serial' => 'required',
            'gate_pass' => 'required',
            'spouse_name' => 'permit_empty',
            'spouse_mobile' => 'permit_empty',
            'present_address_line1' => 'required|max_length[255]',
            'present_post_office' => 'required|max_length[100]',
            'present_police_station' => 'required|max_length[100]',
            'present_district' => 'required|max_length[100]',
            'permanent_address_line1' => 'required|max_length[255]',
            'permanent_post_office' => 'required|max_length[100]',
            'permanent_police_station' => 'required|max_length[100]',
            'permanent_district' => 'required|max_length[100]',
            'mailing_address_line1' => 'required|max_length[255]',
            'mailing_post_office' => 'required|max_length[100]',
            'mailing_police_station' => 'required|max_length[100]',
            'mailing_district' => 'required|max_length[100]',
            'official_picture' => [
                'rules' => 'uploaded[official_picture]|max_size[official_picture,2048]|is_image[official_picture]|mime_in[official_picture,image/png,image/jpeg,image/jpg]',
                'label' => 'Official Profile Picture'
            ]
        ];
    }
}