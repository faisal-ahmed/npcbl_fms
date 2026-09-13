<?php

namespace App\Models;

use App\Models\BaseModel;
use CodeIgniter\Database\BaseConnection;
use Exception;

class UserModel extends BaseModel
{
    /**
     * @var BaseConnection
     */
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Get Single User Data
     */
    public function getUserData($user_id = 0): array
    {
        $builder = $this->db->table('np_job_apply_refund r');
        $builder->select([
            'r.np_job_user_id AS user_id',
            'r.np_job_person_name AS person_name',
            'r.np_job_person_dob AS person_dob',
            'r.np_job_post_name AS post_name',
            'r.np_job_father_name AS father_name',
            'r.np_job_mother_name AS mother_name',
            'r.np_job_person_bkash_number AS bkash_number',
            'r.np_job_submission_date AS submission_date',
        ]);

        if ($user_id !== 0) {
            $builder->where('r.np_job_user_id', $user_id);
            return $builder->get()->getRowArray() ?? [];
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Update bKash Number for Refund
     */
    public function updateBkashNumber(string $userId, string $bkashNumber): bool
    {
        return $this->db->table('np_job_apply_refund')
            ->where('np_job_user_id', $userId)
            ->update([
                'np_job_person_bkash_number' => $bkashNumber,
                'np_job_submission_date'     => date('Y-m-d H:i:s') // or time() depending on your column type
            ]);
    }

    /**
     * Login logic
     */
    public function login(): bool|string
    {
        $request = service('request');
        $user_id = trim($request->getPost('user_id') ?? '');
        $password = $request->getPost('password') ?? '';

        if (!$user_id || !$password) {
            return 'User ID or Password is required.';
        }

        $user = $this->db->table('np_job_apply_refund r')
            ->select('r.*')
            ->where('r.np_job_user_id', $user_id)
            ->get()
            ->getRowArray();

        if (!$user || $user['np_job_password'] != $password) {
            return 'User ID or Password is incorrect.';
        }

        $sessionData = [
            'user_id'  => $user['np_job_user_id'],
            'username' => $user['np_job_person_name'],
            'name_en'  => $user['np_job_person_name'] ?? '',
        ];

        $this->createUserSession($sessionData, $request->getPost());
        return true;
    }

    protected function createUserSession(array $sessionData, array $postData): void
    {
        $session = service('session');
        $now = time();
        $expiry = $now + (3600 * 8);
        $sessionId = session_id() ?: bin2hex(random_bytes(16));

        $session->set(array_merge($sessionData, [
            'session_id'     => $sessionId,
            'session_expiry' => $expiry,
            'status'         => 'active'
        ]));
    }
}