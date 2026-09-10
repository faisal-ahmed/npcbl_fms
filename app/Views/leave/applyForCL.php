<section id="contact-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="text-center"><i class="fas fa-paper-plane text-primary"></i> Apply for Casual Leave</h2>
            </div>
        </div>

        <hr/>

        <div class="content-background">
            <div class="center">
                <?php if (isset($success)) : ?>
                    <div class="status alert alert-success alert-dismissable" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <div class="status alert alert-danger alert-dismissable" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($notification)) : ?>
                    <div class="status alert alert-warning alert-dismissable" style="border: 2px solid #FF9149; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <?php echo $notification; ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-danger status alert alert-danger alert-dismissable" id="instruction" style="border: 2px solid #c52d2f; text-align: center">
                    <strong>Instruction:</strong> Please select your supervisor and manager carefully.
                </h3>
            </div>

            <div class="row wow fadeInDown">
                <div class="col-sm-12">
                    <h4 class="text-primary">Leave Requested By</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="name_en">Full Name (English) <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="name_en"
                                    id="name_en"
                                    class="form-control" disabled
                                    value="<?php echo set_value('name_en', $userData['Name_English'] ?? ''); ?>"
                            >
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="name_bn">Full Name (Bangla) <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="name_bn"
                                    id="name_bn"
                                    class="form-control" disabled
                                    value="<?php echo set_value('name_bn', $userData['Name_Bangla'] ?? ''); ?>"
                            >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="joining_date">Date of Joining <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="joining_date"
                                    id="joining_date"
                                    class="form-control" disabled
                                    value="<?php echo set_value('joining_date', $userData['Joining_Date'] ?? ''); ?>"
                            >
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="joining_department">Joining Department <span class="text-danger">*</span></label>
                            <select
                                    name="joining_department"
                                    id="joining_department"
                                    class="form-control" disabled
                            >
                                <?php if (isset($departments)) : ?>
                                    <?php foreach($departments as $dept): ?>
                                        <option value="<?php echo esc($dept); ?>" <?php echo set_select('joining_department', $dept, (isset($userData['Joining_Department']) && $userData['Joining_Department'] === $dept)); ?>>
                                            <?php echo esc($dept); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="designation_npcbl">Current Designation in NPCBL <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="designation_npcbl"
                                    id="designation_npcbl"
                                    class="form-control" disabled
                                    value="<?php echo set_value('designation_npcbl', $userData['Designation_NPCBL'] ?? ''); ?>"
                            >
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="designation_crnpp">Current Designation in CRNPP <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="designation_crnpp"
                                    id="designation_crnpp"
                                    class="form-control" disabled
                                    value="<?php echo set_value('designation_crnpp', $userData['Designation_CRNPP'] ?? ''); ?>"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <form class="apply-cl-form" method="post" action="<?php echo base_url('leave/apply-for-cl'); ?>">
                        <h4 class="text-primary">Please fill all the fields to apply for leave.</h4>
                        <hr/>
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label for="leave_type_display">Leave Type <span class="text-danger">*</span></label>

                                <select id="leave_type_display" class="form-control" style="height: 48px;" disabled>
                                    <option selected>Casual Leave</option>
                                </select>

                                <input type="hidden" name="leave_type" value="<?php echo $cl_id; ?>">

                                <?php if (isset($validation)) echo $validation->showError('leave_type'); ?>
                            </div>

                            <div class="col-sm-4 form-group">
                                <label for="remaining_days">Leave Balance Remaining for this year</label>
                                <?php
                                // Using the clean 'remaining' key from the controller helper
                                $remaining = $balances[$cl_id]['remaining'] ?? 0;
                                ?>
                                <input type="text" id="remaining_days" class="form-control"
                                       style="height: 48px; font-weight: bold; color: #c52d2f;"
                                       disabled value="<?php echo $remaining; ?> Days">
                            </div>

                            <div class="col-sm-4 form-group">
                                <label for="total_days">Total No. of Days Requested <span class="text-danger">*</span></label>
                                <input type="number" name="total_days" id="total_days" style="height: 48px;" class="form-control" readonly value="<?php echo set_value('total_days'); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label for="start_date">From Date <span class="text-danger">*</span></label>
                                <input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        style="height: 48px;"
                                        class="form-control"
                                        required
                                        min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                        value="<?php echo set_value('start_date'); ?>"
                                >
                                <?php if (isset($validation)) echo $validation->showError('start_date'); ?>
                            </div>

                            <div class="col-sm-4 form-group">
                                <label for="end_date">To Date <span class="text-danger">*</span></label>
                                <input
                                        type="date"
                                        name="end_date"
                                        id="end_date"
                                        style="height: 48px;"
                                        class="form-control"
                                        required
                                        value="<?php echo set_value('end_date'); ?>"
                                >
                                <?php if (isset($validation)) echo $validation->showError('end_date'); ?>
                            </div>

                            <div class="col-sm-4 form-group">
                                <label for="station_leave">Station Leave Required? <span class="text-danger">*</span></label>
                                <select name="station_leave" id="station_leave" class="form-control select2" required>
                                    <option value="">-- Select --</option>
                                    <option value="1" <?php echo set_select('station_leave', '1'); ?>>Yes, I will leave the station.</option>
                                    <option value="0" <?php echo set_select('station_leave', '0'); ?>>No, I will stay at the station.</option>
                                </select>
                                <?php if (isset($validation)): ?>
                                    <?php echo $validation->showError('station_leave'); ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="reason">Reason for Leave <span class="text-danger">*</span></label>
                                <textarea name="reason" id="reason" rows="3" style="height: 100px !important;" class="form-control" minlength="5" required><?php echo set_value('reason'); ?></textarea>
                                <?php if (isset($validation)) echo $validation->showError('reason'); ?>
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="address_in_leave">Address when in Leave <span class="text-danger">*</span></label>
                                <textarea name="address_in_leave" id="address_in_leave" rows="3" style="height: 100px !important;" class="form-control" minlength="5" required><?php echo set_value('address_in_leave'); ?></textarea>
                                <?php if (isset($validation)) echo $validation->showError('address_in_leave'); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="<?php echo ($user_current_grade > NO_SUPERVISOR_UPTO_GRADE) ? "col-sm-4" : "col-sm-6"; ?> form-group">
                                <label for="alternate_id">Alternative Informed Personnel <span class="text-danger">*</span></label>
                                <select name="alternate_id" id="alternate_id" class="form-control select2" required>
                                    <option value="">-- Select Alternative Informed Personnel --</option>
                                    <?php if (isset($alt_users)) : ?>
                                        <?php foreach($alt_users as $alt): ?>
                                            <option value="<?php echo esc($alt['user_id']); ?>" <?php echo set_select('alternate_id', $alt['user_id'], (isset($formData['alternate_id']) && $formData['alternate_id'] === $alt['user_id'])); ?>>
                                                <?php echo strtoupper(esc($alt['full_name'])) . ' - ' . esc($alt['designation']) . ' [' . esc($alt['email']) . ']' . ' [ID: ' . esc($alt['office_id']) . ']' ; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation)) echo $validation->showError('alternate_id'); ?>
                            </div>

                            <?php if ($user_current_grade > NO_SUPERVISOR_UPTO_GRADE) { ?>
                                <div class="col-sm-4 form-group">
                                    <label for="supervisor_id">Supervisor <span class="text-danger">*</span></label>
                                    <input type="hidden" name="supervisor_id" value="<?php echo esc($sup_id); ?>">

                                    <select id="supervisor_id" class="form-control select2" disabled required>
                                        <option value="">-- Select Supervisor --</option>
                                        <?php if (isset($supUsers)) : ?>
                                            <?php foreach($supUsers as $sup): ?>
                                                <option value="<?php echo esc($sup['user_id']); ?>" <?php echo set_select('supervisor_id', $sup['user_id'], ($sup['user_id'] == $sup_id)); ?>>
                                                    <?php echo strtoupper(esc($sup['full_name'])) . ' - ' . esc($sup['designation']) . ' [' . esc($sup['email']) . ']' . ' [ID: ' . esc($sup['office_id']) . ']' ; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($validation)) echo $validation->showError('supervisor_id'); ?>
                                </div>
                            <?php } ?>

                            <div class="<?php echo ($user_current_grade > NO_SUPERVISOR_UPTO_GRADE) ? "col-sm-4" : "col-sm-6"; ?> form-group">
                                <label for="approver_id">Approver <span class="text-danger">*</span></label>
                                <input type="hidden" name="approver_id" value="<?php echo esc($app_id); ?>">

                                <select id="approver_id" class="form-control select2" disabled required>
                                    <option value="">-- Select Approver --</option>
                                    <?php if (isset($appUsers)) : ?>
                                        <?php foreach($appUsers as $app): ?>
                                            <option value="<?php echo esc($app['user_id']); ?>" <?php echo set_select('approver_id', $app['user_id'], ($app['user_id'] == $app_id)); ?>>
                                                <?php echo strtoupper(esc($app['full_name'])) . ' - ' . esc($app['designation']) . ' [' . esc($app['email']) . ']' . ' [ID: ' . esc($app['office_id']) . ']' ; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation)) echo $validation->showError('approver_id'); ?>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success btn-lg">Apply For Leave</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="formLoader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background-color:rgba(255,255,255,0.8);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <img src="<?php echo base_url('images/loader.gif'); ?>" alt="Loading..." style="width:80px;">
        <p style="text-align:center; font-weight:bold;">Loading, Please wait...</p>
    </div>
