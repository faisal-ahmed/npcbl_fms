<section id="tax-update-view" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 3% 13% !important;">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="center" style="padding-bottom: 20px;">
                    <h2 class="text-center">Update Tax Return Information</h2>
                    <hr/>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="status alert alert-success" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                            <?php echo session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error) && $error) : ?>
                        <div class="status alert alert-danger" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                            <?php echo is_array($error) ? implode('<br>', $error) : $error; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card border-info shadow">
                    <div class="card-header bg-info text-white h4">
                        <strong><i class="fas fa-edit"></i> Edit Details for TIN: <?php echo esc($tax['np_hrm_tbl_clm_tax_tin']); ?></strong>
                    </div>
                    <div class="card-body">

                        <?php if ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0): ?>
                            <div class="alert alert-warning border-warning mb-2" style="border: 2px solid #FF9149 !important; text-align: center; font-size: 1.2em; font-weight: bold;">
                                <i class="fas fa-exclamation-triangle"></i> <strong>Note:</strong> This record has already been accepted/processed by the Accounts department and can no longer be modified.
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo base_url('tax/update-tax-record/' . $tax['np_hrm_tbl_clm_tax_id']); ?>" method="post">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tin"><strong>12-Digit TIN Number</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="tin" id="tin" class="form-control" required maxlength="15" value="<?php echo old('tin', $tax['np_hrm_tbl_clm_tax_tin']); ?>" <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'readonly' : ''; ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_year"><strong>Assessment Year</strong> <span class="text-danger">*</span></label>
                                        <select name="tax_year" id="tax_year" class="form-control" required <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'disabled' : ''; ?>>
                                            <?php
                                            $currentYear = date('Y');
                                            $savedYear = old('tax_year', $tax['np_hrm_tbl_clm_tax_year']);
                                            for($y = $currentYear; $y >= $currentYear - 5; $y--):
                                                $yearRange = ($y) . "-" . ($y + 1);
                                                ?>
                                                <option value="<?php echo $yearRange; ?>" <?php echo ($savedYear == $yearRange) ? 'selected' : ''; ?>>
                                                    <?php echo $yearRange; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="submission_date"><strong>Submission Date</strong> <span class="text-danger">*</span></label>
                                        <input type="date" name="submission_date" id="submission_date" class="form-control" required value="<?php echo old('submission_date', $tax['np_hrm_tbl_clm_tax_return_submission_date']); ?>" <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'readonly' : ''; ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="circle"><strong>Tax Circle / Zone</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="circle" id="circle" class="form-control" required value="<?php echo old('circle', $tax['np_hrm_tbl_clm_tax_circle']); ?>" <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'readonly' : ''; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="serial_no"><strong>Return Acknowledgment Serial No.</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="serial_no" id="serial_no" class="form-control" required value="<?php echo old('serial_no', $tax['np_hrm_tbl_clm_tax_return_serial_no']); ?>" <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'readonly' : ''; ?>>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comments"><strong>Additional Comments / Remarks</strong></label>
                                        <textarea id="comments" name="comments" class="form-control" rows="4" <?php echo ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0) ? 'readonly' : ''; ?>><?php echo old('comments', $tax['np_hrm_tbl_clm_tax_comments']); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr/>
                            <div class="form-group text-right">
                                <a href="<?php echo base_url('tax/my-tax'); ?>" class="btn btn-warning btn-lg">Cancel</a>

                                <?php
                                $isAccepted = ($tax['np_hrm_tbl_clm_tax_record_accepted_by_accounts'] != 0);
                                ?>
                                <button type="submit"
                                        class="btn btn-success btn-lg"
                                    <?php if ($isAccepted): ?>
                                        disabled
                                        title="Cannot update after accounts acceptance"
                                        style="cursor: not-allowed; opacity: 0.6;"
                                    <?php endif; ?>>
                                    <i class="fas fa-upload"></i> Update Tax Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .card { border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .card-header { border-top-left-radius: 10px !important; border-top-right-radius: 10px !important; }
    .form-group label { color: #34495e; }
    .btn-info { background-color: #00bcd4; border: none; }
    .btn-info:hover { background-color: #00acc1; }
</style>