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
        $userDetails = $this->userModel->getUserData($userId);

        if (empty($userDetails)) {
            return redirect()->to(base_url('home'))->with('error', 'Profile data not found.');
        }

        $data = [
            'menu'         => 'home',
            'submenu'      => 'personalInfo',
            'session_data' => $this->getFullSession(),
            'user'         => $userDetails, // Access fields via $user['Name_English'], etc.
        ];

        return $this->viewLoad('users/personal_info', $data);
    }
}