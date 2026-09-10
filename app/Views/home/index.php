<section id="personal-dashboard" class="dashboard-wrapper">
    <div class="container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger br-8 shadow-sm mb-3" role="alert"
                         style="border-left: 5px solid #721c24; border-top: 2px solid #c52d2f; border-right: 2px solid #c52d2f; border-bottom: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt mr-2" style="font-size: 20px;"></i>
                            <span><?php echo session()->getFlashdata('error'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <?php if ($user_data['Payroll_ID'] != MD_PAYROLL_ID) { ?>
            <div class="col-md-8">
                <div class="stats-card">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line text-primary"></i> Leave Statistics (<?php echo date('Y'); ?>)
                    </h2>

                    <div class="stats-container">
                        <?php if (!empty($leave_stats)): ?>
                            <?php foreach ($leave_stats as $stat): ?>
                                <div class="leave-item">
                                    <div class="clearfix item-header">
                                        <strong class="leave-type"><?php echo esc($stat['type_name']); ?></strong>
                                        <span class="pull-right usage-text">
                                            Used: <b><?php echo $stat['spent']; ?></b> / Total Allocated: <b><?php echo $stat['allocated']; ?></b> Days
                                        </span>
                                    </div>

                                    <div class="progress custom-progress">
                                        <?php
                                        $displayPercent = ($stat['allocated'] > 0) ? $stat['percent'] : 0;
                                        $barColor = ($displayPercent > 85) ? '#e67e22' : '#3498db';
                                        ?>
                                        <div class="progress-bar" role="progressbar"
                                             style="width: <?php echo $displayPercent; ?>%; background-color: <?php echo $barColor; ?>;"
                                             aria-valuenow="<?php echo $displayPercent; ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?php if($displayPercent > 10): ?>
                                                <small class="progress-label"><?php echo $displayPercent; ?>%</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="clearfix">
                                        <span class="pull-left remaining-text">
                                            <i class="fas fa-clock"></i> Remaining: <?php echo $stat['remaining']; ?> Days
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-warning br-8">
                                <i class="fas fa-exclamation-triangle"></i> No leave records found for the current year.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="<?php echo ($user_data['Payroll_ID'] != MD_PAYROLL_ID) ? "col-md-4" : "col-md-6 mx-auto"; ?>">
                <div class="profile-card">
                    <div class="profile-header">
                        <?php if (!empty($user_data['Profile_Picture'])) : ?>
                            <img class="img-circle profile-img"
                                 src="<?php echo base_url($user_data['Profile_Picture']); ?>"
                                 onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($user_data['Name_English']); ?>&size=160&background=2c3e50&color=fff';">
                        <?php endif; ?>

                        <h3 class="profile-name"><?php echo esc($user_data['Name_English']); ?></h3>

                        <div class="designation-badge">
                            <?php echo esc($user_data['Designation_NPCBL']); ?>
                        </div>
                    </div>

                    <div class="profile-body">
                        <p><i class="fas fa-id-card text-primary info-icon"></i> <b>ID:</b> <?php echo esc($user_data['Payroll_ID']); ?></p>
                        <p><i class="fas fa-envelope text-primary info-icon"></i> <b>Email:</b> <span class="email-text"><?php echo esc($user_data['Official_Email']); ?></span></p>
                        <p><i class="fas fa-sitemap text-primary info-icon"></i> <b>Dept:</b> <?php echo esc($user_data['Joining_Department']); ?></p>
                        <p><i class="fas fa-calendar-check text-primary info-icon"></i> <b>Joined:</b> <?php echo date('d M, Y', strtotime($user_data['Joining_Date'])); ?></p>

                        <hr class="dashed-hr">

                        <p class="address-box">
                            <i class="fas fa-map-marked-alt text-primary info-icon"></i>
                            <b>Present Address:</b><br/>
                            <span class="address-text text-bold-700">
                                <?php
                                $full_address = [
                                    $user_data['Present_Address_Line_1'] ?? '',
                                    $user_data['Present_Post_Office'] ?? '',
                                    $user_data['Present_District'] ?? ''
                                ];
                                echo esc(implode(', ', array_filter($full_address)));
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .dashboard-wrapper {
        padding: 60px 0;
        min-height: 100vh;
    }
    .stats-card {
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-top: 5px solid #4178b5;
    }
    .section-title {
        margin-top: 0;
        color: #2c3e50;
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 20px;
        font-weight: 700;
    }
    .stats-container { margin-top: 30px; }
    .leave-item { margin-bottom: 30px; }
    .item-header { margin-bottom: 8px; }
    .leave-type { font-size: 18px; color: #34495e; }
    .usage-text { font-size: 15px; color: #000000; } /* Set to Black for visibility */

    .custom-progress {
        height: 20px;
        background: #e9ecef;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }
    .progress-bar { transition: width 1.5s ease-in-out; }
    .progress-label { line-height: 20px; font-weight: bold; color: #fff; font-size: 1.2em; }

    .remaining-text { font-size: 16px; color: #FF4961; font-weight: 700; }
    .balance-label { font-size: 13px; font-style: italic; margin-top: 4px; }

    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-top: 6px solid #4178b5;
    }
    .profile-header {
        padding: 30px;
        background: #f8fafc;
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    .profile-img {
        height: 160px;
        width: 160px;
        border: 5px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        margin: 0 auto;
    }
    .profile-name {
        margin: 20px 0 10px;
        color: #2c3e50;
        font-weight: 700;
        font-size: 22px;
    }
    .designation-badge {
        background: #e1f5fe;
        color: #0288d1;
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .profile-body {
        padding: 30px;
        text-align: left;
        font-size: 15px;
        line-height: 1.8;
    }
    .info-icon { width: 25px; }
    .email-text { color: #008cf1; font-size: 1.1em; }
    .email-text:hover { color: #222222; text-decoration: underline; cursor: pointer; }
    .dashed-hr { margin: 20px 0; border-top: 1px dashed #ddd; }
    .address-box { line-height: 1.5; }
    .address-text {
        color: #7f8c8d;
        font-size: 14px;
        display: block;
        padding-left: 30px;
        margin-top: 5px;
    }
    .br-8 { border-radius: 8px; }
</style>