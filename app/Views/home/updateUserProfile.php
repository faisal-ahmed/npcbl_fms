<section id="update_user_profile" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 10% !important;">
        <div class="row">
            <div class="col-sm-12 text-center mb-4">
                <h2 class="font-weight-bold"><i class="fas fa-user-shield text-success"></i> Administrative User Management</h2>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?php if (!empty($success)) : ?>
                    <div class="status alert alert-success alert-dismissable" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)) : ?>
                    <div class="status alert alert-danger alert-dismissable" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card shadow border-top-primary">
                    <div class="card-body">
                        <div class="form-group text-center mb-0">
                            <label class="h5 font-weight-bold">Search Employee</label>
                            <select id="target_user_id" class="form-control select2">
                                <option value="">-- Type Name or Payroll ID --</option>
                                <?php foreach ($allUsers as $user): ?>
                                    <option value="<?php echo $user['user_id']; ?>">
                                        <?php echo strtoupper(esc($user['full_name'])) . ' [' . esc($user['office_id']) . ']'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="slideDownContainer" style="display: none;">
            <div id="csrf_container"><?php echo csrf_field(); ?></div>

            <div class="row">
                <div class="col-md-5 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-id-card"></i> User Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <img id="prev_img" src="<?php echo base_url('images/default.png'); ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: fill; border: 3px solid #28D094;">
                            </div>
                            <h4 id="prev_name" class="text-center text-success font-weight-bold mb-3"></h4>

                            <table class="table table-sm table-borderless mt-2 h6">
                                <tr><td class="text-muted" width="45%">Payroll ID / User ID:</td><td id="prev_id" class="font-weight-bold"></td></tr>
                                <tr><td class="text-muted">Designation (NPCBL):</td><td id="prev_desig_npcbl" class="text-primary font-weight-bold"></td></tr>
                                <tr><td class="text-muted">Official Email:</td><td id="prev_email"></td></tr>
                                <tr><td class="text-muted">Personal Contact:</td><td id="prev_phone"></td></tr>
                            </table>

                            <hr/>
                            <h6 class="text-center font-weight-bold text-uppercase small text-muted">Leave Balances</h6>
                            <div id="prev_balances" class="row no-gutters text-center"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 mb-4">
                    <div class="card shadow border-top-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-key"></i> Account & Security Controls</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url('home/update-user-profile'); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="target_user_id" class="hidden_user_id_class">

                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label class="font-weight-bold">Account Status</label>
                                        <select name="account_status" id="account_status" class="form-control form-control-lg">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive / Suspended</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <div class="reset-password-container">
                                            <label class="font-weight-bold text-danger mb-0" for="resetPassCheck" style="cursor: pointer;">
                                                Reset to Default Password (rnpp#246)
                                            </label>
                                            <input type="checkbox" id="resetPassCheck" name="reset_password" value="yes">
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning font-weight-bold shadow-sm border-left-warning mt-2" style="font-size: 1.1em">
                                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> Resetting the password will take effect immediately. The user will be required to log in with <code>rnpp#246</code>.
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-warning btn-lg px-5 font-weight-bold shadow-sm">
                                        <i class="fas fa-save"></i> Update Account Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* 1. Component Theme Borders */
    .border-top-primary { border-top: 5px solid #4e73df; }
    .border-top-warning { border-top: 5px solid #f6c23e; }
    .border-left-success { border-left: 5px solid #1cc88a; }
    .border-left-danger { border-left: 5px solid #e74a3b; }
    .border-left-warning { border-left: 5px solid #f6c23e; }
    .card-header h5 { font-weight: bold; font-size: 1.1rem; }
    #prev_balances .col-4 { border: 1px solid #eee !important; }

    /* 2. Fix for Text/Email Overflow */
    #prev_email, #prev_id, #prev_desig_npcbl {
        word-break: break-all;
        white-space: normal;
        display: block;
    }

    /* 3. Responsive Stacking (Main Layout) */
    @media (max-width: 992px) {
        #slideDownContainer .row {
            display: flex;
            flex-direction: column;
        }
        #slideDownContainer .col-md-5,
        #slideDownContainer .col-md-7 {
            max-width: 100%;
            flex: 0 0 100%;
            margin-bottom: 20px;
        }
        .container-fluid {
            padding: 0 5% !important;
        }
    }

    /* 4. THE FIX: Reset Password Container (Internal alignment) */
    .reset-password-container {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding: 15px;

        /* This prevents the box from crossing left/right edges */
        width: 100%;
        box-sizing: border-box;

        background: #fff5f5;
        border: 2px dashed #e74a3b;
        border-radius: 8px;
    }

    /* Stack checkbox on top of text for narrow screens */
    @media (max-width: 576px) {
        .reset-password-container {
            flex-direction: column;
            text-align: center;
        }
    }

    /* 5. Custom Checkbox Design (32x32px) */
    #resetPassCheck {
        -webkit-appearance: none;
        appearance: none;
        background-color: #fff;
        margin: 0;
        width: 32px;
        height: 32px;
        border: 2px solid #e74a3b;
        border-radius: 4px;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: 0.2s ease;
        flex-shrink: 0; /* Prevents squishing */
    }

    #resetPassCheck:hover {
        background-color: #fff0f0;
        box-shadow: 0 0 5px rgba(231, 74, 59, 0.3);
    }

    /* Custom Checkmark */
    #resetPassCheck::before {
        content: "";
        width: 16px;
        height: 16px;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        box-shadow: inset 1em 1em #e74a3b;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    #resetPassCheck:checked::before {
        transform: scale(1);
    }

    #resetPassCheck + label {
        margin-bottom: 0 !important;
        cursor: pointer;
        font-size: 1.1rem;
    }
