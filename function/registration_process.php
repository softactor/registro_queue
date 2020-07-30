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
        $emailsql = "SELECT * FROM regis_info where mobile='$mobile'";
        $result = $conn->query($emailsql);
        if ($result->num_rows > 0) {
            $_SESSION['error'] = "You have already registered!";
            header("location: index.php");
            exit();
        } else {
            $regData['is_status']       = 0;
            $regData['name']            = $name;
            $regData['mobile']          = $mobile;
            $regData['queue_number']    = get_regis_queue_number($mobile);
            $regData['generated_at']    = date("Y-m-d H:i:s");
            saveData('regis_info', $regData);
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