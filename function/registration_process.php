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
        $emailsql = "SELECT * FROM regis_info where mobile='$mobile'";
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
            $message    =   "Registration was successfull.Your Queue number is ".$queueNumber.'<br> Registro Team.';
            $smsResponse    =   send_registration_success_sms($mobile, $message);
            if($smsResponse){
                $smsData['mobile_number']   =   $mobile;
                $smsData['sms_type']        =   1;
                $smsData['send_at']         =   date("Y-m-d H:i:s");
                $smsData['sms_status']      =   1;
                saveData('sms_send_details', $regData);
            }else{
                $smsData['mobile_number']   =   $mobile;
                $smsData['sms_type']        =   1;
                $smsData['send_at']         =   date("Y-m-d H:i:s");
                $smsData['sms_status']      =   2;
                saveData('sms_send_details', $regData);
            }
            $_SESSION['success']        = "Registration was successfull";
            header("location: index.php");
            exit();
        }
    }
}

function get_registration_queue_data($is_status=''){
    $table      =   "regis_info";
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