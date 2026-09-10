<section id="leave-archive-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-8">
                <h2 class="text-left"><i class="fas fa-archive text-primary"></i> My Approval Archives - <?php echo $selectedYear; ?></h2>
            </div>
            <div class="col-sm-4 text-right pr-2 text-bold-700" style="font-size: 1.2em;">
                <form method="get" action="<?php echo base_url('leave/approval-archive'); ?>" class="form-inline" style="float: right;">
                    <div class="form-group">
                        <label style="margin-right: 10px;">Selected Year: </label>
                        <select name="year" class="form-control" onchange="this.form.submit()">
                            <?php foreach ($years as $y): ?>
                                <option value="<?php echo $y; ?>" <?php echo ($y == $selectedYear) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <hr/>

        <div class="row dataTable">
            <div class="col-sm-12">
                <table id="archiveTable" class="table table-bordered table-hover" style="width:100%">
                    <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th class="text-center">Sl</th>
                        <th>Applicant Details</th>
                        <th>My Role</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Days</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($archives)): ?>
                        <?php $i = 1; $currentUserId = session()->get('user_id');
                        foreach ($archives as $row):
                            $status = $row['np_hrm_tbl_clm_status'];

                            // Station Leave Logic
                            $stationLeaveVal = $row['np_hrm_tbl_clm_station_leave'] ?? 1;
                            $stationLeaveText = ($stationLeaveVal == 1) ? "Yes, I will leave the station." : "No, I will stay at the station.";

                            // Determine Role
                            $roleInApp = [];
                            if($row['np_hrm_tbl_clm_supervisor_id'] == $currentUserId) $roleInApp[] = "Supervisor";
                            if($row['np_hrm_tbl_clm_approver_id'] == $currentUserId) $roleInApp[] = "Approver";
                            $roleLabel = implode(' & ', $roleInApp);

                            if ($status == 2) {
                                $rowStyle = 'background-color: #eafff5 !important;';
                                $labelColor = '#28D094';
                                $statusText = 'Approved';
                            } elseif ($status == 3) {
                                $rowStyle = 'background-color: #fff2f2 !important;';
                                $labelColor = '#c52d2f';
                                $statusText = 'Rejected';
                            } elseif ($status == 1) {
                                $rowStyle = 'background-color: #fff9f0 !important;';
                                $labelColor = '#FF9149';
                                $statusText = 'Processing';
                            } else {
                                $rowStyle = 'background-color: #f8f9fa !important;';
                                $labelColor = '#6c757d';
                                $statusText = 'Pending';
                            }
                            ?>
                            <tr class="leave-row" style="cursor: pointer; <?php echo $rowStyle; ?>"
                                data-reason="<?php echo esc($row['np_hrm_tbl_clm_reason']); ?>"
                                data-sup-comment="<?php echo esc($row['np_hrm_tbl_clm_supervisor_remarks'] ?? 'N/A'); ?>"
                                data-sup-date="<?php echo ($row['np_hrm_tbl_clm_supervisor_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_supervisor_action_time']) : 'N/A'; ?>"
                                data-app-comment="<?php echo esc($row['np_hrm_tbl_clm_approver_remarks'] ?? 'N/A'); ?>"
                                data-app-date="<?php echo ($row['np_hrm_tbl_clm_approver_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_approver_action_time']) : 'N/A'; ?>"
                                data-admin-comment="<?php echo esc($row['np_hrm_tbl_clm_admin_remarks'] ?? 'N/A'); ?>"
                                data-applied="<?php echo date('d M, Y h:i A', $row['np_hrm_tbl_clm_created_at']); ?>"
                                data-station-leave="<?php echo esc($stationLeaveText); ?>"
                                data-address="<?php echo esc($row['np_hrm_tbl_clm_address_in_leave'] ?? 'N/A'); ?>"
                                data-alt-name="<?php echo esc($row['alternate_name'] ?? 'N/A'); ?>"
                                data-alt-mobile="<?php echo esc($row['alternate_mobile'] ?? 'N/A'); ?>"
                                data-alt-designation="<?php echo esc($row['alternate_designation_npcbl'] ?? 'N/A'); ?>"
                            >
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td>
                                    <strong><?php echo esc($row['applicant_name']); ?></strong><br/>
                                    <small><?php echo esc($row['applicant_designation']); ?></small><br/>
                                    <small><i class="fas fa-envelope"></i> <?php echo esc($row['applicant_email']); ?></small><br/>
                                    <small><i class="fas fa-phone"></i> <?php echo esc($row['applicant_mobile']); ?></small>
                                </td>
                                <td><span class="badge badge-primary"><?php echo $roleLabel; ?></span></td>
                                <td><?php echo esc($row['leave_type_name']); ?></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_start_date'])); ?></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_end_date'])); ?></td>
                                <td class="text-center"><?php echo $row['np_hrm_tbl_clm_total_days']; ?></td>
                                <td class="text-center">
                                    <span class="label" style="background-color:<?php echo $labelColor; ?>; color:#fff; padding: 4px 8px; font-weight: bold; border-radius: 3px;"><?php echo $statusText; ?></span>
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-plus toggle-icon"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .details-container { background-color: #fff; padding: 20px; border: 2px solid #ddd; border-radius: 4px; margin: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
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
    tr.shown { border-left: 5px solid #007bff; }
</style>

<script type="text/javascript">
    function format(d) {
        return '<div class="details-container">' +
            '<div class="row">' +
            /* Section 1: Comments */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Approval Chain & Comments</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-check text-primary"></i> Supervisor\'s Comments</div>' +
            '<div class="detail-content">' + d.supComment + '</div>' +
            '<div class="small text-muted">Date: ' + d.supDate + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-shield text-primary"></i> Approver\'s Comments</div>' +
            '<div class="detail-content">' + d.appComment + '</div>' +
            '<div class="small text-muted">Date: ' + d.appDate + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-gavel text-primary"></i> Admin/HR Remarks</div>' +
            '<div class="detail-content">' + d.adminComment + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 2: Application Details */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Application Details</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-calendar-alt text-primary"></i> Applied Date</div>' +
            '<div class="detail-content">' + d.applied + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-house-user text-primary"></i> Station Leave Status</div>' +
            '<div class="detail-content">' + d.stationLeave + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-info-circle text-primary"></i> Reason for Leave</div>' +
            '<div class="detail-content">' + d.reason + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 3: Contact */
            '<div class="col-md-4">' +
            '<div class="detail-group-title">Contact Information</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-map-marker-alt text-primary"></i> Address During Leave</div>' +
            '<div class="detail-content" style="background: #f9f9f9; padding: 10px; border-radius: 4px; border: 1px dashed #ccc;">' +
            d.address +
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

            '</div>' +
            '</div>' +
            '</div>';
    }

    $(document).ready(function () {
        var table = $('#archiveTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: [8] }]
        });

        $('#archiveTable tbody').on('click', 'tr.leave-row', function () {
            var tr = $(this);
            var row = table.row(tr);
            var icon = tr.find('.toggle-icon');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus').addClass('fa-plus');
            } else {
                table.rows().every(function() {
                    if(this.child.isShown()){
                        this.child.hide();
                        $(this.node()).removeClass('shown');
                        $(this.node()).find('.toggle-icon').removeClass('fa-minus').addClass('fa-plus');
                    }
                });

                var data = {
                    reason: tr.data('reason'),
                    supComment: tr.data('sup-comment'),
                    supDate: tr.data('sup-date'),
                    appComment: tr.data('app-comment'),
                    appDate: tr.data('app-date'),
                    adminComment: tr.data('admin-comment'),
                    applied: tr.data('applied'),
                    stationLeave: tr.data('station-leave'),
                    address: tr.data('address'),
                    altName: tr.data('alt-name'),
                    altDesignation: tr.data('alt-designation'),
                    altMobile: tr.data('alt-mobile')
                };

                row.child(format(data)).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
    });
</script>