<?php

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

