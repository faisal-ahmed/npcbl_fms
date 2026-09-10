<section id="acl-management" class="py-4">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">

        <div class="content-background mb-2" style="border-top: 5px solid #1B65D3 !important;">
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
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card-content">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fas fa-plus-circle"></i> Register New System Action</h6>
                            <form action="<?php echo base_url('acl/add-global-action'); ?>" method="post" class="form-row">
                                <?php echo csrf_field(); ?>
                                <div class="col-md-5">
                                    <input type="text" name="controller_name" class="form-control form-control-lg" placeholder="Controller (e.g. Leave)" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="action_name" class="form-control form-control-lg" placeholder="Action (e.g. delete_request)" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-lg btn-outline-success btn-block">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-background" style="border-top: 5px solid #1B65D3 !important;">
            <div class="row mb-1">
                <div class="col-sm-8 col-xl-8 col-lg-8">
                    <h4 class="text-left card-title">Access Control List (ACL)</h4>
                </div>
                <div class="col-sm-4 col-xl-4 col-lg-4">
                    <form method="get" action="<?php echo base_url('acl/manage-permissions'); ?>" class="form-inline" style="float: right;">
                        <label for="role_id" class="mr-1"><strong style="font-size: 1.2em;">Permission for Role: </strong></label>
                        <select id="role_id" name="role_id" class="form-control form-control-sm custom-select" onchange="this.form.submit()">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['np_hrm_tbl_clm_role_id']; ?>" <?php echo ($role['np_hrm_tbl_clm_role_id'] == $selectedRoleId) ? 'selected' : ''; ?>>
                                    <?php echo strtoupper($role['np_hrm_tbl_clm_role_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>

            <hr/>

            <div class="card col-xl-12 col-lg-12 mb-0" style="font-size: 1.1em;">
                <div class="card-content table-responsive">
                    <table class="table table-bordered row-grouping">
                        <thead class="bg-glow">
                        <tr>
                            <th class="border-top-0" style="font-size: 1em;">Module</th>
                            <th class="border-top-0" style="font-size: 1em;">Function/Method</th>
                            <th class="border-top-0 text-center" style="font-size: 1em;">Current Status</th>
                            <th class="border-top-0 text-center" style="font-size: 1em;">Grant/Revoke Authorization</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($permissions)): ?>
                            <?php
                            $currentModule = '';
                            foreach($permissions as $p):
                                if ($currentModule !== $p['np_hrm_tbl_clm_controller']):
                                    $currentModule = $p['np_hrm_tbl_clm_controller'];
                                    ?>
                                    <tr class="table-active">
                                        <td colspan="4" class="text-bold-600">
                                            <i class="fas fa-folder-open mr-1"></i> <?php echo strtoupper($currentModule); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="pl-3"><?php echo $p['np_hrm_tbl_clm_controller']; ?></td>
                                    <td><code class="text-info"><?php echo $p['np_hrm_tbl_clm_action']; ?></code></td>
                                    <td class="text-center">
                                        <div id="status-badge-<?php echo $p['np_hrm_tbl_clm_permission_id']; ?>">
                                            <?php if($selectedRoleId == 1): ?>
                                                <span class="badge badge-pill font-small-3" style="background-color: #e9ecef !important; color: #1B65D3 !important; font-weight: 600; border: 1px solid #adb5bd;">
                                                    <i class="fas fa-user-shield text-primary"></i> Permission Granted
                                                </span>
                                            <?php elseif($p['np_hrm_tbl_clm_is_allowed']): ?>
                                                <span class="badge badge-success badge-pill badge-glow font-small-3">
                                                    <i class="fas fa-check"></i> Allowed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger badge-pill badge-glow font-small-3">
                                                    <i class="fas fa-times"></i> Denied
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($selectedRoleId == 1): ?>
                                            <button class="btn btn-sm disabled" style="background-color: #e9ecef; border-color: #adb5bd; color: #1B65D3; font-weight: 600; cursor: not-allowed; opacity: 1;">
                                                <i class="fas fa-lock text-primary"></i> Fixed Access
                                            </button>
                                        <?php else: ?>
                                            <label class="switch-container">
                                                <input type="checkbox" class="permission-toggle"
                                                       id="switch-<?php echo $p['np_hrm_tbl_clm_permission_id']; ?>"
                                                       data-id="<?php echo $p['np_hrm_tbl_clm_permission_id']; ?>"
                                                    <?php echo $p['np_hrm_tbl_clm_is_allowed'] ? 'checked' : ''; ?>>
                                                <span class="slider"></span>
                                            </label>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center p-5 text-muted">No permissions defined for this role.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <input type="hidden" id="csrf_security_token" name="<?php echo csrf_token(); ?>" value="<?php echo csrf_hash(); ?>" />
        </div>
    </div>
</section>

<style>
    /* Custom Toggle Slider CSS to ensure "pulling" effect */
    .switch-container {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch-container input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #FF4961;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    input:checked + .slider {
        background-color: #28D094;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #28D094;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>

<script>
    $(document).ready(function() {
        $('.permission-toggle').on('change', function() {
            const $this = $(this);
            const $csrf = $('#csrf_security_token');
            const permId = $this.data('id');
            const $badgeContainer = $('#status-badge-' + permId);

            const isAllowed = $this.is(':checked') ? 1 : 0;
            const csrfName = $csrf.attr('name');
            const csrfHash = $csrf.val();

            // Visual feedback - opacity while saving
            $this.closest('.switch-container').css('opacity', '0.5');

            $.ajax({
                url: '<?php echo base_url("acl/update-permission"); ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    [csrfName]: csrfHash,
                    permission_id: permId,
                    is_allowed: isAllowed
                },
                success: function(response) {
                    $this.closest('.switch-container').css('opacity', '1');
                    if(response.status === 'success') {
                        $csrf.val(response.token);

                        if(isAllowed) {
                            $badgeContainer.html('<span class="badge badge-success badge-pill badge-glow font-small-3"><i class="fas fa-check"></i> Allowed</span>');
                        } else {
                            $badgeContainer.html('<span class="badge badge-danger badge-pill badge-glow font-small-3"><i class="fas fa-times"></i> Denied</span>');
                        }
                    } else {
                        alert('Security Error: ' + response.message);
                        location.reload();
                    }
                },
                error: function() {
                    location.reload();
                }
            });
        });
    });
</script>