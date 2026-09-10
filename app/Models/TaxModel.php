<?php

namespace App\Models;

use App\Models\BaseModel;
use Throwable;

class TaxModel extends BaseModel
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Fetch tax records with full user context
     */
    public function getTaxRecords(?int $userId = null): array
    {
        $builder = $this->db->table('np_hrm_tbl_tax t');
        $builder->select('
            t.*, 
            u.np_hrm_tbl_clm_payroll_id, 
            ex.np_hrm_tbl_clm_name_en, 
            ex.np_hrm_tbl_clm_designation_npcbl,
            ex.np_hrm_tbl_clm_contact_number
        ');
        $builder->join('np_hrm_tbl_user_basic_info u', 'u.np_hrm_tbl_clm_user_id = t.np_hrm_tbl_clm_user_id', 'left');
        $builder->join('np_hrm_tbl_user_extended_info ex', 'ex.np_hrm_tbl_clm_user_id = u.np_hrm_tbl_clm_user_id', 'left');

        if ($userId !== null) {
            $builder->where('t.np_hrm_tbl_clm_user_id', $userId);
        }

        return $builder->orderBy('t.np_hrm_tbl_clm_tax_year', 'DESC')->get()->getResultArray();
    }

    /**
     * Save tax record using the Transactional pattern from your UserModel
     */
    public function saveTaxRecord(array $data): bool|string
    {
        $this->db->transBegin();
        try {
            $this->db->table('np_hrm_tbl_tax')->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return "Database insertion failed.";
            }

            $this->db->transCommit();
            return true;
        } catch (Throwable $e) {
            $this->db->transRollback();
            return $e->getMessage();
        }
    }

    public function updateTaxRecord(int $id, array $data): bool|string
    {
        $this->db->transBegin();
        try {
            $this->db->table('np_hrm_tbl_tax')
                ->where('np_hrm_tbl_clm_tax_id', $id)
                ->update($data);

            if ($this->db->transStatus() === false) {
                $error = $this->db->error();
                $this->db->transRollback();
                return "Database Error: " . $error['message'];
            }

            $this->db->transCommit();
            return true;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return $e->getMessage();
        }
    }

    public function getTaxById(int $taxId): array
    {
        return $this->db->table('np_hrm_tbl_tax')
            ->where('np_hrm_tbl_clm_tax_id', $taxId)
            ->get()->getRowArray() ?? [];
    }
}