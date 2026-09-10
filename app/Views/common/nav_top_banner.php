<nav class="<?php if (isset($loggedIn) && $loggedIn == 'true') echo "header-navbar navbar"; ?> navbar-expand-md navbar-with-menu navbar-without-dd-arrow fixed-top navbar-light navbar-bg-color">
    <div class="navbar-wrapper">
        <div class="navbar-header d-md-none">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                <li class="nav-item d-md-none"><a class="navbar-brand" href="<?php echo base_url(); ?>"><img class="brand-logo d-none d-md-block" alt="NPCBL admin logo" src="<?php echo base_url('app-assets/images/logo/govt-logo.png'); ?>"><img class="brand-logo d-sm-block d-md-none" alt="NPCBL admin logo sm" src="<?php echo base_url('app-assets/images/logo/npcbl-logo.png'); ?>"></a></li>
                <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v">   </i></a></li>
            </ul>
        </div>
        <div class="navbar-container">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <?php if (isset($loggedIn) && $loggedIn == 'true') { ?>
                <ul class="nav navbar-nav float-left">
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a>
                    </li>
                </ul>
                <?php } ?>
                <ul class="nav navbar-nav mr-auto ml-auto">
                    <li class="nav-item">
                        <a class="navbar-brand flex-header" href="<?php echo base_url(); ?>">
                            <h1>
                                <?php
                                    if (isset($loggedIn) && $loggedIn == 'true')
                                        echo "NPCBL Human Resource Management (HRM) Portal";
                                    else echo "Welcome to NPCBL Human Resource Management (HRM) Portal"
                                ?>
                            </h1>
                        </a>
                    </li>
                </ul>
                <?php if (isset($loggedIn) && $loggedIn == 'true') { ?>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item user-hover-menu">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
<!--                            <span class="avatar avatar-online"><img src="<?php /*echo ( (isset($user_data)) ? base_url($user_data['Profile_Picture']) : base_url('app-assets/images/portrait/small/avatar-s-1.png') ); */?>" alt="avatar"></span>-->
                            <span class="avatar avatar-online">
                                <img src="<?php echo base_url('app-assets/images/logo/npcbl-logo.png'); ?>" alt="avatar">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo ($user_data['Payroll_ID'] != MD_PAYROLL_ID) ? base_url("home/personal_info") : "#"; ?>"><i class="ft-award"></i><?php echo ( (isset($user_data)) ? $user_data['Name_English'] : 'Mr. No Name'); ?></a>
<!--                            <div class="dropdown-divider"></div><a class="dropdown-item" href="<?php /*echo base_url('home/update-password'); */?>"><i class="ft-user"></i> Update Password</a>-->
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="<?php echo base_url('home/logout'); ?>"><i class="ft-power"></i> Logout</a>
                        </div>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
<style>
    /* Target mobile and tablets */
    @media (max-width: 991px) {
        .navbar-header .brand-logo {
            max-height: 40px; /* Adjust this value to fit your top bar height exactly */
            width: auto;      /* Maintains aspect ratio */
            object-fit: contain;
        }

        /* Optional: Ensure the navbar-brand wrapper doesn't force extra padding */
        .navbar-header .navbar-brand {
            padding: 5px 10px;
            display: flex;
            align-items: center;
        }

        /* Ensure the toggle buttons and icons align with the constrained logo */
        .navbar-header .nav-link {
            padding: 10px !important;
        }
    }
</style>
