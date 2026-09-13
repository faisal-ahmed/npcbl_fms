<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Employee extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Checks if user is already logged in and redirects to home if true.
     */
    private function checkAuthRedirect(): ?RedirectResponse
    {
        return $this->redirectLoggedInUser();
    }

    public function index(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuthRedirect()) return $redirect;

        return redirect()->to(base_url('employee/login'));
    }

    public function login(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuthRedirect()) return $redirect;

        $data = [
            'menu'         => 'hrm',
            'submenu'      => 'login',
            'session_data' => $this->getFullSession(),
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

    public function logout(): RedirectResponse
    {
        $this->clearSession();
        return redirect()->to(base_url('employee/login'));
    }
}