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
                    <h1>এনপিসিবিএল এর হিউম্যান রিসোর্স ম্যানেজমেন্ট সিস্টেম পোর্টাল এ আপনাকে স্বাগতম</h1>
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
        <form class="login-form" method="post" action="<?php echo site_url('hrm/login'); ?>" id="loginForm">
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

                    <?php if (isset($jsFields)) {
                        foreach ($jsFields as $field): ?>
                            <input type="hidden" name="<?php echo esc($field); ?>" id="<?php echo esc($field); ?>" value="">
                        <?php endforeach;
                    } ?>

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

        const jsFields = <?php echo json_encode($jsFields); ?>;

        const tryGet = (fn, fallback = 'Unavailable') => {
            try {
                const result = fn();
                return (result !== undefined && result !== null && result !== '') ? result : fallback;
            } catch {
                return fallback;
            }
        };

        const tryAsync = async (fn, fallback = 'Unavailable') => {
            try {
                return await fn();
            } catch {
                return fallback;
            }
        };

        const fetchPublicIP = async () => {
            try {
                const res = await fetch("https://api.ipify.org?format=json");
                const data = await res.json();
                return data.ip || 'Unavailable';
            } catch {
                return 'Unavailable';
            }
        };

        const fetchGeoInfo = async (ip) => {
            try {
                const res = await fetch(`https://ipapi.co/${ip}/json/`);
                const data = await res.json();
                return {
                    ip_address: ip,
                    ip_country: data.country_name || 'Unknown',
                    ip_region: data.region || 'Unknown',
                    ip_city: data.city || 'Unknown',
                    ip_org: data.org || 'Unknown',
                };
            } catch {
                return {
                    ip_address: ip,
                    ip_country: 'Unknown',
                    ip_region: 'Unknown',
                    ip_city: 'Unknown',
                    ip_org: 'Unknown',
                };
            }
        };

        const showLoadingModal = () => {
            const modal = document.getElementById('loadingModal');
            if (modal) {
                modal.style.display = 'flex';
            }
        };

        const populateFields = async () => {
            const values = {
                client_time: new Date().toISOString(),
                user_timezone: tryGet(() => Intl.DateTimeFormat().resolvedOptions().timeZone),
                device_info: tryGet(() => navigator.userAgent),
                platform: tryGet(() => navigator.platform),
                screen_resolution: tryGet(() => screen.width + 'x' + screen.height),
                language: tryGet(() => navigator.language),
                cookies_enabled: tryGet(() => navigator.cookieEnabled ? 'Yes' : 'No'),
                color_depth: tryGet(() => screen.colorDepth),
                hardware_concurrency: tryGet(() => navigator.hardwareConcurrency),
                touch_support: tryGet(() =>
                    ('ontouchstart' in window || navigator.maxTouchPoints > 0) ? 'Yes' : 'No'
                ),
                referrer: tryGet(() => document.referrer || 'None'),
                device_memory: tryGet(() => navigator.deviceMemory || 'Unknown'),
                connection_type: tryGet(() => navigator.connection ? navigator.connection.effectiveType : 'Unknown'),
                is_secure_context: tryGet(() => window.isSecureContext ? 'Yes' : 'No'),
                permissions_status: await tryAsync(async () => {
                    if (navigator.permissions && navigator.permissions.query) {
                        const result = await navigator.permissions.query({ name: 'notifications' });
                        return result.state;
                    }
                    return 'Not Supported';
                }),
            };

            const ip = await fetchPublicIP();
            const geo = await fetchGeoInfo(ip);

            values.client_ip = geo.ip_address;
            values.ip_country = geo.ip_country;
            values.ip_region = geo.ip_region;
            values.ip_city = geo.ip_city;
            values.ip_organization = geo.ip_org;

            for (const field of jsFields) {
                const input = document.getElementById(field);
                if (input) {
                    input.value = values[field] || 'Unavailable';
                }
            }

            const allFilled = jsFields.every(f => {
                const el = document.getElementById(f);
                return el && el.value.trim() !== '';
            });

            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = !allFilled;
            }
        };

        const form = document.getElementById('loginForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;

            showLoadingModal();

            await populateFields();
            form.submit();
        });

        populateFields();
    });
</script>