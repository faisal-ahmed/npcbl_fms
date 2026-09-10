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

            <div class="col-xl-9 col-lg-8">

                <div class="card content-background">
                    <div class="card-header d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-industry text-primary"></i> Employment Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Joining Date</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Joining_Date']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Joining Department</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Joining_Department']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Designation (NPCBL)</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Designation_NPCBL']); ?></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Designation (RNPP)</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Designation_RNPP']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Designation (CRNPP)</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Designation_CRNPP']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Shop (RNPP)</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Shop_RNPP']); ?></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Division (RNPP)</label>
                                <div class="font-weight-bold text-dark"><?php echo esc($user['Division_RNPP']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Training Group</label>
                                <div class="font-weight-bold text-dark">
                                    <?php echo esc($user['Training_Group_Name']); ?>
                                    (Group No: <?php echo esc($user['Training_Group_No']); ?>)
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-secondary text-uppercase">Training Duration</label>
                                <div class="font-weight-bold text-dark">
                                    <?php echo esc($user['Training_Duration']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card content-background">
                    <div class="card-header">
                        <h4 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-cog text-primary"></i> Personal Details
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Father's Name</label>
                                <div class="font-weight-bold"><?php echo esc($user['Father_Name']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Mother's Name</label>
                                <div class="font-weight-bold"><?php echo esc($user['Mother_Name']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Spouse Name</label>
                                <div class="font-weight-bold"><?php echo esc($user['Spouse_Name'] ?: 'N/A'); ?></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Father's Contact No.</label>
                                <div class="font-weight-bold"><?php echo esc($user['Father_Mobile']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Mother's Contact No.</label>
                                <div class="font-weight-bold"><?php echo esc($user['Mother_Mobile']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Spouse Contact No.</label>
                                <div class="font-weight-bold"><?php echo esc($user['Spouse_Mobile'] ?: 'N/A'); ?></div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Emergency Contact Name</label>
                                <div class="font-weight-bold"><?php echo esc($user['Emergency_Contact_Name']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Emergency Contact Relation</label>
                                <div class="font-weight-bold"><?php echo esc($user['Emergency_Contact_Relation']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="text-bold-500 text-uppercase">Emergency Contact No.</label>
                                <div class="font-weight-bold"><?php echo esc($user['Emergency_Contact_Mobile'] ?: 'N/A'); ?></div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label class="text-bold-500 text-uppercase">Gender</label>
                                <div class="font-weight-bold"><?php echo esc($user['Gender']); ?></div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-bold-500 text-uppercase">Blood Group</label>
                                <div class="font-weight-bold"><?php echo esc($user['Blood_Group']); ?></div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-bold-500 text-uppercase">Marital Status</label>
                                <div class="font-weight-bold"><?php echo esc($user['Marital_Status']); ?></div>
                            </div>
                            <div class="col-md-3">
                                <label class="text-bold-500 text-uppercase">Religion</label>
                                <div class="font-weight-bold"><?php echo esc($user['Religion']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card content-background h-100">
                            <div class="card-header">
                                <h4 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-address-card text-primary"></i> Present Address
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><?php echo esc($user['Present_Address_Line_1']) . " ". esc($user['Present_Address_Line_2']); ?></p>
                                <p class="mb-1"><strong>Post Office:</strong> <?php echo esc($user['Present_Post_Office']); ?></p>
                                <p class="mb-1"><strong>Police Station:</strong> <?php echo esc($user['Present_Police_Station']); ?></p>
                                <p class="mb-0"><strong>District:</strong> <?php echo esc($user['Present_District']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card content-background h-100">
                            <div class="card-header">
                                <h4 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-address-card text-primary"></i> Permanent Address
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><?php echo esc($user['Permanent_Address_Line_1']) . " ". esc($user['Permanent_Address_Line_2']); ?></p>
                                <p class="mb-1"><strong>Post Office:</strong> <?php echo esc($user['Permanent_Post_Office']); ?></p>
                                <p class="mb-1"><strong>Police Station:</strong> <?php echo esc($user['Permanent_Police_Station']); ?></p>
                                <p class="mb-0"><strong>District:</strong> <?php echo esc($user['Permanent_District']); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card content-background h-100">
                            <div class="card-header">
                                <h4 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-address-card text-primary"></i> Mailing
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><?php echo esc($user['Mailing_Address_Line_1']) . " ". esc($user['Mailing_Address_Line_2']); ?></p>
                                <p class="mb-1"><strong>Post Office:</strong> <?php echo esc($user['Mailing_Post_Office']); ?></p>
                                <p class="mb-1"><strong>Police Station:</strong> <?php echo esc($user['Mailing_Police_Station']); ?></p>
                                <p class="mb-0"><strong>District:</strong> <?php echo esc($user['Mailing_District']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>