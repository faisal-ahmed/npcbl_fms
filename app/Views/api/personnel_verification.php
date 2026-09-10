<?php
// Handle image path logic
$profile_pic = !empty($userData['Profile_Picture']) ? base_url($userData['Profile_Picture']) : base_url('assets/images/default-avatar.png');
?>

<style>
    /* Mobile specific adjustments */
    @media (max-width: 767px) {
        #contact-page {
            padding-bottom: 50px; /* Bottom margin for the whole section */
        }
        .col-sm-4 {
            border-right: none !important; /* Remove vertical line on mobile */
            border-bottom: 1px solid #eee; /* Add horizontal line instead */
            margin-bottom: 20px;
            padding-bottom: 20px;
        }
        .alert {
            margin: 10px 15px; /* Add side margins for mobile */
        }
        .content-background {
            margin: 0 15px 30px 15px !important; /* Spacing for the card on mobile */
        }
    }
</style>

<section id="contact-page" style="padding-top: 50px;">
    <div class="container">
        <div class="row center" style="text-align: center; margin-bottom: 30px;">
            <div class="col-md-12">
                <h2 style="color: #2c3e50; font-weight: bold; text-align: center;">NPCBL Personnel Verification</h2>
                <hr/>

                <?php if (isset($userData['Payroll_ID'])): ?>
                    <div class="alert alert-success" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 20px;">
                        <i class="fa fa-check-circle"></i> <strong>Verified:</strong> The bearer of this card is an employee of Nuclear Power Plant Company Bangladesh Limited (NPCBL).
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold; margin-bottom: 20px;">
                        <i class="fas fa-times-circle"></i> <strong>Unverified:</strong> This card is considered invalid or the record does not exist.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($userData['Payroll_ID'])): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="content-background" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px; border-top: 4px solid #28D094; margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-sm-4 text-center" style="border-right: 1px solid #eee;">
                                <img src="<?php echo $profile_pic; ?>"
                                     alt="<?php echo $userData['Name_English']; ?>"
                                     class="img-thumbnail"
                                     style="width: 200px; height: 200px; margin-bottom: 15px; object-fit: cover;">
                                <h3 style="margin-top: 10px; color: #333; font-weight: bold;"><?php echo $userData['Name_English']; ?></h3>
                                <p class="text-center text-bold-700" style="font-size: 1.2em;"><?php echo $userData['Name_Bangla']; ?></p>
                                <span class="label label-primary" style="font-size: 14px; padding: 5px 10px;">ID: <?php echo $userData['Payroll_ID']; ?></span>
                            </div>

                            <div class="col-sm-8" style="padding-left: 30px;">
                                <h4 style="border-bottom: 1px solid #eee; padding-bottom: 10px; color: #2c3e50; font-weight: bold;">Employee Particulars</h4>

                                <div class="table-responsive" style="border: none;">
                                    <table class="table table-condensed" style="font-size: 1.1em;">
                                        <tbody>
                                        <tr>
                                            <td width="40%"><strong>Designation:</strong></td>
                                            <td><?php echo $userData['Designation_NPCBL']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                                            <td><?php echo $userData['Joining_Department']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Official Email:</strong></td>
                                            <td style="word-break: break-all;"><a href="mailto:<?php echo $userData['Official_Email']; ?>"><?php echo $userData['Official_Email']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Joining Date:</strong></td>
                                            <td><?php echo date('d M, Y', strtotime($userData['Joining_Date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Number:</strong></td>
                                            <td><?php echo $userData['Contact_Number']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Emergency Contact:</strong></td>
                                            <td><?php echo $userData['Emergency_Contact_Mobile']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Blood Group:</strong></td>
                                            <td style="color: #c0392b; font-weight: bold;"><?php echo $userData['Blood_Group']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Present District:</strong></td>
                                            <td><?php echo $userData['Present_District']; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="text-primary content-background text-center" style="padding: 40px; border: 1px solid #ddd; margin-bottom: 50px;">
                        <p style="font-size: 1.3em; font-weight: bold; color: #c52d2f; margin-bottom: 20px;">In case of fraudulent use or suspicion, please contact NPCBL immediately.</p>
                        <p style="font-size: 1.3em; font-weight: bold; line-height: 1.5em;">
                            <strong>Phone:</strong> +880-2-8189022<br>
                            <strong>Email:</strong> info@npcbl.gov.bd<br>
                            <strong>Web:</strong> <a href="http://www.npcbl.gov.bd" target="_blank">www.npcbl.gov.bd</a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>