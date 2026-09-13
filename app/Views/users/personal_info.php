<section id="profile_info" style="padding: 60px 0; font-size: 1.2em;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <h2 class="section-title mr-auto ml-auto">
                <i class="fas fa-user-tie text-primary"></i> Personal Information
            </h2>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="card content-background">
                    <div class="card-body text-center">
                        <?php
                        // Handle Profile Picture with a fallback
                        $profilePic = !empty($user['Profile_Picture']) ? base_url($user['Profile_Picture']) : base_url('assets/img/default_avatar.png');
                        ?>
                        <img src="<?php echo $profilePic; ?>"
                             alt="Profile Picture"
                             class="img-fluid rounded-circle mb-3"
                             style="width: 200px; height: 200px; border: 5px solid #e3e6f0;">

                        <h4 class="text-dark font-weight-bold"><?php echo esc($user['Name_English']); ?></h4>
                        <h4 class="text-dark font-weight-bold">(<?php echo esc($user['Name_Bangla']); ?>)</h4>
                        <br/><br/>
                        <p class="text-secondary mb-1"><?php echo esc($user['Designation_NPCBL']); ?></p>
                        <div class="badge badge-primary px-1 py-1" style="font-size: 1.1em;">Payroll ID: <?php echo esc($user['Payroll_ID']); ?></div>

                        <hr>

                        <div class="text-left">
                            <p class="clearfix">
                                <span class="float-left font-weight-bold">Status:</span>
                                <span class="float-right badge badge-success">Active</span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left font-weight-bold">Date of Birth</span>
                                <span class="float-right"><?php echo esc($user['Date_Of_Birth']); ?></span>
                            </p>
                            <p class="clearfix">
                                <span class="float-left font-weight-bold">Phone:</span>
                                <span class="float-right"><?php echo esc($user['Contact_Number']); ?></span>
                            </p>
                            <div class="clearfix">
                                <label class="font-weight-bold">Official Email:</label>
                                <p class="font-weight-bold text-primary" style="text-decoration: underline">
                                    <?php echo esc($user['Official_Email']); ?>
                                </p>
                            </div>
                            <div class="clearfix">
                                <label class="font-weight-bold">Personal Email:</label>
                                <p class="font-weight-bold text-primary" style="text-decoration: underline">
                                    <?php echo esc($user['Personal_Email']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card content-background">
                    <div class="card-header">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-circle text-primary"></i> Identifications
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                NID
                                <span class="font-weight-bold"><?php echo esc($user['NID']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Gate Pass
                                <span class="font-weight-bold"><?php echo esc($user['Gate_Pass_No']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Biometric ID
                                <span class="font-weight-bold"><?php echo esc($user['BioMetric_Serial']); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>