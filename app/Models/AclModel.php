<?php

namespace App\Models;

use App\Models\BaseModel;
use Exception;
use Throwable;

class AclModel extends BaseModel
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get all roles from: np_hrm_tbl_role
     * Matches your UserModel::getRoleTitleById logic
     */
    public function getAllRoles(): array
    {
        return $this->db->table('np_hrm_tbl_role')
            ->select('np_hrm_tbl_clm_role_id, np_hrm_tbl_clm_role_name')
            ->get()
            ->getResultArray();
    }

    public function getRolePermissionsArray(int $roleId): array
    {
        $results = $this->db->table('np_hrm_tbl_acl_permissions')
            ->select('np_hrm_tbl_clm_controller, np_hrm_tbl_clm_action')
            ->where('np_hrm_tbl_clm_role_id', $roleId)
            ->where('np_hrm_tbl_clm_is_allowed', 1)
            ->get()
            ->getResultArray();

        $permissions = [];
        foreach ($results as $row) {
            $permissions[$row['np_hrm_tbl_clm_controller']][] = $row['np_hrm_tbl_clm_action'];
        }

        return $permissions;
    }

    /**
     * Get permissions from: np_hrm_tbl_acl_permissions
     */
    public function getPermissionsByRole(int $roleId): array
    {
        return $this->db->table('np_hrm_tbl_acl_permissions')
            ->where('np_hrm_tbl_clm_role_id', $roleId)
            ->orderBy('np_hrm_tbl_clm_controller', 'ASC')
            ->orderBy('np_hrm_tbl_clm_action', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Update toggle status
     * Securely blocks any updates to Super Admin (Role 1)
     */
    public function updatePermission(int $id, array $data): bool
    {
        $this->db->table('np_hrm_tbl_acl_permissions')
            ->where('np_hrm_tbl_clm_permission_id', $id)
            ->where('np_hrm_tbl_clm_role_id !=', 1) // The critical safety filter
            ->update($data);

        return $this->db->affectedRows() > 0;
    }

    /**
     * Register a new Controller/Action for ALL existing roles
     */
    public function insertGlobalAction(string $controller, string $action): bool
    {
        $roles = $this->getAllRoles();
        $batch = [];
        $timestamp = time();

        foreach ($roles as $role) {
            $roleId = (int)$role['np_hrm_tbl_clm_role_id'];

            $batch[] = [
                'np_hrm_tbl_clm_role_id'    => $roleId,
                'np_hrm_tbl_clm_controller' => $controller,
                'np_hrm_tbl_clm_action'     => $action,
                'np_hrm_tbl_clm_is_allowed' => ($roleId === 1) ? 1 : 0,
                'np_hrm_tbl_clm_created_at' => $timestamp
            ];
        }

        if (!empty($batch)) {
            return $this->db->table('np_hrm_tbl_acl_permissions')
                ->insertBatch($batch);
        }

        return false;
    }

    /**
     * Used by Middleware/Filter to check access
     */
    public function checkAccess(int $roleId, string $controller, string $action): bool
    {
        $builder = $this->db->table('np_hrm_tbl_acl_permissions');
        $builder->where([
            'np_hrm_tbl_clm_role_id'    => $roleId,
            'np_hrm_tbl_clm_controller' => $controller,
            'np_hrm_tbl_clm_action'     => $action,
            'np_hrm_tbl_clm_is_allowed' => 1
        ]);

        return $builder->countAllResults() > 0;
    }
}