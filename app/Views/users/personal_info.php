<section id="personal-info" style="padding-top:30px; padding-bottom:50px;">
    <div class="container content-background">
        <div class="row">
            <div class="col-sm-12 col-sm-offset-1">
                <h2 class="text-center">Applicant Profile & Fee Refund Information</h2>
                <hr/>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?php echo session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($userInfo)) : ?>
                    <?php $hasSubmitted = !empty($userInfo['bkash_number']); ?>

                    <!-- Applicant Details Card -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Application Details</strong></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped" style="font-size: 1.1em; margin-bottom: 0;">
                                <tr>
                                    <th style="width: 20%;">User ID</th>
                                    <td><strong><?php echo esc($userInfo['user_id']); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Post Name</th>
                                    <td><?php echo esc($userInfo['post_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Applicant's Name</th>
                                    <td><?php echo esc($userInfo['person_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Father's Name</th>
                                    <td><?php echo esc($userInfo['father_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Mother's Name</th>
                                    <td><?php echo esc($userInfo['mother_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td><?php echo esc($userInfo['person_dob']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr/>
                    <!-- bKash Refund Form Card -->
                    <div class="panel panel-primary" style="margin-top: 25px;">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Application Fee Refund Request (bKash)</strong></h3>
                        </div>
                        <div class="panel-body">
                            <form action="<?php echo base_url('applicants/submitBkash'); ?>" method="post" class="form-horizontal" <?php echo !$hasSubmitted ? 'onsubmit="return confirmSubmission()"' : ''; ?>>
                                <?php echo csrf_field(); ?>

                                <?php if ($hasSubmitted): ?>
                                    <div class="alert alert-warning text-bold-500 text-center">
                                        <i class="glyphicon glyphicon-lock"></i> <strong>Note:</strong> Your bKash number has been submitted and locked. Once submitted, it cannot be modified.
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning text-bold-500 text-center font-large-1">
                                        <i class="glyphicon glyphicon-info-sign"></i> Please enter an active <strong>bKash Personal Mobile Number</strong>. The application fee will be remitted to this account. <strong class="text-danger">Warning: Double-check your number before submitting; it cannot be changed later.</strong>
                                    </div>
                                <?php endif; ?>

                                <!-- Forced Flexbox Container for Inline Row Layout -->
                                <div class="form-group flex-row-container" style="margin-top: 20px;">
                                    <label for="bkash_number" class="col-sm-3 control-label text-left-sm" style="padding-top: 8px;">
                                        bKash Account Number <span class="text-danger">*</span>
                                    </label>

                                    <!-- Input Box Column -->
                                    <div class="col-sm-4">
                                        <input
                                                type="text"
                                                class="form-control input-lg"
                                                id="bkash_number"
                                                name="bkash_number"
                                                placeholder="bKash personal number"
                                                value="<?php echo esc($userInfo['bkash_number'] ?? ''); ?>"
                                                maxlength="11"
                                                pattern="01[3-9][0-9]{8}"
                                                title="11-digit Bangladeshi mobile number starting with 01"
                                                required
                                            <?php echo $hasSubmitted ? 'readonly style="background-color: #eef1f5; cursor: not-allowed;"' : ''; ?>
                                        >
                                    </div>

                                    <!-- Right-Side Status / Button Column -->
                                    <div class="col-sm-5">
                                        <?php if ($hasSubmitted): ?>
                                            <div style="background-color: #f8f9fa; border: 1px solid #dcdcdc; padding: 6px 12px; border-radius: 4px; text-align: center; height: 46px; display: flex; flex-direction: column; justify-content: center;">
                                                <div style="color: #27ae60; font-weight: bold; font-size: 0.9em; line-height: 1.2;">
                                                    <i class="glyphicon glyphicon-ok-sign"></i> Submitted on
                                                </div>
                                                <?php if (!empty($userInfo['submission_date'])): ?>
                                                    <div style="font-size: 0.8em; color: #555; margin-top: 2px;">
                                                        <strong>Date:</strong>
                                                        <?php
                                                        $subDate = is_numeric($userInfo['submission_date'])
                                                            ? date('d-M-Y h:i A', (int)$userInfo['submission_date'])
                                                            : date('d-M-Y h:i A', strtotime($userInfo['submission_date']));
                                                        echo esc($subDate);
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                                Submit Number
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php else : ?>
                    <div class="alert alert-warning text-center">
                        No applicant record found for this user.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function confirmSubmission() {
        var bkashNumber = document.getElementById('bkash_number').value;
        return confirm("Please confirm your bKash Number: " + bkashNumber + "\n\nAre you sure this number is correct? Once submitted, it CANNOT be changed.");
    }
</script>

<style>
    .content-background {
        background: #ffffff;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.08);
    }
    td, th {
        border-bottom: 1px solid #e2e2e2 !important;
        vertical-align: middle !important;
    }

    /* Force Flexbox Row Layout for screen sizes tablet and up */
    @media (min-width: 768px) {
        .flex-row-container {
            display: flex !important;
            align-items: center !important;
        }
        .flex-row-container:before,
        .flex-row-container:after {
            display: none !important; /* Disables Bootstrap float pseudo-elements */
        }
    }
</style>