</div>

<script type="text/javascript">
    // Select elements
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const totalDaysInput = document.getElementById('total_days');
    const form = document.querySelector('.apply-cl-form');
    // Using the clean mapped 'remaining' value passed from PHP
    const remainingDays = <?php echo (int)$remaining; ?>;

    // 1. Initial Logic: Set end_date min based on start_date if it exists
    if (startInput.value) {
        endInput.min = startInput.value;
    }

    // 2. Logic for total Days calculation & Date Constraints
    function handleDateChange() {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        // Update the "min" attribute of end date to prevent selecting a date before start date
        if (startInput.value) {
            endInput.min = startInput.value;
        }

        // If end date is now earlier than start date (after changing start), clear end date
        if (end < start) {
            endInput.value = '';
        }

        // Calculate days
        if (start && end && !isNaN(start) && !isNaN(end)) {
            const timeDiff = end - start;
            const dayCount = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;
            totalDaysInput.value = dayCount > 0 ? dayCount : '';
        } else {
            totalDaysInput.value = '';
        }
    }

    startInput.addEventListener('change', handleDateChange);
    endInput.addEventListener('change', handleDateChange);

    // For Loading Screen
    form.addEventListener('submit', function (e) {
        // Final JS validation check
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        const requestedDays = parseInt(totalDaysInput.value) || 0;

        if (end < start) {
            alert("End date cannot be before start date.");
            e.preventDefault();
            return false;
        }

        // Balance Check Logic
        if (requestedDays > remainingDays) {
            alert("Insufficient Balance! You requested " + requestedDays + " days, but you only have " + remainingDays + " days remaining.");
            e.preventDefault();
            return false;
        }

        document.getElementById('formLoader').style.display = 'block';
    });

    // For Select 2 initialization
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%',
            placeholder: '-- Select --',
            allowClear: true
        });
    });
</script>