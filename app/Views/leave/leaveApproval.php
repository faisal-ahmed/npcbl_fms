<section id="leave-approval-page" style="padding: 50px 0;">
    <div class="container-fluid" style="padding: 0 3% 0 10% !important;">
        <div class="row">
            <div class="col-sm-12">
                <h2 class="text-center"><i class="fas fa-user-check text-primary"></i> Pending Approvals</h2>
            </div>
        </div>

        <hr/>

        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success alert-dismissible" role="alert" style="border: 2px solid #28D094; text-align: center; font-size: 1.2em; font-weight: bold; border-left: 5px solid #28D094;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger alert-dismissible" role="alert" style="border: 2px solid #c52d2f; text-align: center; font-size: 1.2em; font-weight: bold; border-left: 5px solid #e74c3c;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="row dataTable">
            <div class="col-sm-12">
                <table id="approvalTable" class="table table-bordered table-hover" style="width:100%">
                    <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th style="width: 50px;" class="text-center">Sl</th>
                        <th>Applicant Details</th>
                        <th>Leave Type</th>
                        <th>Period</th>
                        <th class="text-center">Days</th>
                        <th>Role Required</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; foreach($pendingLeaves as $row):
                        $isSup = ($row['np_hrm_tbl_clm_supervisor_id'] == session()->get('user_id') && $row['np_hrm_tbl_clm_status'] == 0);
                        $roleLabel = $isSup ? 'Supervisor' : 'Approver';
                        $roleKey = $isSup ? 'supervisor' : 'approver';
                        $roleBg = $isSup ? '#FF9149' : '#28D094';

                        $supRemarks = (!empty($row['np_hrm_tbl_clm_supervisor_remarks'])) ? $row['np_hrm_tbl_clm_supervisor_remarks'] : 'No Comment';

                        // Station Leave Logic
                        $slVal = $row['np_hrm_tbl_clm_station_leave'] ?? 1;
                        $slText = ($slVal == 1) ? "Yes, leaving the station." : "No, staying at the station.";

                        // Balance Calculation
                        $allocated = (int)($row['np_hrm_tbl_clm_allocated_days'] ?? 0);
                        $taken = (int)($row['np_hrm_tbl_clm_taken_days'] ?? 0);
                        $remaining = $allocated - $taken;
                        ?>
                        <tr class="leave-row" style="cursor: pointer;"
                            data-reqid="<?php echo $row['np_hrm_tbl_clm_leave_request_id']; ?>"
                            data-role="<?php echo $roleKey; ?>"
                            data-name="<?php echo esc($row['applicant_name']); ?>"
                            data-reason="<?php echo esc($row['np_hrm_tbl_clm_reason']); ?>"
                            data-sup-remarks="<?php echo esc($supRemarks); ?>"
                            data-station-leave="<?php echo esc($slText); ?>"
                            data-address="<?php echo esc($row['np_hrm_tbl_clm_address_in_leave'] ?? 'N/A'); ?>"
                            data-balance="<?php echo $remaining; ?>"
                            data-leavetype="<?php echo esc($row['type_name']); ?>"
                            data-alt-name="<?php echo esc($row['alternate_name'] ?? 'N/A'); ?>"
                            data-alt-mobile="<?php echo esc($row['alternate_mobile'] ?? 'N/A'); ?>"
                            data-alt-designation="<?php echo esc($row['alternate_designation_npcbl'] ?? 'N/A'); ?>"
                        >
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td>
                                <strong><?php echo esc($row['applicant_name']); ?></strong><br/>
                                <small class="text-muted"><?php echo esc($row['applicant_designation']); ?></small><br/>
                                <small><i class="fas fa-envelope text-primary"></i> <?php echo esc($row['applicant_email'] ?? 'N/A'); ?></small><br/>
                                <small><i class="fas fa-phone text-primary"></i> <?php echo esc($row['applicant_mobile'] ?? 'N/A'); ?></small>
                            </td>
                            <td><strong><?php echo esc($row['type_name']); ?></strong></td>
                            <td>
                                <?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_start_date'])); ?> - <br/>
                                <?php echo date('d M, Y', strtotime($row['np_hrm_tbl_clm_end_date'])); ?>
                            </td>
                            <td class="text-center"><?php echo $row['np_hrm_tbl_clm_total_days']; ?></td>
                            <td>
                                <span style="background-color: <?php echo $roleBg; ?>; color: #fff; padding: 6px 15px; border-radius: 3px; font-weight: bold; font-size: 12px; display: inline-block; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                    <?php echo strtoupper($roleLabel); ?>
                                </span>
                            </td>
                            <td class="text-center"><i class="fas fa-plus toggle-icon"></i></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .details-container {
        background-color: #ffffff;
        padding: 25px;
        border: 2px solid #eaeaea;
        border-left: 6px solid #4178b5;
        border-radius: 4px;
        margin: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .required-star { color: #ff0000; font-weight: bold; }

    .btn-action-custom {
        padding: 18px 10px !important;
        font-size: 1em !important;
        font-weight: 800 !important;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-action-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }

    .info-card {
        background: #f8f9fa;
        padding: 12px;
        border-radius: 5px;
        border: 1px solid #eee;
        height: 100%;
        font-size: 1.2em;
    }

    tr.shown { border-left: 5px solid #4178b5; }
</style>

<script type="text/javascript">
    function format(d) {
        var isSup = (d.role === 'supervisor');
        var actionBtnClass = isSup ? 'btn-warning' : 'btn-success';
        var actionBtnText  = isSup ? 'RECOMMEND' : 'APPROVE';
        var actionBtnIcon  = isSup ? 'fa-share-square' : 'fa-check-circle';

        var supervisorBox = '';
        if (d.role === 'approver') {
            supervisorBox = '<div style="margin-top: 15px; background: #fffdf0; padding: 10px; border: 1px solid #ffeeba; border-radius: 5px;">' +
                '<strong><i class="fas fa-user-check"></i> Supervisor\'s Recommendation:</strong>' +
                '<p style="margin: 5px 0 0 0; font-style: italic; color: #856404;">"' + d.supRemarks + '"</p>' +
                '</div>';
        }

        return '<div class="details-container">' +
            '<form method="post" action="<?php echo base_url('leave/leave-approval'); ?>" onsubmit="return handleFormSubmit(this, \''+d.name+'\')">' +
            '<?php echo csrf_field(); ?>' +
            '<input type="hidden" name="request_id" value="'+d.reqid+'">' +
            '<input type="hidden" name="role" value="'+d.role+'">' +
            '<input type="hidden" name="action" class="action-type" value="approve">' +

            '<div class="row">' +
            /* Left Column */
            '<div class="col-md-7" style="border-right: 2px solid #f4f4f4; padding-right: 30px;">' +
            '<h5><strong style="color: #444;"><i class="fas fa-info-circle text-primary"></i> REASON FOR LEAVE:</strong></h5>' +
            '<p style="background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 5px; color: #555; font-size: 1.1em; line-height: 1.6;">'+d.reason+'</p>' +

            supervisorBox +

            '<div class="form-group" style="margin-top: 20px;">' +
            '<label style="font-size: 1.1em;"><strong>YOUR REMARKS</strong> <span class="required-star">*</span></label>' +
            '<textarea name="comment" class="form-control" rows="4" required style="border: 1px solid #ccc; font-size: 1.1em;" placeholder="Type your observation or comments..."></textarea>' +
            '</div>' +

            '<div class="row" style="margin-top: 20px;">' +
            '<div class="col-sm-6">' +
            '<button type="button" onclick="submitAction(this, \'approve\')" class="btn ' + actionBtnClass + ' btn-block btn-action-custom">' +
            '<i class="fas ' + actionBtnIcon + '"></i> ' + actionBtnText +
            '</button>' +
            '</div>' +
            '<div class="col-sm-6">' +
            '<button type="button" onclick="submitAction(this, \'reject\')" class="btn btn-danger btn-block btn-action-custom">' +
            '<i class="fas fa-times-circle"></i> REJECT' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +

            /* Right Column: Info Cards */
            '<div class="col-md-5" style="padding-left: 30px;">' +
            '<h5 style="margin-bottom: 15px;"><strong style="color: #444;"><i class="fas fa-database text-secondary"></i> APPLICANT REFERENCE:</strong></h5>' +
            '<div class="row">' +
            '<div class="col-sm-12" style="margin-bottom: 12px;">' +
            '<div class="info-card" style="border-left: 4px solid #28D094;">' +
            '<strong><i class="fas fa-wallet text-success"></i> Current Leave Balance:</strong><br/>' +
            '<span style="font-size: 1.1em; color: #28a745; font-weight: bold;">' + d.balance + ' Days</span> (' + d.leavetype + ')' +
            '</div>' +
            '</div>' +
            '<div class="col-sm-12" style="margin-bottom: 12px;">' +
            '<div class="info-card" style="border-left: 4px solid #1B65D3;">' +
            '<strong><i class="fas fa-house-user text-primary"></i> Station Leave Status:</strong><br/>' + d.stationLeave +
            '</div>' +
            '</div>' +

            /* NEW: Alternative Informed Person Card */
            '<div class="col-sm-12" style="margin-bottom: 12px;">' +
            '<div class="info-card" style="border-left: 4px solid #f39c12;">' +
            '<strong><i class="fas fa-user-friends text-warning"></i> Alternative Informed Person:</strong><br/>' +
            '<span style="font-size: 0.9em;">' + d.altName + ' (' + d.altDesignation + ')</span><br/>' +
            '<small><i class="fas fa-phone-alt"></i> ' + d.altMobile + '</small>' +
            '</div>' +
            '</div>' +

            '<div class="col-sm-12">' +
            '<div class="info-card" style="border-left: 4px solid #FF4961;">' +
            '<strong><i class="fas fa-map-marker-alt text-danger"></i> Contact Address While in Leave:</strong><br/>' +
            '<small style="color: #666; font-style: italic;">' + d.address + '</small>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</form>' +
            '</div>';
    }

    function submitAction(btn, type) {
        var form = $(btn).closest('form');
        form.find('.action-type').val(type);
        form.submit();
    }

    function handleFormSubmit(form, applicantName) {
        var action = $(form).find('.action-type').val();
        var comment = $(form).find('textarea[name="comment"]').val();

        if(!comment || comment.trim().length < 2) {
            alert("Mandatory: Please provide a valid remark (minimum 5 characters).");
            return false;
        }

        var isApprove = (action === 'approve');
        var role = $(form).find('input[name="role"]').val();

        var confirmActionText = isApprove ? (role === 'supervisor' ? 'RECOMMENDATION' : 'APPROVAL') : 'REJECTION';
        var msg = isApprove
            ? "Confirm " + confirmActionText + " for " + applicantName + "?"
            : "⚠️ CRITICAL ACTION: You are rejecting " + applicantName + "\'s request. Proceed?";

        return confirm(msg);
    }

    $(document).ready(function() {
        var table = $('#approvalTable').DataTable({
            pageLength: 25,
            ordering: true,
            searching: true
        });

        $('#approvalTable tbody').on('click', 'tr.leave-row', function () {
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