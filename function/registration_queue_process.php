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
    $res    =   updateData($table, $regis_info, $where);
    if(isset($remarks) && $remarks == 'Completed'){
        $toRemind     =   get_sms_reminder_phone_number();
        if(isset($toRemind) && !empty($toRemind)){
            $ordinalNumber  =   $toRemind['number_ordinal'];
            $toPhoneNumber  =   $toRemind['phoneNumber'];
            
            $message  = "";
            $message .= "Dear ".ucfirst($toRemind['name']).",";
            $message .= chr(10) . "You are currently $ordinalNumber in queue.";
            $message .= chr(10) . "Kindly make your way to the waiting area.";
            $message .= chr(10) . "Thank you for waiting.";
            $message .= chr(10);
            $message .= chr(10) . "Brought to you by";
            $message .= chr(10) . "Registro";
            
            $reminderResp   =   process_send_remider_sms($toPhoneNumber, $message);
            $sendSMSTime    =   date("Y-m-d H:i:s");
            if($reminderResp){
                $update['is_remind_sms']         =   1;
                $update['send_remind_sms_at']    =   $sendSMSTime;
                $where['id']    =   $toRemind['id'];
                updateData('regis_info', $update, $where);

                $insert['mobile_number']    =   $toRemind['phoneNumber'];
                $insert['sms_type']         =   2;
                $insert['send_at']          =   $sendSMSTime;
                $insert['sms_status']       =   1;
                saveData('sms_send_details', $insert);
            }else{
                $insert['mobile_number']    =   $toRemind['phoneNumber'];
                $insert['sms_type']         =   2;
                $insert['send_at']          =   $sendSMSTime;
                $insert['sms_status']       =   0;
                saveData('sms_send_details', $insert);
            }
            
        }
    }
    $feedback   =   [
        'update_time'   => human_format_date($update_time),
        'status'        =>  'success',
        'message'       =>  "Data have been successfully updated."
    ];
    
    echo json_encode($feedback);
}
function process_send_remider_sms($mobile, $message){
    $toPhoneNumber      =   $mobile;
    $message            =   $message;
    
    $is_success         =   true;
    include '../admin/sms/Twilio/send_sms.php';
    return $is_success;
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
