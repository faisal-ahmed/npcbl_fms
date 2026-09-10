<noscript>
    <div class="alert alert-danger text-center" style="margin-top: 20px;">
        JavaScript is required to reset your password. Please enable JavaScript in your browser.
    </div>
</noscript>

<section id="contact-page" style="padding-top: 50px;">
    <div class="container">
        <div class="center" style="padding-bottom: 20px;">
            <h2 class="text-center">Update your password</h2>
            <hr/>
        </div>

        <div class="row wow fadeInDown centered-form-wrapper">
            <div class="col-md-6 col-md-offset-3 tab-wrap ml-auto mr-auto content-background">
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

                <div id="passwordMatchMsg" class="alert alert-danger" style="display: none; border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                    Passwords do not match.
                </div>

                <div class="alert alert-info border-0 shadow-sm col-md-12 col-md-offset-12 ml-auto mr-auto"  style="border: 2px solid rgba(255,145,73,0.56); font-size: 1.2em; font-weight: bold;">
                    <p style="text-align: center; text-decoration: underline;"><i class="fas fa-info-circle"></i> <strong>Password Requirements</strong></p>
                    <ul class="mb-0 mt-1 list-unstyled" style="color: #000000 !important;">
                        <li>Minimum <strong>8 characters</strong> long.</li>
                        <li>At least one <strong>uppercase letter</strong> (A-Z).</li>
                        <li>At least one <strong>lowercase letter</strong> (a-z).</li>
                        <li>At least one <strong>number</strong> (0-9).</li>
                        <li>At least one <strong>special character</strong> (e.g., @, $, !, %, *, #, ?, &).</li>
                    </ul>
                </div>

                <form class="password-change-form" method="post" action="<?php echo base_url('home/update-password'); ?>" id="forgetPasswordForm">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label>Current Password *</label>
                        <div style="position: relative;">
                            <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Enter current password" style="padding-right: 45px;">

                            <span toggle="#current_password" class="fas fa-eye toggle-password"
                                  style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10; font-size: 18px; color: #999; font-family: 'Font Awesome 5 Free' !important; font-weight: 900 !important;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>New Password *</label>
                        <div style="position: relative;">
                            <input type="password" name="new_password" id="new_password" class="form-control"
                                   placeholder="Enter new password" required
                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}"
                                   style="padding-right: 45px;">

                            <span toggle="#new_password" class="fas fa-eye toggle-password"
                                  style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10; font-size: 18px; color: #999; font-family: 'Font Awesome 5 Free' !important; font-weight: 900 !important;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password *</label>
                        <div style="position: relative;">
                            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control"
                                   placeholder="Confirm new password" required
                                   style="padding-right: 45px;">

                            <span toggle="#confirm_new_password" class="fas fa-eye toggle-password"
                                  style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10; font-size: 18px; color: #999; font-family: 'Font Awesome 5 Free' !important; font-weight: 900 !important;"></span>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 25px;">
                        <button type="submit" id="submitBtn" class="btn btn-success btn-lg btn-block">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div id="formLoader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background:rgba(255,255,255,0.8);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
        <i class="fas fa-circle-notch fa-spin fa-3x text-success"></i>
        <p style="margin-top:10px; font-weight:bold;">Processing...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Visibility
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.querySelector(this.getAttribute('toggle'));
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });

        // Match Validation
        const form = document.getElementById('forgetPasswordForm');
        form.addEventListener('submit', function (e) {
            const p1 = document.getElementById('new_password').value;
            const p2 = document.getElementById('confirm_new_password').value;
            const msg = document.getElementById('passwordMatchMsg');

            if (p1 !== p2) {
                e.preventDefault();
                msg.style.display = 'block';
                return false;
            }
            document.getElementById('formLoader').style.display = 'block';
        });
    });
</script>