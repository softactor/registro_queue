<?php
function get_table_next_primary_id($table){
    global $conn;
    $sql        = "SELECT count('id') as total FROM $table";
    $result     = $conn->query($sql);
    $total_row  =   $result->fetch_object()->total;
    $nextRow    =   $total_row+1;
    return $nextRow;
}
function getTableDataByTableName($table, $order = 'asc', $column = 'name', $dataType = 'obj') {
    global $conn;
    $dataContainer = [];
    $sql = "SELECT * FROM $table order by $column $order";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        if (isset($dataType) && $dataType == 'obj') {
            while ($row = $result->fetch_object()) {
                $dataContainer[] = $row;
            }
        } else {
            while ($row = $result->fetch_assoc()) {
                $dataContainer[] = $row;
            }
        }
    }
    return $dataContainer;
}

function saveData($table, $dataParam) {
    global $conn;
    $fields_array = array_keys($dataParam);
    $fields = implode(',', array_keys($dataParam));
    $values = "'" . implode("', '", array_values($dataParam)) . "'";
    ;
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";

    if ($conn->query($sql) === TRUE) {
        $feedbackData = [
            'status' => 'success',
            'data' => 'Data have been successfully inserted',
            'last_id' => $conn->insert_id,
        ];
        return $feedbackData;
    } else {
        $feedbackData = [
            'status' => 'error',
            'data' => "Error: " . $sql . "<br>" . $conn->error,
            'last_id' => '',
        ];
        return $feedbackData;
    }
}

function updateData($table, $dataParam, $where) {
    global $conn;
    $valueSets = array();
    foreach($dataParam as $key => $value) {
        if(isset($value) && !empty($value)){
            $valueSets[] = $key . " = '" . $value . "'";
        }
    }

    $conditionSets = array();
    foreach($where as $key => $value) {
       $conditionSets[] = $key . " = '" . $value . "'";
    }
    $sql = "UPDATE $table SET ". join(",",$valueSets) . " WHERE " . join(" AND ", $conditionSets);
    if ($conn->query($sql) === TRUE) {
        $feedbackData   =   [
            'status'    =>  'success',
            'message'   =>  'Data have been successfully Updated',
        ];
    } else {
        $feedbackData   =   [
            'status'    =>  'error',
            'message'   =>  "Error: " . $sql . "<br>" . $conn->error,
        ];        
    }
    return $feedbackData;
}

function getNameByIdAndTable($table) {
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    $name = '';
    if ($result->num_rows > 0) {
        $name = $result->fetch_object()->name;
    }
    return $name;
}
function getIdByNameAndTable($table) {
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    $id = '';
    if ($result->num_rows > 0) {
        $id = $result->fetch_object()->id;
    }
    return $id;
}

function getDataRowIdAndTable($table, $dataType='obj') {
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    $name = '';
    if ($result->num_rows > 0) {        
        if (isset($dataType) && $dataType == 'obj') {
            return $result->fetch_object();
        } else {
            return $result->fetch_assoc();
        }
    }
}

function getDataRowByTableAndId($table, $id) {
    global $conn;
    $sql = "SELECT * FROM $table where id=" . $id;
    $result = $conn->query($sql);
    $name = '';
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    } else {
        return false;
    }
}

function getDefaultCategoryCode($table, $fieldName, $modifier, $defaultCode, $prefix) {
    global $conn;
    $sql = "SELECT count($fieldName) as total_row FROM $table";
    $result = $conn->query($sql);
    $name = '';
    $lastRows = $result->fetch_object();
    $number = intval($lastRows->total_row) + 1;
    $defaultCode = $prefix . sprintf('%' . $modifier, $number);
    return $defaultCode;
}

function isDuplicateData($table, $where, $notWhere = '') {
    global $conn;
    $sql = '';
    $sql .= "SELECT * FROM $table where $where ";
    if (isset($notWhere) && !empty($notWhere)) {
        $sql .= " And $notWhere";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = $result->fetch_object();
        return $data->id;
    }
    return false;
}

function getDataRowByTable($table) {
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    $name = '';
    if ($result->num_rows > 0) {
        return $result->num_rows;
    }
    return "0";
}

