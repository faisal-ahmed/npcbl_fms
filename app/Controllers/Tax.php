<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TaxModel;
use CodeIgniter\HTTP\RedirectResponse;

class Tax extends BaseController
{
    protected UserModel $userModel;
    protected TaxModel $taxModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->taxModel = new TaxModel();
    }

    private function checkAuth(): ?RedirectResponse
    {
        return $this->restrictAccess();
    }

    public function index(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $userId = $this->getUserId();

        $data = [
            'menu'         => 'tax',
            'submenu'      => 'index',
            'session_data' => $this->getFullSession(),
            'user_data'    => $this->userModel->getUserData($userId),
            'records'      => $this->taxModel->getTaxRecords($userId),
        ];

        return $this->viewLoad('tax/index', $data);
    }

    public function allRecords(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $allRecords = $this->taxModel->getTaxRecords(null);

        $data = [
            'menu'         => 'tax',
            'submenu'      => 'allTax',
            'session_data' => $this->getFullSession(),
            'user_data'    => $this->userModel->getUserData($this->getUserId()),
            'records'      => $allRecords
        ];

        return $this->viewLoad('tax/index', $data);
    }

    public function exportExcel()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $type = $this->request->getPost('export_type');
        $userId = ($type == 'allTax') ? null : $this->getUserId();
        $records = $this->taxModel->getTaxRecords($userId);

        if (empty($records)) {
            return redirect()->back()->with('error', 'No records found to export.');
        }

        $fileName = "Tax_Records_" . date('Y-m-d_Hi') . ".csv";

        // Standard headers
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: text/csv; charset=utf-8"); // Explicitly set UTF-8
        header("Pragma: no-cache");
        header("Expires: 0");

        // Open file pointer to output
        $file = fopen('php://output', 'w');

        // --- THE CRUCIAL CHANGE: ADDING THE UTF-8 BOM ---
        // This tells Excel to use UTF-8 encoding for Bangla characters
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add Column Headers
        $header = [
            'Sl No.', 'Employee Name', 'Payroll ID', 'Designation',
            'Contact', 'Tax Year', 'TIN Number', 'Submission Date',
            'Circle/Zone', 'Serial No', 'Comments', 'Status'
        ];
        fputcsv($file, $header);

        // Add Data Rows
        $i = 1;
        foreach ($records as $row) {
            fputcsv($file, [
                $i++,
                $row['np_hrm_tbl_clm_name_en'],

                // Format Payroll ID as Text
                '="' . $row['np_hrm_tbl_clm_payroll_id'] . '"',

                $row['np_hrm_tbl_clm_designation_npcbl'],

                // Format Contact Number as Text
                '="' . $row['np_hrm_tbl_clm_contact_number'] . '"',

                $row['np_hrm_tbl_clm_tax_year'],

                // Format TIN Number as Text
                '="' . $row['np_hrm_tbl_clm_tax_tin'] . '"',

                $row['np_hrm_tbl_clm_tax_return_submission_date'],
                $row['np_hrm_tbl_clm_tax_circle'],

                // Format Serial No as Text
                '="' . $row['np_hrm_tbl_clm_tax_return_serial_no'] . '"',

                strip_tags($row['np_hrm_tbl_clm_tax_comments'] ?? ''),
                'Submitted'
            ]);
        }

        fclose($file);
        exit;
    }

    public function addRecord(): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $data = [
            'menu'    => 'tax',
            'submenu' => 'addTax',
            'error'   => null
        ];

        if ($this->request->is('post')) {
            $insertData = [
                'np_hrm_tbl_clm_user_id'                    => $this->getUserId(),
                'np_hrm_tbl_clm_tax_tin'                    => $this->request->getPost('tin'),
                'np_hrm_tbl_clm_tax_circle'                 => $this->request->getPost('circle'),
                'np_hrm_tbl_clm_tax_year'                   => $this->request->getPost('tax_year'),
                'np_hrm_tbl_clm_tax_return_submission_date' => $this->request->getPost('submission_date'),
                'np_hrm_tbl_clm_tax_return_serial_no'       => $this->request->getPost('serial_no'),
                'np_hrm_tbl_clm_tax_comments'               => $this->request->getPost('comments'),
                'np_hrm_tbl_clm_tax_created_at'             => time(),
                'np_hrm_tbl_clm_tax_created_by'             => $this->getUserId()
            ];

            $result = $this->taxModel->saveTaxRecord($insertData);

            if ($result === true) {
                return redirect()->to(base_url('tax/add-tax-return'))->with('success', 'Tax info saved.');
            }
            $data['error'] = $result;
        }

        return $this->viewLoad('tax/addRecord', $data);
    }

    public function updateRecord(int $taxId): string|RedirectResponse
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $taxRecord = $this->taxModel->getTaxById($taxId);

        if (empty($taxRecord) || $taxRecord['np_hrm_tbl_clm_user_id'] != $this->getUserId()) {
            return redirect()->to(base_url('tax/my-tax'))->with('error', 'Unauthorized access or record not found.');
        }

        if ($this->request->is('post')) {
            $updateData = [
                'np_hrm_tbl_clm_tax_tin'                    => $this->request->getPost('tin'),
                'np_hrm_tbl_clm_tax_year'                   => $this->request->getPost('tax_year'),
                'np_hrm_tbl_clm_tax_return_submission_date' => $this->request->getPost('submission_date'),
                'np_hrm_tbl_clm_tax_circle'                 => $this->request->getPost('circle'),
                'np_hrm_tbl_clm_tax_return_serial_no'       => $this->request->getPost('serial_no'),
                'np_hrm_tbl_clm_tax_comments'               => $this->request->getPost('comments'),
                'np_hrm_tbl_clm_tax_updated_at'             => time(),
            ];

            $result = $this->taxModel->updateTaxRecord($taxId, $updateData);

            if ($result === true) {
                return redirect()->to(base_url('tax/my-tax'))->with('success', 'Tax record updated successfully.');
            } else {
                $data['error'] = $result;
            }
        }

        $data = [
            'menu'    => 'tax',
            'submenu' => 'updateTax',
            'tax'     => $taxRecord,
            'title'   => 'Update Tax Return'
        ];

        return $this->viewLoad('tax/updateTax', $data);
    }
}