</style>

<script>
    var leaveTypeNames = {
        <?php foreach ($leaveTypes as $lt): ?>
        "<?php echo $lt['type_id']; ?>": "<?php echo esc($lt['type_name']); ?>",
        <?php endforeach; ?>
    };

    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({ width: '100%' });

        // Handle User Selection
        $('#target_user_id').on('change', function() {
            var userId = $(this).val();
            $('#resetPassCheck').prop('checked', false);
            $('.hidden_user_id_class').val(userId);

            if (!userId) {
                $('#slideDownContainer').slideUp();
                return;
            }

            // AJAX Request
            var csrfName = $('#csrf_container input').attr('name');
            var csrfHash = $('#csrf_container input').val();
            var postData = { 'user_id': userId };
            postData[csrfName] = csrfHash;

            $.ajax({
                url: '<?php echo base_url('home/ajaxGetUserLeaveProfile'); ?>',
                type: 'POST',
                dataType: 'json',
                data: postData,
                success: function(response) {
                    if(response.token) {
                        $('input[name="' + csrfName + '"]').val(response.token);
                    }

                    if (response.status === 'success') {
                        var profile = response.data.profile;
                        var balances = response.data.balances;

                        // Update Details View
                        $('#prev_name').text(profile.Name_English);
                        $('#prev_id').text(profile.Payroll_ID || profile.office_id);
                        $('#prev_desig_npcbl').text(profile.Designation_NPCBL || 'N/A');
                        $('#prev_email').text(profile.Official_Email || 'No Email Provided');
                        $('#prev_phone').text(profile.Contact_Number || 'No Phone Provided');

                        var imgPath = profile.Profile_Picture ? '<?php echo base_url(); ?>' + profile.Profile_Picture : '<?php echo base_url('images/default.png'); ?>';
                        $('#prev_img').attr('src', imgPath);

                        // Set Status
                        var userStatus = parseInt(profile.User_Status);
                        $('#account_status').val(userStatus === 1 ? 'active' : 'inactive');

                        // Update Leave Balances
                        var balHtml = '';
                        $.each(balances, function(i, bal) {
                            var rem = parseInt(bal.np_hrm_tbl_clm_allocated_days || 0) - parseInt(bal.np_hrm_tbl_clm_taken_days || 0);
                            var typeName = leaveTypeNames[bal.np_hrm_tbl_clm_leave_type_id] || "Type";
                            balHtml += `
                                <div class="col-4 border p-1" style="background:#fcfcfc;">
                                    <span class="h5 font-weight-bold text-success">${rem}</span><br>
                                    <small class="text-uppercase" style="font-size:10px;">${typeName}</small>
                                </div>`;
                        });
                        $('#prev_balances').html(balHtml);

                        $('#slideDownContainer').slideDown();
                    }
                },
                error: function() {
                    alert("Session expired. Please refresh the page.");
                }
            });
        });
    });
</script>