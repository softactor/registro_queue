<?php

if (isset($_POST['registration_submit']) && !empty($_POST['registration_submit'])) {
    $error_status = false;
    $error_string = [];
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = validate_text_input($_POST['name']);
    } else {
        $error_status = true;
        $error_string['name_empty'] = 'Name is required.';
    }
    if (isset($_POST['mobile']) && !empty($_POST['mobile'])) {
        $mobile = validate_text_input($_POST['mobile']);
    } else {
        $error_status = true;
        $error_string['mobile_empty'] = 'Mobile Number is required.';
    }

    if ($error_status) {
        foreach ($error_string as $errorKey => $errorVal) {
            $_SESSION['error_message'][$errorKey] = $errorVal;
        }
        $_SESSION['error'] = "Please fill up all required fields!";
        header("location: index.php");
        exit();
    } else {
        $mobile     =   "+65".$mobile;
        $emailsql = "SELECT * FROM regis_info where mobile='$mobile' AND is_delete!=1";
        $result = $conn->query($emailsql);
        if ($result->num_rows > 0) {
            $_SESSION['error'] = "You have already registered!";
            header("location: index.php");
            exit();
        } else {
            $queueNumber    =   get_regis_queue_number($mobile);
            $regData['is_status']       = 0;
            $regData['name']            = $name;
            $regData['mobile']          = $mobile;
            $regData['queue_number']    = $queueNumber;
            $regData['generated_at']    = date("Y-m-d H:i:s");
            saveData('regis_info', $regData);
            $message  = "";
            $message .= "Dear ".ucfirst($name).",";
            $message .= chr(10) . "Registration is successful";
            $message .= chr(10) . "Your queue number is " . $queueNumber;
            $message .= chr(10) . "We'll send you another reminder SMS when your number is approaching";
            $message .= chr(10) . "Thanks";
            $message .= chr(10);
            $message .= chr(10) . "Brought to you by";
            $message .= chr(10) . "Registro";
            $smsResponse    =   send_registration_success_sms($mobile, $message);
            if($smsResponse){
                $smsData['mobile_number']   =   $mobile;
                $smsData['sms_type']        =   1;
                $smsData['send_at']         =   date("Y-m-d H:i:s");
                $smsData['sms_status']      =   1;
                saveData('sms_send_details', $smsData);
            }else{
                $smsData['mobile_number']   =   $mobile;
                $smsData['sms_type']        =   1;
                $smsData['send_at']         =   date("Y-m-d H:i:s");
                $smsData['sms_status']      =   2;
                saveData('sms_send_details', $smsData);
            }
            $_SESSION['success']        = "Registration was successfull";
            header("location: index.php");
            exit();
        }
    }
}

function get_registration_queue_data($is_status=''){
    $table      =   "regis_info where is_delete=0";
    $order      =   "ASC";
    $column     =   "generated_at";
    $data       =   getTableDataByTableName($table, $order, $column);
    return $data;
}

function send_registration_success_sms($mobile, $message){
    $toPhoneNumber      =   $mobile;
    $message            =   $message;
    $is_success         =   true;
    include 'admin/sms/Twilio/send_sms.php';
    
    return $is_success;
}
if(isset($_GET['process_type']) && $_GET['process_type'] == 'deleteVisitor'){
    session_start();
    date_default_timezone_set('Asia/Singapore');
    include '../connection/connect.php';
    include '../helper/utilities.php';
    $table          =   "regis_info";
    $visitor_id     =   $_POST['id'];
    $update_time    =   date("Y-m-d H:i:s");
    $regis_info['is_delete']    =   1;
    $regis_info['updated_at']   =   $update_time;
    $where['id']                =   $visitor_id;
    updateData($table, $regis_info, $where);
    $feedback   =   [
        'status'        =>  'success',
        'message'       =>  "Data have been successfully deleted."
    ];
    
    echo json_encode($feedback);
}