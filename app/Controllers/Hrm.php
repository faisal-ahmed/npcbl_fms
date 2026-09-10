<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Hrm extends BaseController
{
    protected UserModel $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * Checks if user is already logged in and redirects to home if true.
     */
    private function checkAuthRedirect(): ?RedirectResponse
    {
        return $this->redirectLoggedInUser();
    }

    public function index(): RedirectResponse
    {
        if ($redirect = $this->checkAuthRedirect()) return $redirect;

        return redirect()->to(base_url('hrm/login'));
    }

    public function login(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuthRedirect()) return $redirect;

        $data = [
            'menu'         => 'hrm',
            'submenu'      => 'login',
            'session_data' => $this->getFullSession(),
            'jsFields'     => function_exists('get_npp_security_fields') ? get_npp_security_fields() : [],
            'notification' => 'Please enter your Payroll ID and Password to login.',
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $formData = $this->request->getPost();

            $status = $this->userModel->login($formData);

            if ($status === true) {
                return redirect()->to(base_url('home'));
            }

            $data['error'] = $status;
            unset($data['notification']);
        }

        return $this->viewLoad('common/login', $data);
    }

    public function forgetPassword(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuthRedirect()) return $redirect;

        $data = [
            'menu'         => 'hrm',
            'submenu'      => 'login',
            'jsFields'     => function_exists('get_npp_security_fields') ? get_npp_security_fields() : [],
            'notification' => 'Please enter your registered official email address to reset your password.',
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $formData = $this->request->getPost();

            if ($this->userModel->forgetPassword($formData)) {
                $data['success'] = 'An email has been sent to your email address. Please follow the instruction in your email to gain access to your account again.';
                unset($data['notification']);
            } else {
                $data['error'] = 'There is no account matching with that email. Please enter correct email.';
                unset($data['notification']);
            }
        }

        return $this->viewLoad('common/forget_password', $data);
    }
}