function deleteRecordByTableAndId($table, $fieldName, $id) {
    global $conn;
    $sql = "DELETE FROM $table WHERE $fieldName=" . $id;
    if ($conn->query($sql) === TRUE) {
        $feedbackData = [
            'status' => 'success',
            'message' => 'Data have been successfully Deleted',
        ];
        return $feedbackData;
    } else {
        $feedbackData = [
            'status' => 'error',
            'message' => "Error: " . $sql . "<br>" . $conn->error,
        ];
        return $feedbackData;
    }
}
function deleteRecordByWhere($table) {
    global $conn;
    $sql = "DELETE FROM $table";
    if ($conn->query($sql) === TRUE) {
        $feedbackData = [
            'status' => 'success',
            'message' => 'Data have been successfully Deleted',
        ];
        return $feedbackData;
    } else {
        $feedbackData = [
            'status' => 'error',
            'message' => "Error: " . $sql . "<br>" . $conn->error,
        ];
        return $feedbackData;
    }
}

function is_super_admin($user_id, $roleName = 'sa') {
    global $conn;
    $sql    =   "SELECT r.*
                     FROM roles as r
                     JOIN users as u 
                     ON r.id = u.role_id
                     WHERE u.id = $user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {  
            $users = $result->fetch_object();
            if ($users->short_name == $roleName) {
                return true;
            }
            return false;
        }
    return false;
}

//functions to loop day,month,year
function formDay() {
    for ($i = 1; $i <= 31; $i++) {
        $selected = ($i == date('d')) ? ' selected' : '';
        echo '<option' . $selected . ' value="' . $i . '">' . $i . '</option>' . "\n";
    }
}

//with the -8/+8 month, meaning june is center month
function formMonth() {
    $month = strtotime(date('Y') . '-' . date('m') . '-' . date('j') . ' - 8 months');
    $end = strtotime(date('Y') . '-' . date('m') . '-' . date('j') . ' + 8 months');
    while ($month < $end) {
        $selected = (date('F', $month) == date('F')) ? ' selected' : '';
        echo '<option' . $selected . ' value="' . date('F', $month) . '">' . date('F', $month) . '</option>' . "\n";
        $month = strtotime("+1 month", $month);
    }
}

function formYear($startYear = false) {
    if ($startYear) {
        $i = $startYear;
    } else {
        $i = 1980;
    }
    for ($i; $i <= date('Y'); $i++) {
        $selected = ($i == date('Y')) ? ' selected' : '';
        echo '<option' . $selected . ' value="' . $i . '">' . $i . '</option>' . "\n";
    }
}
function human_format_date($timestamp) {
    return date("M jS, Y h:i a", strtotime($timestamp)); //September 30th, 2013
}
function array_to_csv__($array, $download = "") {
    if ($download != "") {
        header('Content-type: application/csv');
        header('Content-Disposition: attachement; filename="' . $download . '"');
        header('Content-Transfer-Encoding: binary');
    }

    ob_start();
    $f = fopen('php://output', 'w') or show_error("Can't open php://output");
    $n = 0;
    foreach ($array as $line) {
        $n++;
        if (!fputcsv($f, $line)) {
            show_error("Can't write line $n: $line");
        }
    }
    fclose($f) or show_error("Can't close php://output");
    return ob_get_clean();
}
function csvToArray($filename = '', $delimiter = ',') {
    if (!file_exists($filename) || !is_readable($filename))
        return false;

    $header = null;
    $data = array();
    $count  =   1;
    if (($handle = fopen($filename, 'r')) !== false) {
        while ($row = fgetcsv($handle)) {
            if($count==1){
                $count++;
                continue;
            }
            $data[]     =     $row;

        }
        fclose($handle);
    }

    return $data;
} // end of method

function hasAccessPermission($user_id, $page_name, $accessType) {
    global $conn;
    $return = false;
    $fieldsName     =   'pa.' . $accessType;
    $sql    =   "SELECT pa.*
                     FROM role_access as pa
                     JOIN roles as r 
                     ON pa.role_id = r.id
                     JOIN users as u
                     ON u.role_id = r.id
                     WHERE u.id = '$user_id' AND pa.page_name = '$page_name' AND $fieldsName=1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {            
            $return = true;
        }

    return $return;
}

function getRoleNameByRoleId($id){
    global $conn;
    $table  =   "roles";
    $sql = "SELECT * FROM $table WHERE id=$id";
    $result = $conn->query($sql);
    $name   =   '';
    if ($result->num_rows > 0) {
        $name   =   $result->fetch_object()->name;
    }
    return $name;
}
function getUserNameByUserId($id){
    global $conn;
    $sql = "SELECT * FROM users where id=$id";
    $result = $conn->query($sql);
    $name   =   '';
    if ($result->num_rows > 0) {
        $users  = $result->fetch_object();
        return $users->name;
    }
    return $name;
}