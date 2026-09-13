<noscript>
    <div class="alert alert-danger text-center" style="margin-top: 20px;">
        JavaScript is required for login. Please enable JavaScript in your browser.
    </div>
</noscript>

<section style="padding-top: 50px;">
    <div class="container content-background">

        <div class="row" style="margin-bottom: 50px;">
            <div class="col-sm-12">
                <div class="text-center status alert alert-success alert-dismissable" style="color: #000000 !important; font-size: 1.2em;">
                    <h1>এনপিসিবিএল এর ফিটনেস ম্যানেজমেন্ট সিস্টেম পোর্টাল এ আপনাকে স্বাগতম</h1>
                </div>
            </div>
        </div>

        <hr/>

        <div class="text-center text-danger status alert alert-danger alert-dismissable" style="border: 2px solid #c52d2f; <?php echo isset($error) ? 'color: #000000 !important; font-size: 1.2em;' : 'display: none'; ?>">
            <?php if (isset($error)) echo esc($error); ?>
        </div>
        <div class="text-center status alert alert-warning alert-dismissable" style="<?php echo isset($notification) ? 'color: #000000 !important; font-size: 1.2em;' : 'display: none'; ?>">
            <?php if (isset($notification)) echo esc($notification); ?>
        </div>
        <form class="login-form" method="post" action="<?php echo site_url('employee/login'); ?>" id="loginForm">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Payroll ID *</label>
                        <input type="text" name="payroll_id" class="form-control" required placeholder="Enter your Payroll ID" value="<?php echo old('payroll_id'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Password *</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="password" class="form-control" required placeholder="Enter your password" style="padding-right: 45px;">
                            <span toggle="#password" class="fas fa-eye toggle-password"
                                  style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 10; font-size: 18px; color: #999; font-family: 'Font Awesome 5 Free' !important; font-weight: 900 !important;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" name="submitBtn" id="submitBtn" class="btn btn-success btn-lg">Login</button>
                    </div>
                </div>
                <div class="col-sm-3">
                    <a class="navigation-brand d-none d-md-block d-lg-block d-xl-block" href="<?php echo base_url(); ?>">
                        <img class="brand-logo" style="max-width:94%; padding: 3%" alt="NPCBL admin logo" src="<?php echo base_url('app-assets/images/logo/govt-logo.png'); ?>"/>
                    </a>
                </div>
                <div class="col-sm-3">
                    <a class="navigation-brand d-none d-md-block d-lg-block d-xl-block" href="<?php echo base_url(); ?>">
                        <img class="brand-logo" style="max-width:90%; padding: 5%" alt="NPCBL admin logo" src="<?php echo base_url('app-assets/images/logo/npcbl-logo.png'); ?>"/>
                    </a>
                </div>
            </div>
        </form>

        <hr/>
    </div></section><div id="loadingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background-color:rgba(255,255,255,0.8);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
        <img src="<?php echo base_url('images/loader.gif'); ?>" alt="Loading..." style="width:80px;">
        <p style="text-align:center; font-weight:bold;">Loading, Please wait...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Password Toggle Logic ---
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

        const showLoadingModal = () => {
            const modal = document.getElementById('loadingModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        };

        const form = document.getElementById('loginForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;

            showLoadingModal();

            form.submit();
        });
    });
</script>