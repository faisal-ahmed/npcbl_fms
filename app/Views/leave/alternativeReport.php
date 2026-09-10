<section id="alternative-leave-report-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-8">
                <h2 class="text-left"><i class="fas fa-user-tag text-primary"></i> Alternate Duty Coverage - <?php echo $selectedYear; ?></h2>
                <p class="text-muted">List of leave requests where you are designated as the Alternative Informed Person.</p>
            </div>

            <div class="col-sm-4 text-right pr-2 text-bold-700" style="font-size: 1.2em;">
                <form method="get" action="<?php echo base_url('leave/alternative'); ?>" class="form-inline" style="float: right;">
                    <div class="form-group">
                        <label for="year" style="margin-right: 10px;">Year: </label>
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

        <div class="row dataTable">
            <div class="col-sm-12">
                <table id="alternativeTable" class="table table-bordered table-hover" style="width:100%">
                    <?php if (!empty($leaveHistory)): ?>
                        <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th style="width: 50px;" class="text-center">Sl</th>
                            <th>Applicant Name</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th class="text-center">Days</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; foreach ($leaveHistory as $row):
                            $status = $row['np_hrm_tbl_clm_status'];

                            // Station Leave Logic
                            $slVal = $row['np_hrm_tbl_clm_station_leave'] ?? 1;
                            $slText = ($slVal == 1) ? "Yes, leaving the station." : "No, staying at the station.";

                            // Styling based on Status
                            $rowStyle = 'background-color: #f2f2f2 !important;';
                            $labelColor = '#777777';
                            $statusText = 'Cancelled';

                            if ($status == 0 || $status == 1) {
                                $rowStyle = 'background-color: #fff4e6 !important;';
                                $labelColor = '#FF9149';
                                $statusText = ($status == 0) ? 'Pending (Sup)' : 'Pending (App)';
                            } elseif ($status == 2) {
                                $rowStyle = 'background-color: #eafff5 !important;';
                                $labelColor = '#28D094';
                                $statusText = 'Approved';
                            } elseif ($status == 3) {
                                $rowStyle = 'background-color: #fff2f2 !important;';
                                $labelColor = '#c52d2f';
                                $statusText = 'Rejected';
                            }

                            $statusLabel = '<span class="label" style="background-color:'.$labelColor.'; color:#fff; padding: 4px 8px; font-weight: bold; border-radius: 3px; display: inline-block;">'.$statusText.'</span>';
                            ?>
                            <tr class="leave-row" style="cursor: pointer; <?php echo $rowStyle; ?>"
                                data-applicant="<?php echo esc($row['applicant_name']); ?>"
                                data-designation="<?php echo esc($row['applicant_designation']); ?>"
                                data-mobile="<?php echo esc($row['applicant_mobile']); ?>"
                                data-reason="<?php echo esc($row['np_hrm_tbl_clm_reason']); ?>"
                                data-sup-comment="<?php echo esc($row['np_hrm_tbl_clm_supervisor_remarks'] ?? 'No comments.'); ?>"
                                data-sup-date="<?php echo ($row['np_hrm_tbl_clm_supervisor_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_supervisor_action_time']) : 'N/A'; ?>"
                                data-app-comment="<?php echo esc($row['np_hrm_tbl_clm_approver_remarks'] ?? 'No comments.'); ?>"
                                data-app-date="<?php echo ($row['np_hrm_tbl_clm_approver_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_approver_action_time']) : 'N/A'; ?>"
                                data-applied="<?php echo date('d M, Y h:i A', $row['np_hrm_tbl_clm_created_at']); ?>"
                                data-station-leave="<?php echo esc($slText); ?>"
                                data-address="<?php echo esc($row['np_hrm_tbl_clm_address_in_leave'] ?? 'N/A'); ?>"
                                data-status="<?php echo $status; ?>"
                                data-updated-at="<?php echo ($row['np_hrm_tbl_clm_updated_at']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_updated_at']) : 'N/A'; ?>"
                            >
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td>
                                    <strong><?php echo esc($row['applicant_name']); ?></strong><br/>
                                    <small class="text-muted"><?php echo esc($row['applicant_designation']); ?></small>
                                </td>
                                <td><?php echo esc($row['leave_type_name']); ?></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_start_date'])); ?></td>
                                <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_end_date'])); ?></td>
                                <td class="text-center"><?php echo $row['np_hrm_tbl_clm_total_days']; ?></td>
                                <td class="text-center"><?php echo $statusLabel; ?></td>
                                <td class="text-center">
                                    <i class="fas fa-plus toggle-icon"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    <?php else: ?>
                        <tbody>
                        <tr>
                            <td colspan="8" class="text-center">No coverage records found for <?php echo $selectedYear; ?>.</td>
                        </tr>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .details-container { background-color: #ffffff; padding: 20px; border: 2px solid #ddd; border-radius: 4px; margin: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    .detail-group-title { font-weight: bold; color: #007bff; text-transform: uppercase; font-size: 1.1em; margin-bottom: 12px; border-bottom: 2px solid #007bff; display: inline-block; }
    .detail-section { margin-bottom: 12px; }
    .detail-title { font-weight: bold; color: #555; }
    .detail-content { color: #333; margin-top: 3px; }
    .separator-column { border-right: 1px solid #eee; }
    tr.shown { border-left: 5px solid #007bff; }
</style>

<script type="text/javascript">
    function format(d) {
        var status = parseInt(d.status);
        var statusTimeBox = '';

        if (status >= 2) {
            statusTimeBox = '<div class="detail-section" style="margin-top: 10px; padding: 10px; background: #f0f7ff; border-radius: 4px;">' +
                '<div class="detail-title"><i class="fas fa-clock text-info"></i> Final Status Time</div>' +
                '<div class="detail-content"><strong>' + (d.updatedAt || 'N/A') + '</strong></div>' +
                '</div>';
        }

        return '<div class="details-container">' +
            '<div class="row">' +
            /* Section 1: Applicant Contact */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Applicant Details</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user text-primary"></i> Name & Designation</div>' +
            '<div class="detail-content">' + d.applicant + '<br/><small>' + d.designation + '</small></div>' +
            '</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-phone-alt text-primary"></i> Contact Number</div>' +
            '<div class="detail-content">' + d.mobile + '</div>' +
            '</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-map-marker-alt text-primary"></i> Address During Leave</div>' +
            '<div class="detail-content" style="font-style: italic; background: #f9f9f9; padding: 5px; border: 1px dashed #ccc;">' + d.address + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 2: Application Details */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Application Info</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Applied Date</div>' +
            '<div class="detail-content">' + d.applied + '</div>' +
            '</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Station Leave?</div>' +
            '<div class="detail-content">' + d.stationLeave + '</div>' +
            '</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Reason</div>' +
            '<div class="detail-content">' + d.reason + '</div>' +
            '</div>' +
            '</div>' +

            /* Section 3: Approval Status */
            '<div class="col-md-4">' +
            '<div class="detail-group-title">Approval Chain</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Supervisor Remark</div>' +
            '<div class="detail-content"><small>' + d.supComment + '</small></div>' +
            '</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Approver Remark</div>' +
            '<div class="detail-content"><small>' + d.appComment + '</small></div>' +
            '</div>' +
            statusTimeBox +
            '</div>' +
            '</div>' +
            '</div>';
    }

    $(document).ready(function () {
        var table = $('#alternativeTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: [7] }]
        });

        $('#alternativeTable tbody').on('click', 'tr.leave-row', function () {
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
                row.child(format(tr.data())).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
    });
</script>