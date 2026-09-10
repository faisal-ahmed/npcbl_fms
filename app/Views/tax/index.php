<section style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 3% 13% !important;">
        <div class="row">
            <div class="col-sm-8 h2">
                <?php echo (isset($submenu) && $submenu == 'allTax') ? 'All Employee\'s Tax Submissions' : 'My Tax Return History'; ?>
            </div>

            <?php if (!(isset($submenu) && $submenu == 'allTax')) { ?>
                <div class="col-sm-4 text-right">
                    <a href="<?php echo base_url('tax/add-tax-return'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Add New Record
                    </a>
                </div>
            <?php } else { ?>
                <div class="col-sm-4 text-right">
                    <button id="exportTaxData" class="btn btn-success btn-lg">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </button>

                    <form id="exportForm" action="<?php echo base_url('tax/export-excel'); ?>" method="post" style="display:none;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="export_type" value="<?php echo $submenu; ?>">
                    </form>
                </div>
            <?php } ?>
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

        <div class="row wow fadeInUp">
            <div class="col-sm-12">
                <table id="taxTable" class="table table-active table-bordered table-hover" style="width:100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">Sl No.</th>
                        <?php if (isset($submenu) && $submenu == 'allTax'): ?>
                            <th>Employee Name</th>
                            <th>Payroll ID</th>
                            <th>Designation (NPCBL)</th>
                            <th>Contact Number</th>
                        <?php endif; ?>
                        <th class="text-center">Tax Year</th>
                        <th>TIN Number</th>
                        <th>Submission Date</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 50px;">Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)): ?>
                        <?php $i = 1; foreach ($records as $row): ?>
                            <tr class="main-tax-row" style="cursor: pointer; border-bottom: 1px solid #ddd;">
                                <td class="text-center"><?php echo $i++; ?></td>
                                <?php if (isset($submenu) && $submenu == 'allTax'): ?>
                                    <td><strong><?php echo esc($row['np_hrm_tbl_clm_name_en']); ?></strong></td>
                                    <td><?php echo esc($row['np_hrm_tbl_clm_payroll_id']); ?></td>
                                    <td><?php echo esc($row['np_hrm_tbl_clm_designation_npcbl']); ?></td>
                                    <td><?php echo esc($row['np_hrm_tbl_clm_contact_number']); ?></td>
                                <?php endif; ?>
                                <td class="text-center"><strong><?php echo esc($row['np_hrm_tbl_clm_tax_year']); ?></strong></td>
                                <td><?php echo esc($row['np_hrm_tbl_clm_tax_tin']); ?></td>
                                <td><?php echo $row['np_hrm_tbl_clm_tax_return_submission_date']; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-success">Submitted</span>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0);" class="toggle-details" style="font-size: 1.2em; color: #007aa7;">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                    <div class="child-row-content" style="display: none;">
                                        <div class="row p-1" style="background: #fdfdfd; border-left: 4px solid #007aa7;">
                                            <div class="col-md-4 text-left">
                                                <p><strong>Circle/Zone:</strong> <?php echo esc($row['np_hrm_tbl_clm_tax_circle']); ?></p>
                                                <p><strong>Serial No:</strong> <?php echo esc($row['np_hrm_tbl_clm_tax_return_serial_no']); ?></p>
                                            </div>
                                            <div class="col-md-5 text-left">
                                                <p><strong>Comments/Remarks:</strong></p>
                                                <div class="well well-sm" style="background: #fff; min-height: 50px; border: 1px solid #eee; padding: 10px;">
                                                    <?php echo !empty($row['np_hrm_tbl_clm_tax_comments']) ? $row['np_hrm_tbl_clm_tax_comments'] : '<span class="text-muted">No additional comments provided.</span>'; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <p><strong>Last Updated:</strong><br/><small><?php echo date('d M, Y h:i A', $row['np_hrm_tbl_clm_tax_updated_at']); ?></small></p>
                                                <?php if (!(isset($submenu) && $submenu == 'allTax')): ?>
                                                    <a href="<?php echo base_url('tax/update-tax-record/' . $row['np_hrm_tbl_clm_tax_id']); ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i> Edit Record
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
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
    .main-tax-row:hover { background-color: #f1f4f7 !important; }
    .toggle-details i { transition: transform 0.3s ease; }
    .toggle-details.active i { transform: rotate(45deg); color: #d9534f; }
    #taxTable thead th { border: none; padding: 12px; vertical-align: middle; background-color: #2c3e50; color: white; }
    .badge-success { background-color: #28a745; }
    .dataTables_filter { text-align: right; margin-bottom: 10px; }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#exportTaxData').on('click', function(e) {
            e.preventDefault();
            $('#exportForm').submit();
        });

        var table = $('#taxTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ordering: true,
            searching: true,
            scrollX: true,
            columnDefs: [{ orderable: false, targets: -1 }]
        });

        function toggleTaxRow(trElement) {
            var tr = $(trElement).closest('tr.main-tax-row');
            var row = table.row(tr);
            var icon = tr.find('.toggle-details');

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('active').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
                tr.css('background-color', '');
            } else {
                table.rows().every(function() {
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

        $('#taxTable tbody').on('click', 'tr.main-tax-row', function (e) {
            if ($(e.target).closest('.btn-info, a:not(.toggle-details)').length) return;
            if ($(e.target).closest('.toggle-details').length) {
                e.preventDefault();
            }

            toggleTaxRow(this);
        });
    });
</script>