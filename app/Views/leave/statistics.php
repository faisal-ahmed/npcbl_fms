<?php
/**
 * PHP Helper function for rendering rows with all required data attributes
 */
function renderLeaveRow($row, $sl, $category) {
    $status = $row['np_hrm_tbl_clm_status'] ?? 2; // Defaulting to approved for this stats page
    $stationLeaveVal = $row['np_hrm_tbl_clm_station_leave'] ?? 1;
    $stationLeaveText = ($stationLeaveVal == 1) ? "Yes, I will leave the station." : "No, I will stay at the station.";

    // Background color based on category (Upcoming vs Enjoyed)
    $bgColor = ($category == 'upcoming') ? 'background-color: #f0fff4 !important;' : 'background-color: #ffffff !important;';

    ob_start(); ?>
    <tr class="leave-row" style="cursor: pointer; <?php echo $bgColor; ?>"
        data-reason="<?php echo esc($row['np_hrm_tbl_clm_reason']); ?>"
        data-sup-comment="<?php echo esc($row['np_hrm_tbl_clm_supervisor_remarks'] ?? 'No comments.'); ?>"
        data-sup-date="<?php echo ($row['np_hrm_tbl_clm_supervisor_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_supervisor_action_time']) : 'N/A'; ?>"
        data-app-comment="<?php echo esc($row['np_hrm_tbl_clm_approver_remarks'] ?? 'No comments.'); ?>"
        data-app-date="<?php echo ($row['np_hrm_tbl_clm_approver_action_time']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_approver_action_time']) : 'N/A'; ?>"
        data-applied="<?php echo date('d M, Y h:i A', $row['np_hrm_tbl_clm_created_at']); ?>"
        data-station-leave="<?php echo esc($stationLeaveText); ?>"
        data-address="<?php echo esc($row['np_hrm_tbl_clm_address_in_leave'] ?? 'N/A'); ?>"
        data-updated-at="<?php echo ($row['np_hrm_tbl_clm_updated_at']) ? date('d M, Y h:i A', $row['np_hrm_tbl_clm_updated_at']) : 'N/A'; ?>"
        data-alt-name="<?php echo esc($row['alternate_name'] ?? 'N/A'); ?>"
        data-alt-mobile="<?php echo esc($row['alternate_mobile'] ?? 'N/A'); ?>"
        data-alt-designation="<?php echo esc($row['alternate_designation_npcbl'] ?? 'N/A'); ?>"
        data-status="<?php echo $status; ?>"
    >
        <td class="text-center"><?php echo $sl; ?></td>
        <td><strong><?php echo esc($row['leave_type_name']); ?></strong></td>
        <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_start_date'])); ?></td>
        <td><?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_end_date'])); ?></td>
        <td class="text-center"><?php echo $row['np_hrm_tbl_clm_total_days']; ?></td>
        <td>
            <?php echo esc($row['alternate_name'] ?? 'N/A'); ?>
            <?php if(!empty($row['alternate_mobile'])): ?>
                <br/><small><i class="fas fa-phone-alt"></i> <?php echo esc($row['alternate_mobile']); ?></small>
            <?php endif; ?>
        </td>
        <td class="text-center"><i class="fas fa-plus toggle-icon"></i></td>
    </tr>
    <?php return ob_get_clean();
} ?>

