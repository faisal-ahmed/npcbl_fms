<section id="manual-leave-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 10% !important;">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="text-center font-weight-bold"><i class="fas fa-user-edit text-success"></i> Admin: Manual Leave Entry</h2>
                <hr/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success shadow-sm" style="border-left: 8px solid #0f513a; font-size: 1.2rem; font-weight: bold; text-align: center;">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger shadow-sm" style="border-left: 8px solid #6b1f29; font-size: 1.2rem; font-weight: bold; text-align: center;">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-background">
            <div id="csrf_container"><?php echo csrf_field(); ?></div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow" style="border-top: 5px solid #28D094;">
                        <div class="card-body">
                            <div class="form-group text-center">
                                <label class="h4 font-weight-bold">Select Employee</label>
                                <select id="target_user_id" class="form-control select2">
                                    <option value="">-- Search by Name or ID --</option>
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
                <form action="<?php echo base_url('home/manual-leave-entry'); ?>" method="post" id="manualLeaveForm">
                    <div id="form_csrf_container"><?php echo csrf_field(); ?></div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div id="userPreview">
                                        <div class="text-center mb-3">
                                            <img id="prev_img" src="<?php echo base_url('images/default.png'); ?>" class="rounded-circle img-thumbnail" style="width: 160px; height: 160px; object-fit: fill; border: 3px solid #28D094;">
                                        </div>
                                        <h3 id="prev_name" class="text-center text-success font-weight-bold"></h3>
                                        <table class="table table-sm table-borderless h5">
                                            <tr><td class="text-muted">Payroll ID:</td><td id="prev_id" class="font-weight-bold"></td></tr>
                                            <tr><td class="text-muted">Designation:</td><td id="prev_desig"></td></tr>
                                        </table>
                                        <hr/>
                                        <h5 class="text-center font-weight-bold">Remaining Leave Balances</h5>
                                        <div id="prev_balances" class="row no-gutters text-center"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card shadow" style="border-top: 5px solid #1a8760; border-radius: 0 0 .25rem .25rem;">
                                <div class="card-header bg-success text-white">
                                    <h4 class="mb-0">Step 2: Leave Details</h4>
                                </div>
                                <div class="card-body h5">
                                    <input type="hidden" name="target_user_id" id="hidden_user_id">

                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Leave Type <span class="text-danger">*</span></label>
                                            <input type="hidden" name="leave_type" value="1">
                                            <select id="leave_type_display" class="form-control form-control-lg" disabled>
                                                <?php if (isset($leaveTypes)): ?>
                                                    <?php foreach ($leaveTypes as $type):
                                                        $typeId = $type['type_id'] ?? $type['np_hrm_tbl_clm_leave_type_id'];
                                                        $typeName = $type['type_name'] ?? $type['np_hrm_tbl_clm_leave_type_name'];
                                                        ?>
                                                        <option value="<?php echo esc($typeId); ?>" <?php echo ($typeId == 1) ? 'selected' : ''; ?>>
                                                            <?php echo esc($typeName); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>Total Days <span class="text-danger">*</span></label>
                                            <input type="number" name="total_days" id="total_days" class="form-control form-control-lg" min="1" readonly required>
                                            <small id="balance_warning" class="text-danger font-weight-bold" style="display:none;">Exceeds remaining balance!</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>From Date</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control form-control-lg" required>
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>To Date</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control form-control-lg" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <textarea name="reason" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>HR/Admin Remarks</label>
                                        <textarea name="hr_remarks" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-success btn-lg px-5 font-weight-bold">
                                            <i class="fas fa-check-circle"></i> Submit & Approve
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    var leaveTypeNames = {
        <?php foreach ($leaveTypes as $lt):
        $id = $lt['type_id'] ?? $lt['np_hrm_tbl_clm_leave_type_id'];
        $name = $lt['type_name'] ?? $lt['np_hrm_tbl_clm_leave_type_name'];
        ?>
        "<?php echo $id; ?>": "<?php echo esc($name); ?>",
        <?php endforeach; ?>
    };

    var userBalances = {};

    function validateSubmission() {
        var days = parseInt($('#total_days').val()) || 0;
        var available = userBalances[1] || 0;

        if (days > available) {
            $('#balance_warning').show();
            $('#total_days').addClass('is-invalid');
            $('#submitBtn').prop('disabled', true);
        } else if (days <= 0) {
            $('#balance_warning').hide();
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#balance_warning').hide();
            $('#total_days').removeClass('is-invalid');
            $('#submitBtn').prop('disabled', false);
        }
    }

    function calculateDays() {
        var start = $('#start_date').val();
        var end = $('#end_date').val();
        if (start && end) {
            var d1 = new Date(start);
            var d2 = new Date(end);
            var timeDiff = d2.getTime() - d1.getTime();
            var dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
            $('#total_days').val(dayDiff > 0 ? dayDiff : 0);
            validateSubmission();
        }
    }

    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        $('#target_user_id').on('change', function() {
            var userId = $(this).val();
            $('#hidden_user_id').val(userId);

            if (!userId) {
                $('#slideDownContainer').slideUp();
                return;
            }

            $('#formLoader').show();
            // Pull the latest token from the global container
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
                    $('#formLoader').hide();

                    // CRITICAL FIX: Update ALL CSRF fields with the new token
                    if(response.token) {
                        $('input[name="' + csrfName + '"]').val(response.token);
                    }

                    if (response.status === 'success') {
                        var profile = response.data.profile;
                        var balances = response.data.balances;
                        userBalances = {};

                        $('#prev_name').text(profile.Name_English);
                        $('#prev_id').text(profile.Payroll_ID);
                        $('#prev_desig').text(profile.Designation_NPCBL);
                        var img = profile.Profile_Picture ? profile.Profile_Picture : 'default.png';
                        var base_url = '<?php echo base_url(); ?>';
                        img = base_url + img;

                        $('#prev_img').attr('src', img);

                        var balHtml = '';
                        $.each(balances, function(i, bal) {
                            var typeId = bal.np_hrm_tbl_clm_leave_type_id;
                            var typeName = leaveTypeNames[typeId] || "Unknown Type";
                            var rem = parseInt(bal.np_hrm_tbl_clm_allocated_days || 0) - parseInt(bal.np_hrm_tbl_clm_taken_days || 0);
                            userBalances[typeId] = rem;
                            balHtml += `<div class="col-4 border p-2" style="background:#f9f9f9;">
                                <span class="h4 font-weight-bold text-success">${rem}</span><br>
                                <small class="font-weight-bold text-uppercase">${typeName}</small>
                            </div>`;
                        });
                        $('#prev_balances').html(balHtml);
                        $('#slideDownContainer').slideDown();
                        validateSubmission();
                    }
                },
                error: function() {
                    $('#formLoader').hide();
                    alert("Security Token expired. Please refresh the page.");
                }
            });
        });

        $('#start_date, #end_date').on('change', function() {
            calculateDays();
        });

        $('#manualLeaveForm').on('submit', function() {
            $('#formLoader').show();
        });
    });
</script>