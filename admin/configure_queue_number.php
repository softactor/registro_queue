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
                        <h2>Configure Queue Number</h2>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php 
                        $qresendSmsNumber   =   get_que_resend_sms_number();
                        ?>
                        <form class="form-inline" id="setResendSmsQueueNumberForm">
                            <div class="form-group">
                                <label for="email">Queue Number:</label>
                                <input type="email" class="form-control" id="queue_number" name="queue_number" value="<?php echo $qresendSmsNumber; ?>">
                            </div>
                            <button type="button" class="btn btn-primary" onclick="set_resend_sms_queue_number();">Set</button>
                        </form>
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
