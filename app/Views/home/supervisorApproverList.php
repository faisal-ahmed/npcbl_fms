<section style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 3% 13% !important;">
        <div class="row">
            <div class="col-sm-8 h2">
                My Team
            </div>
        </div>

        <hr/>

        <div class="col-sm-12">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="status alert alert-success" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold;">
                    <?php echo session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error) && $error) : ?>
                <div class="status alert alert-danger" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold;">
                    <?php echo is_array($error) ? implode('<br>', $error) : $error; ?>
                </div>
            <?php endif; ?>
        </div>

        <h3>Pending Confirmation</h3>
        <div class="row wow fadeInUp">
            <div class="col-sm-12">
                <table id="masterTable1" class="table table-active table-bordered table-hover" style="width:100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">Sl No.</th>
                        <th>Employee Name</th>
                        <th>Payroll ID</th>
                        <th>Designation (NPCBL)</th>
                        <th class="text-center">Remaining Leave</th>
                        <th>Supervisor</th>
                        <th>Approver</th>
                        <th class="text-center" style="width: 50px;">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)): ?>
                        <?php $i = 1; foreach ($records as $row): ?>
                            <?php if ($row['emp_confirmation'] == '0'): ?>
                                <tr class="main-master-row" style="cursor: pointer; border-bottom: 1px solid #ddd;">
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><strong><?php echo esc($row['emp_name']); ?></strong></td>
                                    <td><?php echo esc($row['emp_payroll']); ?></td>
                                    <td><?php echo esc($row['emp_desig']); ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info"><?php echo $row['remaining_leave'] ?? 0; ?> Days</span>
                                        <br><small>(<?php echo $row['leave_year'] ?? date('Y'); ?>)</small>
                                    </td>
                                    <td><?php echo esc($row['supervisor_name'] ?? 'Not Assigned'); ?></td>
                                    <td><?php echo esc($row['approver_name'] ?? 'Not Assigned'); ?></td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="toggle-details" style="font-size: 1.2em; color: #007aa7;">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                        <div class="child-row-content" style="display: none;">
                                            <div class="row p-2" style="background: #fdfdfd; border-left: 4px solid #007aa7;">
                                                <div class="<?php echo (isset($type) && $type == 'all') ? 'col-md-4' : 'col-md-3'; ?> text-left">
                                                    <h4 class="mb-1 text-primary fas fa-user-tie"> <strong style="text-decoration: underline;">Employee Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em; word-break: break-all;">
                                                        <div class="col-sm-4 text-center p-0">
                                                            <img id="prev_img" src="<?php echo base_url($row['emp_profile_pic'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>BioMetric ID: <?php echo esc($row['emp_bio']); ?></p>
                                                            <p>Email: <?php echo esc($row['emp_email']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-left">
                                                    <h4 class="mb-1 text-primary fas fa-user-check"> <strong style="text-decoration: underline;">Supervisor Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em;">
                                                        <div class="col-sm-4 text-center p-0">
                                                            <img id="prev_img" src="<?php echo base_url($row['supervisor_profile_picture'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="images/default.png">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>Name: <?php echo esc($row['supervisor_name'] ?? 'N/A'); ?></p>
                                                            <p>Payroll: <?php echo esc($row['supervisor_payroll'] ?? 'N/A'); ?></p>
                                                            <p>BioMetric ID: <?php echo esc($row['supervisor_bio'] ?? 'N/A'); ?></p>
                                                            <p>Designation: <?php echo esc($row['supervisor_desig'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="<?php echo (isset($type) && $type == 'all') ? 'col-md-4' : 'col-md-3'; ?> text-left p-0">
                                                    <h4 class="mb-1 text-primary fas fa-user-check p-0"> <strong style="text-decoration: underline;">Approver Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em;">
                                                        <div class="col-sm-4 text-center">
                                                            <img id="prev_img" src="<?php echo base_url($row['approver_profile_picture'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="images/default.png">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>Name: <?php echo esc($row['approver_name'] ?? 'N/A'); ?></p>
                                                            <p>Payroll: <?php echo esc($row['approver_payroll'] ?? 'N/A'); ?></p>
                                                            <p>BioMetric ID: <?php echo esc($row['approver_bio'] ?? 'N/A'); ?></p>
                                                            <p>Designation: <?php echo esc($row['approver_desig'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if (!(isset($type) && $type == 'all')): ?>
                                                    <div class="col-md-2 text-center p-0">
                                                        <h4 class="mb-1 text-primary fas fa-user-tie"> <strong style="text-decoration: underline;">Approval</strong></h4>

                                                        <form method="post" action="">
                                                            <div id="form_csrf_container"><?php echo csrf_field(); ?></div>
                                                            <input type="hidden" name="user_id" value="<?php echo isset($row['emp_id']) ? htmlspecialchars($row['emp_id']) : ''; ?>">

                                                            <div class="d-flex flex-column align-items-center gap-3" style="line-height: 1; font-size: 1.1em;">
                                                                <button type="submit" name="action_flag" value="confirm" class="btn btn-success btn-lg px-2 w-100" onclick="return confirm('Are you sure you want to approve this employee?');">
                                                                    <i class="fas fa-check-circle"></i> Approve
                                                                </button>

                                                                <button type="submit" name="action_flag" value="cancel" class="btn btn-danger btn-lg px-2 w-100" onclick="return confirm('Are you sure you want to cancel this request?');">
                                                                    <i class="fas fa-times-circle"></i> Reject
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr/>
        <h3>Confirmed Personnel</h3>
        <div class="row wow fadeInUp">
            <div class="col-sm-12">
                <table id="masterTable2" class="table table-active table-bordered table-hover" style="width:100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">Sl No.</th>
                        <th>Employee Name</th>
                        <th>Payroll ID</th>
                        <th>Designation (NPCBL)</th>
                        <th class="text-center">Remaining Leave</th>
                        <th>Supervisor</th>
                        <th>Approver</th>
                        <th class="text-center" style="width: 50px;">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)): ?>
                        <?php $i = 1; foreach ($records as $row): ?>
                            <?php if ($row['emp_confirmation'] != '0'): ?>
                                <tr class="main-master-row" style="cursor: pointer; border-bottom: 1px solid #ddd;">
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><strong><?php echo esc($row['emp_name']); ?></strong></td>
                                    <td><?php echo esc($row['emp_payroll']); ?></td>
                                    <td><?php echo esc($row['emp_desig']); ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info"><?php echo $row['remaining_leave'] ?? 0; ?> Days</span>
                                        <br><small>(<?php echo $row['leave_year'] ?? date('Y'); ?>)</small>
                                    </td>
                                    <td><?php echo esc($row['supervisor_name'] ?? 'Not Assigned'); ?></td>
                                    <td><?php echo esc($row['approver_name'] ?? 'Not Assigned'); ?></td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="toggle-details" style="font-size: 1.2em; color: #007aa7;">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                        <div class="child-row-content" style="display: none;">
                                            <div class="row p-2" style="background: #fdfdfd; border-left: 4px solid #007aa7;">
                                                <div class="col-md-4 text-left">
                                                    <h4 class="mb-1 text-primary fas fa-user-tie"> <strong style="text-decoration: underline;">Employee Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em; word-break: break-all;">
                                                        <div class="col-sm-4 text-center p-0">
                                                            <img id="prev_img" src="<?php echo base_url($row['emp_profile_pic'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>BioMetric ID: <?php echo esc($row['emp_bio']); ?></p>
                                                            <p>Email: <?php echo esc($row['emp_email']); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-left">
                                                    <h4 class="mb-1 text-primary fas fa-user-check"> <strong style="text-decoration: underline;">Supervisor Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em;">
                                                        <div class="col-sm-4 text-center p-0">
                                                            <img id="prev_img" src="<?php echo base_url($row['supervisor_profile_picture'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="images/default.png">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>Name: <?php echo esc($row['supervisor_name'] ?? 'N/A'); ?></p>
                                                            <p>Payroll: <?php echo esc($row['supervisor_payroll'] ?? 'N/A'); ?></p>
                                                            <p>BioMetric ID: <?php echo esc($row['supervisor_bio'] ?? 'N/A'); ?></p>
                                                            <p>Designation: <?php echo esc($row['supervisor_desig'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-left">
                                                    <h4 class="mb-1 text-primary fas fa-user-check p-0"> <strong style="text-decoration: underline;">Approver Details</strong></h4>
                                                    <div class="row" style="line-height: 1; font-size: 1.1em;">
                                                        <div class="col-sm-4 text-center">
                                                            <img id="prev_img" src="<?php echo base_url($row['approver_profile_picture'] ?? "images/default.png"); ?>" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: fill; border: 3px solid #28D094;" alt="images/default.png">
                                                        </div>
                                                        <div class="col-sm-8 font-weight-bold p-0">
                                                            <p>Name: <?php echo esc($row['approver_name'] ?? 'N/A'); ?></p>
                                                            <p>Payroll: <?php echo esc($row['approver_payroll'] ?? 'N/A'); ?></p>
                                                            <p>BioMetric ID: <?php echo esc($row['approver_bio'] ?? 'N/A'); ?></p>
                                                            <p>Designation: <?php echo esc($row['approver_desig'] ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .main-master-row:hover { background-color: #f1f4f7 !important; }
    .toggle-details i { transition: transform 0.3s ease; }
    .toggle-details.active i { transform: rotate(45deg); color: #d9534f; }
    #masterTable1 thead th { border: none; padding: 12px; vertical-align: middle; background-color: #2c3e50; color: white; }
    #masterTable2 thead th { border: none; padding: 12px; vertical-align: middle; background-color: #2c3e50; color: white; }
    .badge-info { background-color: #17a2b8; }
    .dataTables_filter { text-align: right; margin-bottom: 10px; }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#exportMasterData').on('click', function(e) {
            e.preventDefault();
            $('#exportForm').submit();
        });

        var table1 = $('#masterTable1').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        var table2 = $('#masterTable2').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        function toggleMasterRow(trElement, targetTable) {
            var tr = $(trElement).closest('tr.main-master-row');
            var row = targetTable.row(tr);
            var icon = tr.find('.toggle-details');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('active').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                tr.css('background-color', '');
            } else {
                targetTable.rows().every(function() {
                    if (this.child.isShown()) {
                        this.child.hide();
                        $(this.node()).removeClass('shown').css('background-color', '');
                        $(this.node()).find('.toggle-details').removeClass('active')
                            .find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                    }
                });
                var content = tr.find('.child-row-content').html();
                row.child(content).show();
                tr.addClass('shown');
                icon.addClass('active').find('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
                tr.css('background-color', '#f1f4f7');
            }
        }

        $('#masterTable1 tbody').on('click', 'tr.main-master-row', function (e) {
            if ($(e.target).closest('.btn-info, a:not(.toggle-details)').length) return;
            if ($(e.target).closest('.toggle-details').length) {
                e.preventDefault();
            }
            toggleMasterRow(this, table1);
        });

        $('#masterTable2 tbody').on('click', 'tr.main-master-row', function (e) {
            if ($(e.target).closest('.btn-info, a:not(.toggle-details)').length) return;
            if ($(e.target).closest('.toggle-details').length) {
                e.preventDefault();
            }
            toggleMasterRow(this, table2);
        });
    });
</script>