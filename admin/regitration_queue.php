<?php include 'header.php'; ?>
<?php include 'top_sidebar.php'; ?>
<!-- Left side column. contains the logo and sidebar -->
<?php include 'left_sidebar.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <?php include '../operation_message.php'; ?>                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2>Registration Queue List</h2>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        $regisData = get_registration_queue_data();
                        if (isset($regisData) && !empty($regisData)) {
                            ?>
                            <div class="table-responsive">          
                                <table class="table table-bordered table-striped table-hover" id="visitor_queue_table_data">
                                    <thead>
                                        <tr>
                                            <th>Queue Number</th>
                                            <th>Name</th>
                                            <th>Mobile</th>
                                            <th>Registered On</th>
                                            <th>Completed On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($regisData as $regis) {
                                            ?>
                                            <tr id="visitor_queue_id_<?php echo $regis->id; ?>">
                                                <td style="font-weight: bold; font-size: 18px;"><?php echo $regis->queue_number; ?></td>
                                                <td><?php echo ucfirst($regis->name); ?></td>
                                                <td><?php echo $regis->mobile; ?></td>
                                                <td><?php echo human_format_date($regis->generated_at); ?></td>
                                                <td>
                                                    <span id="visitor_completed_on_date_<?php echo $regis->id; ?>">
                                                        <?php
                                                            echo (($regis->is_status == 1) ? human_format_date($regis->updated_at) : "");
                                                        ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <input class="is_barcode_toggle_class" onchange="visitor_event_exit_status('<?php echo $regis->id; ?>');" id="visitor_event_exit_status_<?php echo $regis->id ?>" type="checkbox" <?php if($regis->is_status){ echo 'checked'; } ?> data-toggle="toggle" data-on="Completed" data-off="Pending" data-onstyle="info" data-offstyle="danger">
                                                    <button type="button" class="btn btn-danger" onclick="deleteVisitor('<?php echo $regis->id; ?>');"><span class="fa fa-close">&nbsp;Del</span></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-info">
                                <strong>Info!</strong> No Data Found.
                            </div>
                        <?php } ?>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include 'footer.php'; ?>
