  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="http://saifpowertecltd.com/">Registro/</a>.</strong> All rights
    reserved.
  </footer>
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->

<script src="../vendor/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../vendor/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../vendor/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="../vendor/bower_components/raphael/raphael.min.js"></script>
<script src="../vendor/bower_components/morris.js/morris.min.js"></script>
<!-- daterangepicker -->
<script src="../vendor/bower_components/moment/min/moment.min.js"></script>
<script src="../vendor/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../vendor/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../vendor/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../vendor/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../vendor/bower_components/fastclick/lib/fastclick.js"></script>
<!-- DataTables -->
<script src="../vendor/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../vendor/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="../vendor/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->
<script src="../js/site_url.js"></script>
<script src="../js/sweetalert.js"></script>
<script src="../js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#visitor_queue_table_data").DataTable();
    });
    function set_resend_sms_queue_number(){
        $.ajax({
            url: baseUrl + "function/registration_queue_process.php?process_type=setResendSmsQueueNumber",
            type: 'POST',
            data: $("#setResendSmsQueueNumberForm").serialize(),
            dataType: 'JSON',
            success: function (response) {
                $('#success_message').show();
                $('#message').html(response.message);
            }
        });
    }

function visitor_event_exit_status(visitor_id){
    var q_status    =   $('#visitor_event_exit_status_'+visitor_id).prop('checked');
    var remarkTitle       =   (q_status ? 'Completed' : 'Pending');
    $.ajax({
        url: baseUrl + "function/registration_queue_process.php?process_type=updateVisitorQStatus",
        type: 'POST',
        dataType: 'json',
        data: 'visitor_id='+visitor_id+'&q_status='+q_status+'&remarks='+remarkTitle,
        success: function (response) {
                $('#success_message').show();
                $('#message').html(response.message);
                if(remarkTitle ==  "Completed"){
                    $("#visitor_completed_on_date_"+visitor_id).html(response.update_time);
                }else{
                    $("#visitor_completed_on_date_"+visitor_id).html("");
                }
        }
    });
}
function deleteVisitor(id) {
    swal({
        title: 'Confirmed?',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: 'No',
        closeOnConfirm: false
    },
            function () {
                $.ajax({
                    url: baseUrl + "function/registration_process.php?process_type=deleteVisitor",
                    type: 'POST',
                    data: 'id=' + id,
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.status == 'success') {
                            $('#visitor_queue_id_' + id).hide('slow');
                            swal("Delete complete", response.message, "success");
                            setTimeout(function () {
                                swal.close();
                            }, 1000);
                        }
                    }
                });
            });
}
</script>
</body>
</html>