<section id="contact-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="text-center">
                    <i class="fas fa-user-shield text-primary"></i>
                    <?php echo $existing_config ? 'Your Leave Approvers' : 'Set Leave Approvers'; ?>
                </h2>
            </div>
        </div>

        <hr/>

        <div class="content-background">
            <div class="center">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="status alert alert-success" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <?php echo session()->getFlashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="status alert alert-danger" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <?php echo session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if (!$existing_config && $step == 1) : ?>
                    <h3 class="text-danger status alert alert-danger" style="border: 2px solid #c52d2f; text-align: center">
                        <strong>Instruction:</strong> Please select your supervisor and manager carefully. Once set, these cannot be changed.
                    </h3>
                <?php endif; ?>

                <?php if ($step == 2) : ?>
                    <div class="status alert alert-warning" style="border: 2px solid #FF9149; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <strong>Step 2: Confirmation.</strong> Please review the profiles below. Click "Confirm & Save" to lock these settings.
                    </div>
                <?php endif; ?>
            </div>

            <div class="row wow fadeInDown">
                <div class="col-sm-12">
                    <form class="approval-setup-form" method="post" action="<?php echo base_url('leave/set-approver'); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="step" value="<?php echo $step; ?>">

                        <?php if ($step == 2): ?>
                            <div class="row">
                                <?php
                                $profiles = [
                                    'Supervisor' => $post_data['supervisor_info'],
                                    'Approver'   => $post_data['approver_info']
                                ];

                                foreach ($profiles as $label => $profile): ?>
                                    <div class="col-sm-6">
                                        <div class="card border-success mb-3" style="border: 1px solid #28D094; border-radius: 8px; overflow: hidden;">
                                            <div class="card-header bg-success text-white h4">
                                                <strong><i class="fas fa-user"></i> <?php echo $label; ?> Profile</strong>
                                            </div>
                                            <div class="card-body" style="background: #f8f9fa;">
                                                <div class="row">
                                                    <div class="col-sm-4 text-center">
                                                        <img src="<?php echo base_url($profile['Profile_Picture'] ?? 'assets/img/default_avatar.png'); ?>"
                                                             class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: fill; border: 2px solid #28D094;">
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <h4 class="text-primary"><?php echo esc($profile['Name_English'] ?? 'N/A'); ?></h4>
                                                        <p class="mb-1"><strong>Office ID:</strong> <?php echo esc($profile['Payroll_ID'] ?? 'N/A'); ?></p>
                                                        <p class="mb-1"><strong>Designation NPCBL:</strong> <?php echo esc($profile['Designation_NPCBL'] ?? 'N/A'); ?></p>
                                                        <p class="mb-1"><strong>Designation RNPP:</strong> <?php echo esc($profile['Designation_CRNPP'] ?? 'N/A'); ?></p>
                                                        <p class="mb-1"><i class="fas fa-envelope text-secondary"></i> <?php echo esc($profile['Official_Email'] ?? 'N/A'); ?></p>
                                                        <p class="mb-0"><i class="fas fa-phone text-secondary"></i> <?php echo esc($profile['Contact_Number'] ?? 'N/A'); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <input type="hidden" name="supervisor_id" value="<?php echo $post_data['supervisor_id']; ?>">
                            <input type="hidden" name="approver_id" value="<?php echo $post_data['approver_id']; ?>">

                        <?php else: ?>
                            <div class="row">
                                <div class="col-sm-6 form-group">
                                    <label for="supervisor_id">Select Supervisor <span class="text-danger">*</span></label>
                                    <select name="supervisor_id" id="supervisor_id" class="form-control select2" required <?php echo $existing_config ? 'disabled' : ''; ?>>
                                        <option value="">-- Select Supervisor --</option>
                                        <?php if (isset($supUsers)) : ?>
                                            <?php foreach($supUsers as $sup):
                                                $id = $sup['user_id'] ?? $sup['np_hrm_tbl_clm_user_id'];
                                                $default_selected = ($existing_config && $existing_config['np_hrm_tbl_clm_supervisor_id'] == $id);
                                                ?>
                                                <option value="<?php echo esc($id); ?>" <?php echo set_select('supervisor_id', $id, $default_selected); ?>>
                                                    <?php echo strtoupper(esc($sup['full_name'])) . ' - ' . esc($sup['designation']) . ' [' . esc($sup['email']) . ']' . ' [ID: ' . esc($sup['office_id']) . ']' ; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-sm-6 form-group">
                                    <label for="approver_id">Select Approver <span class="text-danger">*</span></label>
                                    <select name="approver_id" id="approver_id" class="form-control select2" required <?php echo $existing_config ? 'disabled' : ''; ?>>
                                        <option value="">-- Select Approver --</option>
                                        <?php if (isset($appUsers)) : ?>
                                            <?php foreach($appUsers as $app):
                                                $id = $app['user_id'] ?? $app['np_hrm_tbl_clm_user_id'];
                                                $default_selected = ($existing_config && $existing_config['np_hrm_tbl_clm_approver_id'] == $id);
                                                ?>
                                                <option value="<?php echo esc($id); ?>" <?php echo set_select('approver_id', $id, $default_selected); ?>>
                                                    <?php echo strtoupper(esc($app['full_name'])) . ' - ' . esc($app['designation']) . ' [' . esc($app['email']) . ']' . ' [ID: ' . esc($app['office_id']) . ']' ; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!$existing_config): ?>
                            <div class="row mt-4">
                                <div class="col-sm-12 text-center">
                                    <?php if ($step == 1): ?>
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            <i class="fas fa-search"></i> Review Selection
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('leave/set-approver'); ?>" class="btn btn-primary btn-lg px-4 mr-2">
                                            <i class="fas fa-arrow-left"></i> Back
                                        </a>
                                        <button type="submit" class="btn btn-success btn-lg px-5">
                                            <i class="fas fa-check-circle"></i> Confirm & Save
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if ($existing_config['np_hrm_tbl_clm_supervisor_confirmation'] != 0): ?>
                                <div class="row mt-2">
                                    <div class="col-sm-12 text-center">
                                        <div class="alert alert-success d-inline-block h4">
                                            <i class="fas fa-lock"></i> Your supervisor has approved your selection.
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row mt-2">
                                <div class="col-sm-12 text-center">
                                    <div class="alert alert-warning d-inline-block h4">
                                        <i class="fas fa-lock"></i> Selection is locked. Please contact HR/Admin if you need to change your approvers.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="formLoader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background-color:rgba(255,255,255,0.8);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
        <img src="<?php echo base_url('images/loader.gif'); ?>" alt="Loading..." style="width:80px;">
        <p style="margin-top:10px; font-weight:bold; color:#333;">Processing your request, please wait...</p>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if ($('.select2').length > 0) {
            $('.select2').select2({
                width: '100%',
                placeholder: '-- Select --',
                allowClear: true
            });
        }

        $('.approval-setup-form').on('submit', function() {
            if ($(this)[0].checkValidity()) {
                $('#formLoader').show();
            }
        });
    });
</script>