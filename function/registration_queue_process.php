<?php

if(isset($_GET['process_type']) && $_GET['process_type'] == 'updateVisitorQStatus'){
    session_start();
    date_default_timezone_set('Asia/Singapore');
    include '../connection/connect.php';
    include '../helper/utilities.php';
    $table          =   "regis_info";
    $visitor_id     =   $_POST['visitor_id'];
    $q_status       =   $_POST['q_status'];
    $remarks        =   $_POST['remarks'];
    $update_time    =   date("Y-m-d H:i:s");
    $regis_info['remarks']      =   $remarks;
    $regis_info['is_status']    =   (isset($remarks) && $remarks == 'Completed' ? 1 : 0);
    $regis_info['updated_at']   =   $update_time;
    $where['id']                =   $visitor_id;
    updateData($table, $regis_info, $where);
    $feedback   =   [
        'update_time'   => human_format_date($update_time),
        'status'        =>  'success',
        'message'       =>  "Data have been successfully updated."
    ];
    
    echo json_encode($feedback);
}
if(isset($_GET['process_type']) && $_GET['process_type'] == 'setResendSmsQueueNumber'){
    session_start();
    date_default_timezone_set('Asia/Singapore');
    include '../connection/connect.php';
    include '../helper/utilities.php';
    $table      =   "configure_queue_number";
    $dataParam['number']     =   $_POST['queue_number'];
    $passsql    = "SELECT * FROM $table";
    $result = $conn->query($passsql);
    if ($result->num_rows > 0) {
        // update operation
        $data   =   $result->fetch_object();
        $where['id']    =   $data->id;
        updateData($table, $dataParam, $where);
        $message        =   "Data have been successfully updated";
    }else{
        //insert operation
        saveData($table, $dataParam);
        $message        =   "Data have been successfully stored";
    }
    
    $feedback   =   [
        'status'    =>  'success',
        'message'   =>  $message
    ];
    
    echo json_encode($feedback);
}

