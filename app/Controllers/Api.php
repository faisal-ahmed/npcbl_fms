<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Api extends BaseController
{
    public function index(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode(200)
            ->setJSON([
                'status'  => 'error',
                'message' => 'You are not authorized to view the resources.'
            ]);
    }

    public function nppPersonnelVerification(): string
    {
        $urlData = $this->request->getGet();
        $userModel = new UserModel();
        $verify_user = $userModel->getUserData(0, $urlData['id']);
        $data = [
            'menu' => 'home',
            'userData' => $verify_user,
            'submenu' => 'index'
        ];

        return $this->viewLoad('api/personnel_verification', $data);
    }
}
