<noscript>
    <div class="alert alert-danger text-center" style="margin-top: 20px;">
        JavaScript is required to reset your password. Please enable JavaScript in your browser.
    </div>
</noscript>

<section id="contact-page" style="padding-top: 50px;">
    <div class="container">
        <div class="center" style="padding-bottom: 0px;">
            <h2>Forget your account's password?</h2>
        </div>
        <div class="row wow fadeInDown">
            <div class="status alert alert-success alert-dismissable" style="<?php echo isset($success) ? '' : 'display: none'; ?>">
                <?php if (isset($success)) echo esc($success); ?>
            </div>
            <div class="text-danger status alert alert-danger alert-dismissable" style="border: 2px solid #c52d2f; <?php echo isset($error) ? '' : 'display: none'; ?>">
                <?php if (isset($error)) echo esc($error); ?>
            </div>
            <div class="status alert alert-warning alert-dismissable" style="<?php echo isset($notification) ? '' : 'display: none'; ?>">
                <?php if (isset($notification)) echo esc($notification); ?>
            </div>

            <form class="login-form" method="post" action="<?php echo current_url(); ?>" id="forgetPasswordForm">
                <?php echo csrf_field(); ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Payroll ID *</label>
                        <input type="text" name="payroll_id" class="form-control" required placeholder="Enter your payroll ID" value="<?php echo old('payroll_id'); ?>">
                    </div>

                    <div class="form-group">
                        <label>Official Email *</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter your official email address" value="<?php echo old('email'); ?>">
                    </div>

                    <!-- JS tracking fields -->
                    <?php if (isset($jsFields)) {
                        foreach ($jsFields as $field): ?>
                            <input type="hidden" name="<?php echo esc($field); ?>" id="<?php echo esc($field); ?>" value="">
                        <?php endforeach;
                    } ?>

                    <div class="form-group">
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" required>Submit</button>
                        <a href="<?php echo site_url('hrm/login'); ?>" class="btn btn-success btn-lg" style="padding: 7px; vertical-align: -webkit-baseline-middle;">Go back to Login?</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="get-started center wow fadeInDown animated" style="padding-bottom: 0px; visibility: visible; animation-name: fadeInDown;">
                <div class="request">
                    <h4><a href="#" style="padding: 6px 34px;">Having Trouble?</a></h4>
                </div>
                <h3 style="padding: 20px 20px 30px 20px; font-weight: bold; font-size: 1.2em;">If you have any trouble resetting your password, please contact the system administrator.</h3>
            </div>
        </div>
    </div><!--/.container-->
</section><!--/#contact-page-->

<!-- Loading Modal -->
<div id="loadingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:20px 40px; border-radius:5px; text-align:center; font-size:18px; color:#333;">
        Loading, please wait...
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

            // Enable submit only if all filled
            const allFilled = jsFields.every(f => {
                const el = document.getElementById(f);
                return el && el.value.trim() !== '';
            });

            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = !allFilled;
            }
        };

        const form = document.getElementById('forgetPasswordForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;

            showLoadingModal();

            await populateFields();
            form.submit();
        });

        // Initial population for debugging/visual
        populateFields();
    });
</script>
