<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Applicants extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string|RedirectResponse
    {
        if ($redirect = $this->redirectGeneralUser()) return $redirect;

        return redirect()->to(base_url('applicants/login'));
    }

    public function login(): string|RedirectResponse
    {
        $data = [
            'menu'         => 'applicants',
            'submenu'      => 'login',
            'session_data' => $this->getFullSession(),
        ];

        if (strtolower($this->request->getMethod()) === 'post') {
            $formData = $this->request->getPost();

            $status = $this->userModel->login($formData);

            if ($status === true) {
                return redirect()->to(base_url('applicants/home'));
            }

            $data['error'] = $status;
        }

        return $this->viewLoad('common/login', $data);
    }

    public function home(): string|RedirectResponse
    {
        if ($redirect = $this->redirectGeneralUser()) return $redirect;

        $session = service('session');
        $userId = $session->get('user_id');

        $userInfo = $this->userModel->getUserData($userId);

        return $this->viewLoad('users/personal_info', [
            'menu'      => 'users',
            'submenu'   => 'personal_info',
            'userInfo'  => $userInfo,
        ]);
    }

    public function submitBkash(): RedirectResponse
    {
        if ($redirect = $this->redirectGeneralUser()) return $redirect;

        $session = service('session');
        $userId = $session->get('user_id');

        $userModel = new \App\Models\UserModel();
        $existingUser = $userModel->getUserData($userId);

        // Prevent overwriting if already submitted
        if (!empty($existingUser['bkash_number'])) {
            return redirect()->back()->with('error', 'Your bKash number has already been submitted and cannot be updated.');
        }

        $bkashNumber = trim($this->request->getPost('bkash_number') ?? '');

        if (!preg_match('/^01[3-9]\d{8}$/', $bkashNumber)) {
            return redirect()->back()->with('error', 'Please enter a valid 11-digit bKash Personal Mobile Number.');
        }

        $updated = $userModel->updateBkashNumber($userId, $bkashNumber);

        if ($updated) {
            return redirect()->back()->with('success', 'Your bKash number has been saved and locked successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update bKash number. Please try again.');
        }
    }

    public function logout(): RedirectResponse
    {
        $this->clearSession();
        return redirect()->to(base_url('applicants/login'));
    }
}