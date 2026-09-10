<section id="leave-report-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-8">
                <h2 class="text-left"><i class="fas fa-clipboard-list text-primary"></i> Personal Leave Report - <?php echo $selectedYear; ?></h2>
            </div>

            <div class="col-sm-4 text-right pr-2 text-bold-700" style="font-size: 1.2em;">
                <form method="get" action="<?php echo base_url('leave/leave-report'); ?>" class="form-inline" style="float: right;">
                    <div class="form-group">
                        <label for="year" style="margin-right: 10px;">Selected Year: </label>
                        <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                            <?php foreach ($years as $y): ?>
                                <option value="<?php echo $y; ?>" <?php echo ($y == $selectedYear) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <hr/>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible" role="alert" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Success!</strong> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible" role="alert" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Error!</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="row dataTable">
            <div class="col-sm-12">
                <table id="leaveReportTable" class="table table-bordered table-hover" style="width:100%">
                    <?php if (!empty($leaveHistory)): ?>
                        <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th style="width: 50px;" class="text-center">Sl No.</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Days</th>
                            <th class="text-center">Status</th>
                            <th>Current Department & Contact Details</th>
                            <th class="text-center">Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($leaveHistory as $row):
                            $status = $row['np_hrm_tbl_clm_status'];

                            // Station Leave Logic
                            $stationLeaveVal = $row['np_hrm_tbl_clm_station_leave'] ?? 1;
                            $stationLeaveText = ($stationLeaveVal == 1) ? "Yes, I will leave the station." : "No, I will stay at the station.";

                            // Default style (Ash)
                            $rowStyle = 'background-color: #f2f2f2 !important;';
                            $labelColor = '#777777';
                            $statusText = 'Cancelled';

                            // Group 1: Pending (0, 1) -> Orange
                            if ($status == 0 || $status == 1) {
                                $rowStyle = 'background-color: #fff4e6 !important;';
                                $labelColor = '#FF9149';
                                $statusText = ($status == 0) ? 'Pending (Supervisor)' : 'Pending (Approver)';
                            }
                            // Group 2: Approved (2) -> Green
                            elseif ($status == 2) {
                                $rowStyle = 'background-color: #eafff5 !important;';
                                $labelColor = '#28D094';
                                $statusText = 'Approved';
                            }
                            // Group 3: Rejected (3) -> Red
                            elseif ($status == 3) {
                                $rowStyle = 'background-color: #fff2f2 !important;';
                                $labelColor = '#c52d2f';
                                $statusText = 'Rejected';
                            }
                            // Group 4: Cancelled (4) -> Ash
                            elseif ($status == 4) {
                                $rowStyle = 'background-color: #fff2f2 !important;';
                                $labelColor = '#777777';
                                $statusText = 'Cancelled';
                            }

                            $statusLabel = '<span class="label" style="background-color:'.$labelColor.'; border: 1px solid rgba(0,0,0,0.1); color:#fff; padding: 4px 8px; font-weight: bold; border-radius: 3px; display: inline-block;">'.$statusText.'</span>';

                            $handlerName = '';
                            $handlerPhone = '';
                            $handlerDesignation = '';

                            if ($status == 3 || $status == 4) {
                                $handlerName = $user_data['Name_English'] ?? 'Self-Cancelled';
                                $handlerPhone = $user_data['Contact_Number'] ?? '';
                                $handlerDesignation = $user_data['Designation_NPCBL'] ?? '';
                            } else if ($status == 0) {
                                $handlerName = $row['supervisor_name'];
                                $handlerPhone = $row['supervisor_mobile'];
                                $handlerDesignation = $row['supervisor_designation_npcbl'];
                            } else if ($status == 1) {
                                $handlerName = $row['approver_name'];
                                $handlerPhone = $row['approver_mobile'];
                                $handlerDesignation = $row['approver_designation_npcbl'];
                            } else {
                                $handlerName = 'HR / Admin';
                                $handlerPhone = '';
                                $handlerDesignation = '';
                            }
                            ?>
                            <tr class="leave-row" style="cursor: pointer; <?php echo $rowStyle; ?>"
                                data-reason="<?php echo esc($row['np_hrm_tbl_clm_reason']); ?>"
                                data-sup-comment="<?php echo esc($row['np_hrm_tbl_clm_supervisor_remarks'] ?? 'No comments.'); ?>"
                                data-sup-date="<?php echo ($row['np_hrm_tbl_clm_supervisor_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_supervisor_action_time']) : 'N/A'; ?>"
                                data-app-comment="<?php echo esc($row['np_hrm_tbl_clm_approver_remarks'] ?? 'No comments.'); ?>"
                                data-app-date="<?php echo ($row['np_hrm_tbl_clm_approver_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_approver_action_time']) : 'N/A'; ?>"
                                data-admin-comment="<?php echo esc($row['np_hrm_tbl_clm_admin_remarks'] ?? 'N/A'); ?>"
                                data-applied="<?php echo date('d M, Y h:i A', $row['np_hrm_tbl_clm_created_at']); ?>"
                                data-station-leave="<?php echo esc($stationLeaveText); ?>"
                                data-address="<?php echo esc($row['np_hrm_tbl_clm_address_in_leave'] ?? 'N/A'); ?>"
                                data-reqid="<?php echo $row['np_hrm_tbl_clm_leave_request_id']; ?>"
                                data-status="<?php echo $status; ?>"
                                data-updated-at="<?php echo ($row['np_hrm_tbl_clm_updated_at']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_updated_at']) : 'N/A'; ?>"
                                data-alt-name="<?php echo esc($row['alternate_name'] ?? 'N/A'); ?>"
                                data-alt-mobile="<?php echo esc($row['alternate_mobile'] ?? 'N/A'); ?>"
                                data-alt-designation="<?php echo esc($row['alternate_designation_npcbl'] ?? 'N/A'); ?>"
                            >
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><strong><?php echo esc($row['leave_type_name']); ?></strong></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_start_date'])); ?></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_end_date'])); ?></td>
                                <td class="text-center"><?php echo $row['np_hrm_tbl_clm_total_days']; ?></td>
                                <td class="text-center"><?php echo $statusLabel; ?></td>
                                <td>
                                    <?php echo esc($handlerName); ?><?php if ($handlerDesignation != '') echo " - " . esc($handlerDesignation); ?><?php if ($handlerPhone != '') echo '<br/><i class="fas fa-mobile-alt text-primary"></i> ' . esc($handlerPhone); ?>
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-plus toggle-icon" style="font-weight: 900 !important; font-family: 'Font Awesome 5 Free';"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    <?php else: ?>
                        <tbody>
                        <tr>
                            <td colspan="8" class="text-center" style="font-weight: bold; font-size: 1.2em;">No leave records found for the year <?php echo $selectedYear; ?>.</td>
                        </tr>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .details-container {
        background-color: #ffffff;
        padding: 20px;
        border: 2px solid #ddd;
        border-radius: 4px;
        margin: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .detail-group-title {
        font-weight: bold;
        color: #007bff;
        text-transform: uppercase;
        font-size: 1.2em;
        margin-bottom: 15px;
        border-bottom: 2px solid #007bff;
        display: inline-block;
    }
    .detail-section { margin-bottom: 15px; }
    .detail-title { font-weight: bold; color: #555; font-size: 1.1em; }
    .detail-content { color: #333; margin-top: 5px; line-height: 1.4; }
    .separator-column { border-right: 1px solid #eee; }
    #leaveReportTable thead th { vertical-align: middle; }
    tr.shown { border-left: 5px solid #007bff; }
</style>

<script type="text/javascript">
    /**
     * Formats the expandable row details
     */
    function format(d) {
        var currentStatus = (d && d.status !== undefined) ? parseInt(d.status) : 99;
        var requestId = (d && d.reqid) ? d.reqid : '';

        // Status Time Box
        var statusTimeBox = '';
        if (currentStatus === 2 || currentStatus === 3 || currentStatus === 4) {
            statusTimeBox = '<div class="detail-section" style="margin-top: 15px;">' +
                '<div class="detail-title"><i class="fas fa-clock text-info"></i> Final Status Time</div>' +
                '<div class="detail-content" style="font-weight: bold; color: #4178b5;">' + (d.updatedAt || 'N/A') + '</div>' +
                '</div>';
        }

        var cancelBtn = '';
        if (currentStatus === 0 || currentStatus === 1) {
            cancelBtn = '<hr/>' +
                '<form method="post" action="" onsubmit="return confirmCancel()">' +
                '<?php echo csrf_field(); ?>' +
                '<input type="hidden" name="request_id" value="' + requestId + '">' +
                '<input type="hidden" name="action" value="cancel">' +
                '<button type="submit" class="btn btn-danger btn-block" style="font-weight:bold; padding: 10px;">' +
                '<i class="fas fa-times-circle"></i> CANCEL LEAVE REQUEST' +
                '</button>' +
                '</form>';
        }

        return '<div class="details-container">' +
            '<div class="row">' +
            /* Section 1: Approval Chain */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Approval Chain & Comments</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-check text-primary"></i> Supervisor\'s Comments</div>' +
            '<div class="detail-content">' + (d.supComment || 'No comments.') + '</div>' +
            '<div class="small text-muted">Date: ' + (d.supDate || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-shield text-primary"></i> Approver\'s Comments</div>' +
            '<div class="detail-content">' + (d.appComment || 'No comments.') + '</div>' +
            '<div class="small text-muted">Date: ' + (d.appDate || 'N/A') + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 2: Application Details */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Application Details</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-calendar-alt text-primary"></i> Applied Date</div>' +
            '<div class="detail-content">' + (d.applied || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-house-user text-primary"></i> Station Leave Status</div>' +
            '<div class="detail-content">' + (d.stationLeave || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-info-circle text-primary"></i> Reason for Leave</div>' +
            '<div class="detail-content">' + (d.reason || 'N/A') + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 3: Contact Info, Alternate Person & Actions */
            '<div class="col-md-4">' +
            '<div class="detail-group-title">Contact Information</div>' +

            /* Address During Leave */
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-map-marker-alt text-primary"></i> Address During Leave</div>' +
            '<div class="detail-content" style="background: #f9f9f9; padding: 10px; border-radius: 4px; border: 1px dashed #ccc;">' +
            (d.address || 'N/A') +
            '</div>' +
            '</div>' +
            '<hr/>' +

            /* NEW: Alternate Informed Person */
            '<div class="detail-section">' +
            '<div class="detail-title text-warning"><i class="fas fa-user-friends text-warning"></i> Alternate Informed Person</div>' +
            '<div class="detail-content">' +
            '<strong>' + d.altName + '</strong><br/>' +
            '<small>' + d.altDesignation + '</small><br/>' +
            '<i class="fas fa-phone-alt"></i> ' + d.altMobile +
            '</div>' +
            '</div>' +

            statusTimeBox +
            cancelBtn +
            '</div>' +
            '</div>' +
            '</div>';
    }

    function confirmCancel() {
        return confirm("Are you sure you want to cancel this leave application?");
    }

    $(document).ready(function () {
        var table = $('#leaveReportTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: [7] }]
        });

        $('#leaveReportTable tbody').on('click', 'tr.leave-row', function () {
            var tr = $(this);
            var row = table.row(tr);
            var icon = tr.find('.toggle-icon');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus').addClass('fa-plus');
            } else {
                table.rows().every(function () {
                    if (this.child.isShown()) {
                        this.child.hide();
                        $(this.node()).removeClass('shown').find('.toggle-icon').removeClass('fa-minus').addClass('fa-plus');
                    }
                });

                var rowData = {
                    reason: tr.data('reason'),
                    supComment: tr.data('sup-comment'),
                    supDate: tr.data('sup-date'),
                    appComment: tr.data('app-comment'),
                    appDate: tr.data('app-date'),
                    adminComment: tr.data('admin-comment'),
                    applied: tr.data('applied'),
                    stationLeave: tr.data('station-leave'),
                    address: tr.data('address'),

                    // Mapping Alternate Person Data
                    altName: tr.data('alt-name'),
                    altMobile: tr.data('alt-mobile'),
                    altDesignation: tr.data('alt-designation'),

                    reqid: tr.data('reqid'),
                    status: tr.data('status'),
                    updatedAt: tr.data('updated-at')
                };

                row.child(format(rowData)).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
    });
</script>