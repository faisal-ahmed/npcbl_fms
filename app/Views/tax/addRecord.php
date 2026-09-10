<section id="tax-add-view" style="padding: 50px 0;">
    <div class="container">
        <div class="center" style="padding-bottom: 0;">
            <h2 class="text-center">Submit New Tax Return Information</h2>
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

        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="card border-success shadow-sm">
                    <div class="card-header bg-success text-white h4">
                        <strong><i class="fas fa-file-invoice-dollar"></i> Tax Return Details</strong>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo base_url('tax/add-tax-return'); ?>" method="post">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tin"><strong>TIN Number</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="tin" id="tin" class="form-control" required maxlength="20" value="<?php echo old('tin'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_year"><strong>Assessment Year</strong> <span class="text-danger">*</span></label>
                                        <select name="tax_year" id="tax_year" class="form-control" required>
                                            <option value="">Select Year</option>
                                            <?php
                                            $currentYear = date('Y');
                                            for($y = $currentYear; $y >= $currentYear - 5; $y--):
                                                $yearRange = ($y) . "-" . ($y + 1);
                                                ?>
                                                <option value="<?php echo $yearRange; ?>" <?php echo old('tax_year') == $yearRange ? 'selected' : ''; ?>>
                                                    <?php echo $yearRange; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="submission_date"><strong>Submission Date</strong> <span class="text-danger">*</span></label>
                                        <input type="date" name="submission_date" id="submission_date" class="form-control" required value="<?php echo old('submission_date', date('Y-m-d')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="circle"><strong>Tax Circle / Zone</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="circle" id="circle" class="form-control" placeholder="e.g. Circle-11, Zone-02" required value="<?php echo old('circle'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="serial_no"><strong>Return Acknowledgment Serial No.</strong> <span class="text-danger">*</span></label>
                                        <input type="text" name="serial_no" id="serial_no" class="form-control" placeholder="Enter Serial Number from Acknowledgment Receipt" required value="<?php echo old('serial_no'); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comments"><strong>Additional Comments / Remarks</strong></label>
                                        <textarea id="comments" name="comments" class="form-control" rows="5" placeholder="Enter any additional details here..."><?php echo old('comments'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr/>
                            <div class="form-group text-right">
                                <a href="<?php echo base_url('tax/my-tax'); ?>" class="btn btn-warning btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Submit Tax Record
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
    /* Clean up Card UI */
    .card { border-radius: 8px; overflow: hidden; }
    .card-header { font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

    /* Ensure default textarea looks clean */
    .form-control:focus {
        border-color: #007aa7;
        box-shadow: 0 0 0 0.2rem rgba(0, 122, 167, 0.25);
    }

    label { margin-bottom: 5px; color: #333; }

    /* Center text utility */
    .center { text-align: center; }
</style>