<section id="leave-statistics-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-6">
                <h2 class="text-left"><i class="fas fa-chart-line text-primary"></i> Approved Leave Statistics - <?php echo $selectedYear; ?></h2>
            </div>
            <div class="col-sm-6 text-right">
                <div style="display: inline-block; vertical-align: middle; margin-right: 15px;">
                    <a href="<?php echo base_url('leave/export_csv?year=' . $selectedYear); ?>" class="btn btn-success">
                        <i class="fas fa-file-excel"></i>&nbsp;&nbsp;Download Report
                    </a>
                </div>
                <form method="get" action="<?php echo base_url('leave/statistics'); ?>" class="form-inline" style="display: inline-block; vertical-align: middle;">
                    <div class="form-group">
                        <label for="year" style="margin-right: 10px; font-weight: bold;">Year: </label>
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

        <div class="row mb-5">
            <div class="col-sm-12">
                <h3 class="text-success" style="margin-bottom: 20px;"><i class="fas fa-calendar-check"></i> Upcoming & Current Leaves</h3>
                <table id="upcomingTable" class="table table-bordered table-hover statsTable" style="width:100%">
                    <thead>
                    <tr style="background-color: #eafff5;">
                        <th class="text-center">Sl</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Days</th>
                        <th>Alternate Person</th>
                        <th class="text-center">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($upcomingLeaves)): $i = 1; foreach ($upcomingLeaves as $row): ?>
                        <?php echo renderLeaveRow($row, $i++, 'upcoming'); ?>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-sm-12">
                <h3 class="text-muted" style="margin-bottom: 20px;"><i class="fas fa-history"></i> Previously Enjoyed Leaves</h3>
                <table id="enjoyedTable" class="table table-bordered table-hover statsTable" style="width:100%">
                    <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th class="text-center">Sl</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th class="text-center">Days</th>
                        <th>Alternate Person</th>
                        <th class="text-center">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($enjoyedLeaves)): $i = 1; foreach ($enjoyedLeaves as $row): ?>
                        <?php echo renderLeaveRow($row, $i++, 'enjoyed'); ?>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .details-container { background-color: #ffffff; padding: 20px; border: 1px solid #ddd; border-left: 5px solid #007bff; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin: 5px; }
    .detail-group-title { font-weight: bold; color: #007bff; text-transform: uppercase; font-size: 0.9em; margin-bottom: 10px; border-bottom: 2px solid #007bff; display: inline-block; }
    .detail-section { margin-bottom: 10px; }
    .detail-title { font-weight: bold; color: #555; font-size: 0.9em; }
    .detail-content { color: #333; font-size: 0.95em; line-height: 1.4; }
    .separator-column { border-right: 1px solid #eee; }
    .dataTables_filter input { border: 1px solid #ccc; padding: 5px; border-radius: 4px; }
    tr.shown { border-left: 5px solid #007bff; }
</style>

<script type="text/javascript">
    function format(d) {
        return '<div class="details-container">' +
            '<div class="row">' +
            /* Section 1: Chain */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Approval Chain & Comments</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-check text-primary"></i> Supervisor\'s Comments</div>' +
            '<div class="detail-content">' + (d.supComment || 'No comments.') + '</div>' +
            '<div class="small text-muted">Action Date: ' + (d.supDate || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-user-shield text-primary"></i> Approver\'s Comments</div>' +
            '<div class="detail-content">' + (d.appComment || 'No comments.') + '</div>' +
            '<div class="small text-muted">Action Date: ' + (d.appDate || 'N/A') + '</div>' +
            '</div>' +
            '</div>' +
            /* Section 2: Details */
            '<div class="col-md-4 separator-column">' +
            '<div class="detail-group-title">Application Details</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Applied On</div>' +
            '<div class="detail-content">' + (d.applied || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Station Leave Status</div>' +
            '<div class="detail-content">' + (d.stationLeave || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title">Reason for Leave</div>' +
            '<div class="detail-content">' + (d.reason || 'N/A') + '</div>' +
            '</div>' +
            '</div>' +
            /* Section 3: Contact */
            '<div class="col-md-4">' +
            '<div class="detail-group-title">Contact & Alternate Person</div>' +
            '<div class="detail-section">' +
            '<div class="detail-title"><i class="fas fa-map-marker-alt text-primary"></i> Address During Leave</div>' +
            '<div class="detail-content" style="background: #f9f9f9; padding: 8px; border-radius: 4px; border: 1px dashed #ccc;">' + (d.address || 'N/A') + '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="detail-section">' +
            '<div class="detail-title text-warning"><i class="fas fa-user-friends"></i> Alternate Informed Person</div>' +
            '<div class="detail-content">' +
            '<strong>' + d.altName + '</strong><br/>' +
            '<small>' + (d.altDesignation || '') + '</small><br/>' +
            '<i class="fas fa-phone-alt"></i> ' + d.altMobile +
            '</div>' +
            '</div>' +
            '<hr/>' +
            '<div class="text-info"><strong>Final Approval Time:</strong><br/>' + (d.updatedAt || 'N/A') + '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    }

    $(document).ready(function () {
        // Initialize DataTables
        var tables = $('.statsTable').DataTable({
            "pageLength": 10,
            "searching": true,
            "ordering": true,
            "info": true,
            "language": {
                "search": "<strong>Quick Search:</strong> "
            }
        });

        $('.statsTable tbody').on('click', 'tr.leave-row', function () {
            var currentTable = $(this).closest('table').DataTable();
            var tr = $(this);
            var row = currentTable.row(tr);
            var icon = tr.find('.toggle-icon');

            if (row.child.isShown()) {
                // If clicking the already open row, just close it
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('fa-minus').addClass('fa-plus');
            } else {
                // 1. Close ALL open rows in ALL tables first
                $('.statsTable').each(function() {
                    var tableInstance = $(this).DataTable();
                    tableInstance.rows().every(function() {
                        if (this.child.isShown()) {
                            this.child.hide();
                            $(this.node()).removeClass('shown');
                            $(this.node()).find('.toggle-icon').removeClass('fa-minus').addClass('fa-plus');
                        }
                    });
                });

                // 2. Open the clicked row
                row.child(format(tr.data())).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
    });
</script>