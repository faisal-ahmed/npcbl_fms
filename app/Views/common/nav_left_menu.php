<?php
if (isset($session_data['user_id'])) {
    $roleId = (int)($session_data['role_id'] ?? 0);
    $perms = $user_permissions ?? [];

    $hasAccess = function($controller, $action) use ($roleId, $perms) {
        if ($roleId == SUPER_ADMIN_ROLE_ID) return true;
        return isset($perms[$controller]) && in_array($action, $perms[$controller]);
    };
?>

    <div class="main-menu menu-fixed menu-dark menu-bg-default rounded menu-accordion menu-shadow" style="height: 100%;">
        <div class="main-menu-content">
            <a class="navigation-brand d-none d-md-block d-lg-block d-xl-block text-center" href="<?php echo base_url(); ?>">
                <img class="brand-logo" alt="NPCBL logo" src="<?php echo base_url('app-assets/images/logo/govt-logo.png'); ?>" style="width: 80px;"/>
            </a>

            <ul class="navigation navigation-main" id="main-menu-navigation">

                <li class="nav-item <?php echo (($submenu ?? '') === 'homeIndex') ? 'active open' : ''; ?>">
                    <a href="<?php echo base_url('home'); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-title">Home</span>
                    </a>
                </li>

                <?php if ($hasAccess('leave', 'index')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'personalLeaveReport') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("leave"); ?>">
                            <i class="fas fa-clipboard-list"></i>
                            <span class="menu-title">Personal<br/>Leave<br/>History</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($hasAccess('leave', 'apply-for-cl')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'applyForCL') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("leave/apply-for-cl"); ?>">
                            <i class="fas fa-paper-plane"></i>
                            <span class="menu-title">Apply for<br/>Casual<br/>Leave</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($hasAccess('leave', 'apply-for-others')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'applyForOthers') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("leave/apply-for-others"); ?>">
                            <i class="fas fa-file-medical"></i>
                            <span class="menu-title">Apply for<br/>Other<br/>Leave</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($hasAccess('leave', 'alternative')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'alternative') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("leave/alternative"); ?>">
                            <i class="fas fa-hands-helping"></i>
                            <span class="menu-title">Alternate<br/>Assignments</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($hasAccess('leave', 'statistics')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'statistics') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("leave/statistics"); ?>">
                            <i class="fas fa-chart-area"></i>
                            <span class="menu-title">NPCBL<br/>Leave<br/>Statistics</span>
                        </a>
                    </li>
                <?php } ?>

                <?php if ($hasAccess('home', 'my-team')) { ?>
                    <li class="nav-item <?php echo ($submenu === 'myTeam') ? 'active open' : ''; ?>">
                        <a href="<?php echo base_url("home/my-team"); ?>">
                            <i class="fas fa-users"></i>
                            <span class="menu-title">My Team</span>
                        </a>
                    </li>
                <?php } ?>

                <?php
                $canSeeApprovals = $hasAccess('leave', 'leave-approval');
                $canSeeArchive   = $hasAccess('leave', 'approval-archive');
                if ($canSeeApprovals || $canSeeArchive) {
                    ?>
                    <li class="nav-item has-sub <?php echo in_array($submenu ?? '', ['approvalArchive', 'approvals']) ? 'open' : ''; ?>">
                        <a href="#">
                            <i class="fas fa-user-check"></i>
                            <span class="menu-title">My<br/>Approvals</span>
                        </a>
                        <ul class="menu-content">
                            <?php if ($canSeeApprovals) { ?>
                                <li class="<?php echo ($submenu === 'approvals') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("leave/leave-approval"); ?>">Waiting for My Approval</a>
                                </li>
                            <?php } ?>
                            <?php if ($canSeeArchive) { ?>
                                <li class="<?php echo ($submenu === 'approvalArchive') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("leave/approval-archive"); ?>">Approval Archives</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                $canSeeTaxList   = $hasAccess('tax', 'my-tax');
                $canAddTax       = $hasAccess('tax', 'add-tax-return');
                $canSeeAllTax    = $hasAccess('tax', 'all-tax-return');

                if ($canSeeTaxList || $canAddTax || $canSeeAllTax) {
                    ?>
                    <li class="nav-item has-sub <?php echo in_array($submenu ?? '', ['index', 'addTax', 'allTax', 'updateTax']) ? 'open' : ''; ?>">
                        <a href="#">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span class="menu-title">Tax<br/>Management</span>
                        </a>
                        <ul class="menu-content">
                            <?php if ($canSeeTaxList) { ?>
                                <li class="<?php echo ($submenu === 'index') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("tax/my-tax"); ?>">My Tax Returns</a>
                                </li>
                            <?php } ?>

                            <?php if ($canAddTax) { ?>
                                <li class="<?php echo ($submenu === 'addTax') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("tax/add-tax-return"); ?>">Add New Tax Return</a>
                                </li>
                            <?php } ?>

                            <?php if ($canSeeAllTax) { ?>
                                <li class="<?php echo ($submenu === 'allTax') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("tax/all-tax-return"); ?>">All User Records</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php
                $canManageAcl = $hasAccess('acl', 'manage-permissions');
                $canManualEntry = $hasAccess('home', 'manual-leave-entry');
                $updateUserProfile = $hasAccess('home', 'update-user-profile');
                $supervisorApproverList = $hasAccess('home', 'supervisor-approver-list');
                if ($canManageAcl || $canManualEntry || $updateUserProfile) {
                    ?>
                    <li class="nav-item has-sub <?php echo ($menu === 'admin') ? 'open' : ''; ?>">
                        <a href="#">
                            <i class="fas fa-user-shield"></i>
                            <span class="menu-title">Leave<br/>Management</span>
                        </a>
                        <ul class="menu-content">
                            <?php if ($canManageAcl) { ?>
                                <li class="<?php echo ($submenu === 'acl') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("acl/manage-permissions"); ?>">Permission Management</a>
                                </li>
                            <?php } ?>
                            <?php if ($updateUserProfile) { ?>
                                <li class="<?php echo ($submenu === 'updateUserProfile') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("home/update-user-profile"); ?>">Update User Profile</a>
                                </li>
                            <?php } ?>
                            <?php if ($supervisorApproverList) { ?>
                                <li class="<?php echo ($submenu === 'supervisorApproverList') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("home/supervisor-approver-list"); ?>">Supervisor Approver List</a>
                                </li>
                            <?php } ?>
                            <?php if ($canManualEntry) { ?>
                                <li class="<?php echo ($submenu === 'manualLeave') ? 'active' : ''; ?>">
                                    <a href="<?php echo base_url("home/manual-leave-entry"); ?>">Manual Leave Entry</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

            <?php
            $personalInfo       = $hasAccess('home', 'personal_info');
            $updatePassword     = $hasAccess('home', 'update-password');
            $setApprover        = $hasAccess('leave', 'set-approver');
            if ($personalInfo || $updatePassword || $setApprover) {
                ?>
                <li class="nav-item has-sub <?php echo in_array($submenu ?? '', ['personalInfo', 'updatePassword', 'setLeaveApprover']) ? 'open' : ''; ?>">
                    <a href="#">
                        <i class="fas fa-cogs"></i>
                        <span class="menu-title">Settings</span>
                    </a>
                    <ul class="menu-content">
                        <?php if ($personalInfo) { ?>
                            <li class="<?php echo ($submenu === 'personalInfo') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url("home/personal_info"); ?>">Personal Information</a>
                            </li>
                        <?php } ?>
                        <?php if ($setApprover) { ?>
                            <li class="<?php echo ($submenu === 'setLeaveApprover') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url("leave/set-approver"); ?>">Set Leave Approver</a>
                            </li>
                        <?php } ?>
                        <?php if ($updatePassword) { ?>
                            <li class="<?php echo ($submenu === 'updatePassword') ? 'active' : ''; ?>">
                                <a href="<?php echo base_url("home/update-password"); ?>">Update Password</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            </ul>

            <a class="navigation-brand d-none d-md-block d-lg-block d-xl-block" href="<?php echo base_url(); ?>">
                <img class="brand-logo" alt="NPCBL logo" src="<?php echo base_url('app-assets/images/logo/npcbl-logo.png'); ?>" style="width: 75px;"/>
            </a>
        </div>
    </div>
<?php } ?>