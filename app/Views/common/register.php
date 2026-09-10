<section id="contact-page" style="padding-top: 50px;">
    <div class="container">
        <div class="center" style="padding-bottom: 20px;">
            <h2>এনপিসিবিএলের কর্মকর্তা/কর্মচারীর তথ্য হালনাগাদকরণ</h2>
            <?php if (isset($success)) : ?>
                <div class="status alert alert-success alert-dismissable">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)) : ?>
                <div class="status alert alert-danger alert-dismissable">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($notification)) : ?>
                <div class="status alert alert-warning alert-dismissable">
                    <?php echo $notification; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($successSubmission) && $successSubmission === false) { ?>
                <h3 class="text-danger status alert alert-danger alert-dismissable" id="instruction" style="border: 2px solid #c52d2f;">
                    <strong>Instruction:</strong> All fields with <span class="text-danger">*</span> are required.
                </h3>
                <div class="status alert alert-warning alert-dismissable" style="text-align: left;">
                    <h3 style="color: crimson; font-weight: bold; margin-bottom: 0px; padding-bottom: 0px; text-align: center; font-size: 1.1em;"><strong>** Note In General:</strong> নির্ভুল তথ্য প্রদান করুন। কর্তৃপক্ষ অসত্য তথ্যের প্রমাণ পেলে প্রয়োজনীয় আইনানুগ ব্যবস্থা গ্রহণ করবে।</h3><br/>
                    ** <strong>Note For 'N/A':</strong> "Not Available".<br/>
                    ** <strong>Note For Full Name (Bangla):</strong> Please write your official Bengali full-name only.<br/>
                    ** <strong>Note For Mobile Number:</strong> If your parents are deceased, please prefix their names with <strong>"Late"</strong> and enter <strong>"N/A"</strong> in the phone number fields.<br/>
                    ** <strong>Note For Photo Upload:</strong> Please upload a scanned copy of your professional passport-size photograph for official use. Only JPG, JPEG, or PNG formats are accepted, and the file size must be less than 2MB.<br/>
                    ** <strong>Note For Training:</strong> If you have received multiple trainings, please list the most significant one, especially any conducted under the General Contract.<br/>
                    ** <strong>Note For Payroll ID:</strong> Please ensure that you enter the correct Payroll ID. Do not enter incorrect information. If you are unsure, contact the Accounts Department for verification.<br/>
                    ** <strong>Note For Email Address:</strong> Please ensure Official Email address used for official communication.<br/>
                    ** <strong>Note For Bio-Metric Serial Number:</strong> For employees working in the Dhaka office, please enter 'N/A'.<br/>
                    ** <strong>Note For Relation with Emergency Contact Person:</strong> It can only be your Father, Mother, Brother, Sister or Spouse.<br/>
                </div>
            <?php } ?>
        </div>

        <?php if (isset($successSubmission) && $successSubmission === false) { ?>
            <div class="row wow fadeInDown">
            <form enctype="multipart/form-data" class="login-form" method="post" action="<?php echo base_url('hrm/register'); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="step" value="<?php echo esc($step); ?>">
                <?php if ($step === '2'): ?>
                    <input type="hidden" name="confirm" id="confirm" value="no">
                <?php endif; ?>

                <div class="col-sm-12">

                    <!-- Basic Information -->
                    <h4 class="text-primary">Basic Information</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="name_en">Full Name (English) <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="name_en"
                                    id="name_en"
                                    class="form-control"
                                    required maxlength="95" minlength="3"
                                    value="<?php echo set_value('name_en', $formData['name_en'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('name_en'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="name_bn">Full Name (Bangla) <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="name_bn"
                                    id="name_bn"
                                    class="form-control"
                                    required maxlength="95" minlength="3"
                                    value="<?php echo set_value('name_bn', $formData['name_bn'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('name_bn'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="joining_date">Date of Joining <span class="text-danger">*</span></label>
                            <input
                                    type="date"
                                    name="joining_date"
                                    id="joining_date"
                                    class="form-control"
                                    required
                                    value="<?php echo set_value('joining_date', $formData['joining_date'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('joining_date'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="joining_department">Joining Department <span class="text-danger">*</span></label>
                            <select
                                    name="joining_department"
                                    id="joining_department"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Department --</option>
                                <?php if (isset($departments)) : ?>
                                    <?php foreach($departments as $dept): ?>
                                        <option value="<?php echo esc($dept); ?>" <?php echo set_select('joining_department', $dept, (isset($formData['joining_department']) && $formData['joining_department'] === $dept)); ?>>
                                            <?php echo esc($dept); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="joining_department" value="<?php echo esc($formData['joining_department'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('joining_department'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="official_picture">Please Upload Your Official Picture <span class="text-danger">*</span></label>
                            <?php if ($step === '2' && !empty($formData['official_picture'])): ?>
                                <div>
                                    <img src="<?php echo $formData['official_picture']; ?>" alt="Official Picture" style="max-height: 150px; margin-bottom: 10px;">
                                </div>
                                <!-- Disable file input on step 2 -->
                                <input
                                        type="file"
                                        name="official_picture"
                                        id="official_picture"
                                        class="form-control"
                                        accept="image/png, image/jpeg"
                                        disabled
                                >
                                <input type="hidden" name="official_picture" value="<?php echo esc($formData['official_picture']); ?>">
                            <?php else: ?>
                                <input
                                        type="file"
                                        name="official_picture"
                                        id="official_picture"
                                        class="form-control"
                                        accept="image/png, image/jpeg"
                                        required
                                >
                                <?php if (isset($validation)) echo $validation->showError('official_picture'); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Designations -->
                    <h4 class="text-primary">Designations</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="designation_npcbl">Current Designation in NPCBL <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="designation_npcbl"
                                    id="designation_npcbl"
                                    class="form-control"
                                    placeholder="Example: Assistant Manager (Engineering)"
                                    required maxlength="95" minlength="3"
                                    value="<?php echo set_value('designation_npcbl', $formData['designation_npcbl'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('designation_npcbl'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="designation_crnpp">Current Designation in CRNPP <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="designation_crnpp"
                                    id="designation_crnpp"
                                    class="form-control"
                                    required maxlength="95" minlength="3"
                                    placeholder="If not available write 'N/A'"
                                    value="<?php echo set_value('designation_crnpp', $formData['designation_crnpp'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('designation_crnpp'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="designation_rnpp">Div./Shop/Dept. Designation in RNPP <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="designation_rnpp"
                                    id="designation_rnpp"
                                    class="form-control"
                                    placeholder="If not available write 'N/A'"
                                    required maxlength="95" minlength="3"
                                    value="<?php echo set_value('designation_rnpp', $formData['designation_rnpp'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('designation_rnpp'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="shop_rnpp">Shop/Dept. in RNPP <span class="text-danger">*</span></label>
                            <select
                                    name="shop_rnpp"
                                    id="shop_rnpp"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Shop/Dept. --</option>
                                <?php if (isset($shop_rnpp)) : ?>
                                    <?php foreach($shop_rnpp as $shop): ?>
                                        <option value="<?php echo esc($shop); ?>" <?php echo set_select('shop_rnpp', $shop, (isset($formData['shop_rnpp']) && $formData['shop_rnpp'] === $shop)); ?>>
                                            <?php echo esc($shop); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="shop_rnpp" value="<?php echo esc($formData['shop_rnpp'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('shop_rnpp'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="division_rnpp">Division in RNPP <span class="text-danger">*</span></label>
                            <select
                                    name="division_rnpp"
                                    id="division_rnpp"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Division --</option>
                                <?php if (isset($divisions)) : ?>
                                    <?php foreach($divisions as $division): ?>
                                        <option value="<?php echo esc($division); ?>" <?php echo set_select('division_rnpp', $division, (isset($formData['division_rnpp']) && $formData['division_rnpp'] === $division)); ?>>
                                            <?php echo esc($division); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="division_rnpp" value="<?php echo esc($formData['division_rnpp'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('division_rnpp'); ?>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>

                    <!-- Contact & Identity -->
                    <h4 class="text-primary">Contact & Identity</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="personal_contact">Personal (Permanent) Mobile No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="personal_contact"
                                    id="personal_contact"
                                    class="form-control"
                                    pattern="\d{8,}" title="Enter at least 8 digits"
                                    required maxlength="18"
                                    value="<?php echo set_value('personal_contact', $formData['personal_contact'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('personal_contact'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="nid">NID No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="nid"
                                    id="nid"
                                    class="form-control"
                                    pattern="\d{8,}" title="Enter at least 10 digits"
                                    required maxlength="18"
                                    value="<?php echo set_value('nid', $formData['nid'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('nid'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                            <input
                                    type="date"
                                    name="dob"
                                    id="dob"
                                    class="form-control"
                                    required
                                    value="<?php echo set_value('dob', $formData['dob'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('dob'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="training_group_no">Training Group No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="training_group_no"
                                    id="training_group_no"
                                    class="form-control"
                                    placeholder="If not available write 'N/A'"
                                    required maxlength="18" minlength="1"
                                    value="<?php echo set_value('training_group_no', $formData['training_group_no'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('training_group_no'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="training_group_name">Training Group Name <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="training_group_name"
                                    id="training_group_name"
                                    class="form-control"
                                    placeholder="If not available write 'N/A'"
                                    required maxlength="250" minlength="3"
                                    value="<?php echo set_value('training_group_name', $formData['training_group_name'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('training_group_name'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="training_duration">Training Duration (Weeks) <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="training_duration"
                                    id="training_duration"
                                    class="form-control"
                                    placeholder="Sample answer '8 Weeks'. If not available write 'N/A'"
                                    required maxlength="95" minlength="1"
                                    value="<?php echo set_value('training_duration', $formData['training_duration'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('training_duration'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="birth_district">District of Birth <span class="text-danger">*</span></label>
                            <select
                                    name="birth_district"
                                    id="birth_district"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select District --</option>
                                <?php if (isset($districts)) { foreach($districts as $dist): ?>
                                    <option value="<?php echo esc($dist); ?>" <?php echo set_select('birth_district', $dist, (isset($formData['birth_district']) && $formData['birth_district'] === $dist)); ?>>
                                        <?php echo esc($dist); ?>
                                    </option>
                                <?php endforeach; } ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="birth_district" value="<?php echo esc($formData['birth_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('birth_district'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Gender <span class="text-danger">*</span></label><br>
                            <label for="gender_male">
                                <input
                                        type="radio"
                                        name="gender"
                                        id="gender_male"
                                        value="Male"
                                        required
                                    <?php echo set_radio('gender', 'Male', (isset($formData['gender']) && $formData['gender'] === 'Male')); ?>
                                    <?php echo ($step === '2') ? 'disabled' : ''; ?>
                                > Male
                            </label>
                            <label for="gender_female" style="margin-left:20px;">
                                <input
                                        type="radio"
                                        name="gender"
                                        id="gender_female"
                                        value="Female"
                                        required
                                    <?php echo set_radio('gender', 'Female', (isset($formData['gender']) && $formData['gender'] === 'Female')); ?>
                                    <?php echo ($step === '2') ? 'disabled' : ''; ?>
                                > Female
                            </label>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="gender" value="<?php echo esc($formData['gender'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('gender'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="religion">Religion <span class="text-danger">*</span></label>
                            <select
                                    name="religion"
                                    id="religion"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Religion --</option>
                                <option value="Islam" <?php echo set_select('religion', 'Islam', (isset($formData['religion']) && $formData['religion'] === 'Islam')); ?>>Islam</option>
                                <option value="Hinduism" <?php echo set_select('religion', 'Hinduism', (isset($formData['religion']) && $formData['religion'] === 'Hinduism')); ?>>Hinduism</option>
                                <option value="Christianity" <?php echo set_select('religion', 'Christianity', (isset($formData['religion']) && $formData['religion'] === 'Christianity')); ?>>Christianity</option>
                                <option value="Buddhism" <?php echo set_select('religion', 'Buddhism', (isset($formData['religion']) && $formData['religion'] === 'Buddhism')); ?>>Buddhism</option>
                                <option value="Other" <?php echo set_select('religion', 'Other', (isset($formData['religion']) && $formData['religion'] === 'Other')); ?>>Others</option>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="religion" value="<?php echo esc($formData['religion'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('religion'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="blood_group">Blood Group <span class="text-danger">*</span></label>
                            <select
                                    name="blood_group"
                                    id="blood_group"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Blood Group --</option>
                                <option value="A+" <?php echo set_select('blood_group', 'A+', (isset($formData['blood_group']) && $formData['blood_group'] === 'A+')); ?>>A+</option>
                                <option value="A-" <?php echo set_select('blood_group', 'A-', (isset($formData['blood_group']) && $formData['blood_group'] === 'A-')); ?>>A-</option>
                                <option value="B+" <?php echo set_select('blood_group', 'B+', (isset($formData['blood_group']) && $formData['blood_group'] === 'B+')); ?>>B+</option>
                                <option value="B-" <?php echo set_select('blood_group', 'B-', (isset($formData['blood_group']) && $formData['blood_group'] === 'B-')); ?>>B-</option>
                                <option value="AB+" <?php echo set_select('blood_group', 'AB+', (isset($formData['blood_group']) && $formData['blood_group'] === 'AB+')); ?>>AB+</option>
                                <option value="AB-" <?php echo set_select('blood_group', 'AB-', (isset($formData['blood_group']) && $formData['blood_group'] === 'AB-')); ?>>AB-</option>
                                <option value="O+" <?php echo set_select('blood_group', 'O+', (isset($formData['blood_group']) && $formData['blood_group'] === 'O+')); ?>>O+</option>
                                <option value="O-" <?php echo set_select('blood_group', 'O-', (isset($formData['blood_group']) && $formData['blood_group'] === 'O-')); ?>>O-</option>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="blood_group" value="<?php echo esc($formData['blood_group'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('blood_group'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="official_email">Official Email <span class="text-danger">*</span></label>
                            <input
                                    type="email"
                                    name="official_email"
                                    id="official_email"
                                    class="form-control"
                                    required maxlength="95" minlength="5"
                                    placeholder="Email address used for official communication."
                                    value="<?php echo set_value('official_email', $formData['official_email'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('official_email'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="personal_email">Personal Email <span class="text-danger">*</span></label>
                            <input
                                    type="email"
                                    name="personal_email"
                                    id="personal_email"
                                    class="form-control"
                                    required maxlength="95" minlength="5"
                                    value="<?php echo set_value('personal_email', $formData['personal_email'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('personal_email'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="biometric_serial">Bio-Metric Serial Number <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="biometric_serial"
                                    id="biometric_serial"
                                    class="form-control"
                                    required maxlength="6" minlength="1"
                                    placeholder="For employees working in the Dhaka office, please enter 'N/A'."
                                    value="<?php echo set_value('biometric_serial', $formData['biometric_serial'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('biometric_serial'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="gate_pass">Gate Pass No. (From IRF Entry Pass Card) <span class="text-danger">*</span></label>
                            <input
                                    type="number"
                                    name="gate_pass"
                                    id="gate_pass"
                                    class="form-control"
                                    required maxlength="18" minlength="3"
                                    value="<?php echo set_value('gate_pass', $formData['gate_pass'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('gate_pass'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="payroll_id">Payroll ID <span class="text-danger">*</span></label>
                            <input
                                    type="number"
                                    name="payroll_id"
                                    id="payroll_id"
                                    class="form-control"
                                    required  maxlength="18" minlength="3"
                                    placeholder="Please ensure that you enter the correct Payroll ID. Do not enter incorrect information. If you are unsure, contact the Accounts Department for verification."
                                    value="<?php echo set_value('payroll_id', $formData['payroll_id'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('payroll_id'); ?>
                        </div>
                    </div>

                    <!-- Family & Emergency Contact -->
                    <h4 class="text-primary">Family & Emergency Contact</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="father_name">Father's Name <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="father_name"
                                    id="father_name"
                                    class="form-control"
                                    required  maxlength="95" minlength="3"
                                    value="<?php echo set_value('father_name', $formData['father_name'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('father_name'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="father_mobile">Father's Mobile No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="father_mobile"
                                    id="father_mobile"
                                    class="form-control"
                                    placeholder="If not available use 'N/A'."
                                    required maxlength="18" minlength="3"
                                    value="<?php echo set_value('father_mobile', $formData['father_mobile'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('father_mobile'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="mother_name">Mother's Name <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="mother_name"
                                    id="mother_name"
                                    class="form-control"
                                    required  maxlength="95" minlength="3"
                                    value="<?php echo set_value('mother_name', $formData['mother_name'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('mother_name'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="mother_mobile">Mother's Mobile No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="mother_mobile"
                                    id="mother_mobile"
                                    class="form-control"
                                    placeholder="If not available use 'N/A'."
                                    required  maxlength="18" minlength="3"
                                    value="<?php echo set_value('mother_mobile', $formData['mother_mobile'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('mother_mobile'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="emergency_contact_name">Emergency Contact Person Name <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="emergency_contact_name"
                                    id="emergency_contact_name"
                                    class="form-control"
                                    required  maxlength="95" minlength="3"
                                    value="<?php echo set_value('emergency_contact_name', $formData['emergency_contact_name'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('emergency_contact_name'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="emergency_contact_mobile">Emergency Contact Mobile No. <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="emergency_contact_mobile"
                                    id="emergency_contact_mobile"
                                    placeholder="If not available use 'N/A'."
                                    class="form-control"
                                    required maxlength="18" minlength="3"
                                    value="<?php echo set_value('emergency_contact_mobile', $formData['emergency_contact_mobile'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('emergency_contact_mobile'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="emergency_relation">Relation with Emergency Contact Person <span class="text-danger">*</span></label>
                            <input
                                    type="text"
                                    name="emergency_relation"
                                    id="emergency_relation"
                                    class="form-control" maxlength="95" minlength="3"
                                    required
                                    placeholder="Example: Father, Mother, Sister, Brother, Spouse."
                                    value="<?php echo set_value('emergency_relation', $formData['emergency_relation'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('emergency_relation'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                            <select
                                    name="marital_status"
                                    id="marital_status"
                                    class="form-control"
                                    required
                                <?php echo ($step === '2') ? 'disabled' : ''; ?>
                            >
                                <option value="">-- Select Marital Status --</option>
                                <option value="Married" <?php echo set_select('marital_status', 'Married', ($formData['marital_status'] ?? '') === 'Married'); ?>>Married</option>
                                <option value="Single" <?php echo set_select('marital_status', 'Single', ($formData['marital_status'] ?? '') === 'Single'); ?>>Single</option>
                                <option value="Divorced" <?php echo set_select('marital_status', 'Divorced', ($formData['marital_status'] ?? '') === 'Divorced'); ?>>Divorced</option>
                                <option value="Widowed" <?php echo set_select('marital_status', 'Widowed', ($formData['marital_status'] ?? '') === 'Widowed'); ?>>Widowed</option>
                            </select>
                            <?php if (isset($validation)) echo $validation->showError('marital_status'); ?>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="marital_status" value="<?php echo esc($formData['marital_status'] ?? ''); ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label for="spouse_name">
                                Name of Spouse <span id="spouse_name_required_mark" class="text-danger" style="display: none;">*</span>
                            </label>
                            <input
                                    type="text"
                                    name="spouse_name"
                                    id="spouse_name"
                                    placeholder="Enter name of your spouse."
                                    class="form-control" maxlength="95" minlength="3"
                                    value="<?php echo set_value('spouse_name', $formData['spouse_name'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('spouse_name'); ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="spouse_mobile">
                                Mobile No. of Spouse <span id="spouse_mobile_required_mark" class="text-danger" style="display: none;">*</span>
                            </label>
                            <input
                                    type="text"
                                    name="spouse_mobile"
                                    id="spouse_mobile"
                                    class="form-control"
                                    pattern="^$|^\d{8,}$" placeholder="Enter at least 8 digits or leave empty" maxlength="18"
                                    value="<?php echo set_value('spouse_mobile', $formData['spouse_mobile'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>
                            >
                            <?php if (isset($validation)) echo $validation->showError('spouse_mobile'); ?>
                        </div>
                    </div>

                    <!-- Address -->
                    <h4 class="text-primary">Present Address</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="present_address_line1">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="present_address_line1" id="present_address_line1" class="form-control" maxlength="250" minlength="3" required
                                   value="<?php echo set_value('present_address_line1', $formData['present_address_line1'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('present_address_line1'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="present_address_line2">Address Line 2</label>
                            <input type="text" name="present_address_line2" id="present_address_line2" maxlength="250" class="form-control"
                                   value="<?php echo set_value('present_address_line2', $formData['present_address_line2'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('present_address_line2'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="present_post_office">Post Office (With Post Code) <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Example: Dhaka - 1207." name="present_post_office" id="present_post_office" class="form-control" maxlength="95" minlength="3" required
                                   value="<?php echo set_value('present_post_office', $formData['present_post_office'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('present_post_office'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="present_police_station">Police Station <span class="text-danger">*</span></label>
                            <input type="text" name="present_police_station" id="present_police_station" maxlength="95" minlength="3" class="form-control" required
                                   value="<?php echo set_value('present_police_station', $formData['present_police_station'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('present_police_station'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="present_district">District <span class="text-danger">*</span></label>
                            <select name="present_district" id="present_district" class="form-control" required <?php echo ($step === '2') ? 'disabled' : ''; ?>>
                                <option value="">-- Select District --</option>
                                <?php if (isset($districts)) { foreach($districts as $dist): ?>
                                    <option value="<?php echo esc($dist); ?>" <?php echo set_select('present_district', $dist, ($formData['present_district'] ?? '') === $dist); ?>>
                                        <?php echo esc($dist); ?>
                                    </option>
                                <?php endforeach; } ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="present_district" value="<?php echo esc($formData['present_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="present_district" value="<?php echo esc($formData['present_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('present_district'); ?>
                        </div>
                    </div>

                    <!-- Permanent Address -->
                    <h4 class="text-primary">Permanent Address</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="permanent_address_line1">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_address_line1" id="permanent_address_line1" class="form-control" maxlength="250" minlength="3" required
                                   value="<?php echo set_value('permanent_address_line1', $formData['permanent_address_line1'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('permanent_address_line1'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="permanent_address_line2">Address Line 2</label>
                            <input type="text" name="permanent_address_line2" id="permanent_address_line2" maxlength="250" class="form-control"
                                   value="<?php echo set_value('permanent_address_line2', $formData['permanent_address_line2'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('permanent_address_line2'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="permanent_post_office">Post Office (With Post Code) <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Example: Dhaka - 1207."  name="permanent_post_office" id="permanent_post_office" maxlength="95" minlength="3" class="form-control" required
                                   value="<?php echo set_value('permanent_post_office', $formData['permanent_post_office'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('permanent_post_office'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="permanent_police_station">Police Station <span class="text-danger">*</span></label>
                            <input type="text" name="permanent_police_station" id="permanent_police_station" maxlength="95" minlength="3" class="form-control" required
                                   value="<?php echo set_value('permanent_police_station', $formData['permanent_police_station'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('permanent_police_station'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="permanent_district">District <span class="text-danger">*</span></label>
                            <select name="permanent_district" id="permanent_district" class="form-control" required <?php echo ($step === '2') ? 'disabled' : ''; ?>>
                                <option value="">-- Select District --</option>
                                <?php if (isset($districts)) { foreach($districts as $dist): ?>
                                    <option value="<?php echo esc($dist); ?>" <?php echo set_select('permanent_district', $dist, ($formData['permanent_district'] ?? '') === $dist); ?>>
                                        <?php echo esc($dist); ?>
                                    </option>
                                <?php endforeach; } ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="permanent_district" value="<?php echo esc($formData['permanent_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="permanent_district" value="<?php echo esc($formData['permanent_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('permanent_district'); ?>
                        </div>
                    </div>

                    <!-- Mailing Address -->
                    <h4 class="text-primary">Mailing Address</h4>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <label for="mailing_address_line1">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_address_line1" id="mailing_address_line1" class="form-control" maxlength="250" minlength="3" required
                                   value="<?php echo set_value('mailing_address_line1', $formData['mailing_address_line1'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('mailing_address_line1'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="mailing_address_line2">Address Line 2</label>
                            <input type="text" name="mailing_address_line2" id="mailing_address_line2" maxlength="250" class="form-control"
                                   value="<?php echo set_value('mailing_address_line2', $formData['mailing_address_line2'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('mailing_address_line2'); ?>
                        </div>

                        <div class="col-sm-12 form-group">
                            <label for="mailing_post_office">Post Office (With Post Code) <span class="text-danger">*</span></label>
                            <input type="text" placeholder="Example: Dhaka - 1207." name="mailing_post_office" id="mailing_post_office" maxlength="95" minlength="3" class="form-control" required
                                   value="<?php echo set_value('mailing_post_office', $formData['mailing_post_office'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('mailing_post_office'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="mailing_police_station">Police Station <span class="text-danger">*</span></label>
                            <input type="text" name="mailing_police_station" id="mailing_police_station" maxlength="95" minlength="3" class="form-control" required
                                   value="<?php echo set_value('mailing_police_station', $formData['mailing_police_station'] ?? ''); ?>"
                                <?php echo ($step === '2') ? 'readonly' : ''; ?>>
                            <?php if (isset($validation)) echo $validation->showError('mailing_police_station'); ?>
                        </div>

                        <div class="col-sm-6 form-group">
                            <label for="mailing_district">District <span class="text-danger">*</span></label>
                            <select name="mailing_district" id="mailing_district" class="form-control" required <?php echo ($step === '2') ? 'disabled' : ''; ?>>
                                <option value="">-- Select District --</option>
                                <?php if (isset($districts)) { foreach($districts as $dist): ?>
                                    <option value="<?php echo esc($dist); ?>" <?php echo set_select('mailing_district', $dist, ($formData['mailing_district'] ?? '') === $dist); ?>>
                                        <?php echo esc($dist); ?>
                                    </option>
                                <?php endforeach; } ?>
                            </select>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="mailing_district" value="<?php echo esc($formData['mailing_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if ($step === '2'): ?>
                                <input type="hidden" name="mailing_district" value="<?php echo esc($formData['mailing_district'] ?? ''); ?>">
                            <?php endif; ?>
                            <?php if (isset($validation)) echo $validation->showError('mailing_district'); ?>
                        </div>
                    </div>

                    <?php if ($step === '2'): ?>
                        <div class="row mb-4 text-center" style="background-color: lightgreen; padding: 20px; margin-bottom: 10px; font-size: 1.1em; border: 3px solid crimson;">
                            <div class="col">
                                <div class="form-check d-flex justify-content-center align-items-center">
                                    <input type="checkbox" class="form-check-input" id="attestationCheck" style="width: 20px;height: 20px;margin-top: 0.2em;transform: translateY(4px);">
                                    <label for="attestationCheck" class="form-check-label ms-3 mb-0" style="line-height: 1.6; text-align: left;">
                                        আমি এই মর্মে ঘোষণা দিচ্ছি যে, উপরে প্রদত্ত সকল তথ্য আমার জ্ঞান ও বিশ্বাস অনুযায়ী সঠিক এবং আমি মানসিকভাবে সুস্থ অবস্থায় এই ঘোষণা দিচ্ছি।
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="text-center my-3">
                        <?php if ($step === '1'): ?>
                            <button type="submit" class="btn btn-primary btn-lg">Next</button>
                        <?php elseif ($step === '2'): ?>
                            <button type="submit" name="step" value="1" class="btn btn-danger btn-lg me-2">Go Back & Update Data</button>
                            <button
                                    type="submit"
                                    class="btn btn-success btn-lg"
                                    id="confirmSubmitBtn"
                                    onclick="return confirmSubmission();"
                                    disabled
                            >
                                Confirm & Submit
                            </button>

                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
        <?php } ?>
    </div>
</section>

<?php if ($step !== '2'): ?>
    <script type="text/javascript">
        function toggleSpouseRequired() {
            const status = document.getElementById('marital_status').value;
            const spouseName = document.getElementById('spouse_name');
            const spouseMobile = document.getElementById('spouse_mobile');
            const nameMark = document.getElementById('spouse_name_required_mark');
            const mobileMark = document.getElementById('spouse_mobile_required_mark');

            if (status === 'Married') {
                spouseName.setAttribute('required', 'required');
                spouseMobile.setAttribute('required', 'required');
                nameMark.style.display = 'inline';
                mobileMark.style.display = 'inline';
            } else {
                spouseName.removeAttribute('required');
                spouseMobile.removeAttribute('required');
                nameMark.style.display = 'none';
                mobileMark.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const statusSelect = document.getElementById('marital_status');
            if (statusSelect) {
                toggleSpouseRequired();
                statusSelect.addEventListener('change', toggleSpouseRequired);
            }
        });
    </script>
<?php else: ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const instructionEl = document.getElementById("instruction");
            if (instructionEl) {
                instructionEl.innerHTML = '<strong>Instruction:</strong> Please review and confirm your data carefully. You will not be able to change the data once submitted.';
                instructionEl.classList.remove('text-danger');
                instructionEl.classList.add('text-primary');
                instructionEl.style.border = '2px solid #007bff';
            }

            const checkbox = document.getElementById("attestationCheck");
            const submitBtn = document.getElementById("confirmSubmitBtn");

            if (checkbox && submitBtn) {
                checkbox.addEventListener("change", function () {
                    submitBtn.disabled = !this.checked;
                });
            }
        });

        // This is the only confirmSubmission function you should keep
        function confirmSubmission() {
            if(confirm("Are you sure all data is correct? Once submitted, you cannot change it.")) {
                document.getElementById('confirm').value = 'yes';
                return true;
            }
            return false;
        }
    </script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            const instructionEl = document.getElementById("instruction");
            if (instructionEl) {
                instructionEl.innerHTML = '<strong>Instruction:</strong> Please review and confirm your data carefully. You will not be able to change the data once submitted.';
                instructionEl.classList.remove('text-danger');
                instructionEl.classList.add('text-primary');
                instructionEl.style.border = '2px solid #007bff';
            }
        });
    </script>
<?php endif; ?>

