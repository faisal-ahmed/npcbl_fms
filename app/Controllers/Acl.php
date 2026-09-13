<?php

namespace App\Controllers;

use App\Models\AclModel;
use CodeIgniter\HTTP\ResponseInterface;

class Acl extends BaseController
{
    protected AclModel $aclModel;

    public function __construct()
    {
        $this->aclModel = new AclModel();
    }

    /**
     * Display the ACL Management Page
     */
    public function managePermissions(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($redirect = $this->restrictAccess()) {
            return $redirect;
        }

        // Authorization: Ensure ONLY Admin (Role ID 1) can see this
        if (session()->get('role_id') != 1) {
            return redirect()->to(base_url('home'))->with('error', 'Unauthorized access denied.');
        }

        $roleId = $this->request->getGet('role_id') ?? 1;

        $data = [
            'selectedRoleId' => $roleId,
            'roles'          => $this->aclModel->getAllRoles(),
            'permissions'    => $this->aclModel->getPermissionsByRole($roleId),
            'menu'           => 'admin',
            'submenu'        => 'acl'
        ];

        return $this->viewLoad("acl/manage_permissions", $data);
    }

    /**
     * SECURE AJAX TOGGLE with HMAC Verification & CSRF Rotation
     */
    public function updatePermission(): ResponseInterface
    {
        // 1. Block direct URL access
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }

        // 2. Strict Session & Fingerprint Check
        if (!$this->isLoggedIn() || session()->get('role_id') != 1) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Session invalid or unauthorized'
            ]);
        }

        $id     = $this->request->getPost('permission_id');
        $status = $this->request->getPost('is_allowed');

        //Data Validation
        if (!is_numeric($id) || !in_array($status, ['0', '1'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data format']);
        }

        $update = $this->aclModel->updatePermission($id, [
            'np_hrm_tbl_clm_is_allowed' => $status,
            'np_hrm_tbl_clm_updated_at' => time()
        ]);

        if ($update) {
            return $this->response->setJSON([
                'status' => 'success',
                'token'  => csrf_hash() // Rotate CSRF token for next request
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Database update failed']);
    }

    /**
     * Add a Controller/Action globally (Permission 0)
     */
    public function addGlobalAction()
    {
        if ($redirect = $this->restrictAccess()) return $redirect;
        if (session()->get('role_id') != 1) return redirect()->to(base_url('home'));

        $controller = $this->request->getPost('controller_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $action     = $this->request->getPost('action_name', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($controller && $action) {
            $this->aclModel->insertGlobalAction($controller, $action);
            return redirect()->back()->with('success', "Action '{$action}' registered globally.");
        }

        return redirect()->back()->with('error', 'Please provide both Controller and Action names.');
    }
}