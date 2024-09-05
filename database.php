<?php
require_once ('config.php');

// AUTHENTICATION SECTION STARTS

function checkUserExistence($user, $pass) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $user);
    $password = mysqli_real_escape_string($conn, $pass);
    $pwd = md5($password);

    $sql = "select * from users where username='$username' and pwd='$pwd'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception('User with this username and password does not exist');
    }
}

function getUserInfo($user, $password) {
    global $conn;

    $username = mysqli_real_escape_string($conn, $user);
    $password = mysqli_real_escape_string($conn, $password);
    $pwd = md5($password);

    $sql = "select * from users where username='$username' and pwd='$pwd'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception('Unable to fetch user details');
    }
    return $row;
}

function setUserSession($userDetails) {
    session_start();
	$_SESSION["userid"] = $userDetails['id'];
	$_SESSION["username"] = $userDetails['username'];
	$_SESSION["role"] = $userDetails['role'];
	$_SESSION["time"] = time();
}

function trackLoginAttempt($username, $ip_location, $status) {
    global $conn;

    $ip = $_SERVER['REMOTE_ADDR'];
    $now = date("d/m/Y h:i:s a");

    $sql = "INSERT INTO login_log (ip, time, location, username, status) 
            values ('$ip', '$now', '$ip_location', '$username', '$status')";
    $result = $conn->query($sql);
}
// AUTHENTICATION SECTION ENDS

// CUSTOMER SECTION STARTS
function insertCustomer($data) {
    global $conn;

    $sql = "INSERT INTO customers (name,person,address,gst,phone,mobile,fax,email,type,slmn) 
            VALUES ('{$data["name"]}','{$data["person"]}','{$data["address"]}','{$data["gst"]}','{$data["phone"]}','{$data["mobile"]}','{$data["fax"]}','{$data["email"]}','{$data["type"]}','{$data["slmn"]}')";
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('add','CID',$conn->insert_id,$logQuery);
}

function editCustomer($data) {
    global $conn;

    $sql = "UPDATE `customers` SET `name` = '{$data["name"]}', `person` = '{$data["person"]}', `gst` = '{$data["gst"]}', `address` =  '{$data["address"]}',
            `phone` =  '{$data["phone"]}', `fax` =  '{$data["fax"]}', `mobile` =  '{$data["mobile"]}', `email` =  '{$data["email"]}', `type` =  '{$data["type"]}',
            `slmn` =  '{$data["slmn"]}' WHERE  `id` = {$data["id"]}";
    checkAccountExist('customers','id',$data['id']);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('edit','CID',$data['id'],$logQuery);
}

function deleteCustomer($data) {
    global $conn;

    $sql = "DELETE FROM `customers` WHERE `id` = {$data["id"]}";
    checkAccountExist('customers','id',$data['id']);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('delete','CID',$data['id'],$logQuery);
}

function getCustomerDetails($cid) {
    global $conn;

    $sql = "SELECT * FROM `customers` WHERE `id` = $cid";
    checkAccountExist('customers','id',$cid);
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception();
    }
    return $row;
}

function getContactNameFromId($id) {
    global $conn;

    $sql = "SELECT name FROM `customers` WHERE `id` = '$id'";
    checkAccountExist('customers','id',$id);
    $result = $conn->query($sql);
    $fetch = mysqli_fetch_array($result);
    $contact_name = $fetch['name'];
    return $contact_name;
}
// CUSTOMER SECTION ENDS

// ITEMS SECTION STARTS
function getItemsList() {
    global $conn;

    $sql = "SELECT * FROM `items` ORDER BY id DESC LIMIT 0,100";
    $result = $conn->query($sql);
    $items = [];
    while ($row = mysqli_fetch_array($result)) {
        $items[] = $row;
    }
    return $items;
}

function insertItem($data) {
    global $conn;

    $sql = "INSERT INTO `items` (`items`,`price`,`description`,`dimension`,`unit`) 
            VALUES ('{$data["items"]}','{$data["price"]}','{$data["description"]}','{$data["dimension"]}','{$data["unit"]}')";
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('add','ITM',$conn->insert_id,$logQuery);
}
// ITEMS SECTION ENDS


// ACTIVITY LOG SECTION STARTS
function logActivity($process,$code,$id,$logQuery) {
    global $conn;
    
    $date1 = date("d/m/Y h:i:s a");
    $username = $_SESSION['username'];
    $code = $code.$id;
    $logSql = "INSERT INTO activity_log (time, process, code, user, query) 
               VALUES ('$date1', '$process', '$code', '$username', '$logQuery')";
    $conn->query($logSql);
}
// ACTIVITY LOG SECTION ENDS

// CHECK EXISTANCE FOR EDIT AND DELETE
function checkAccountExist($table,$column,$id) {
    global $conn;

    $sqlIdCheck = "SELECT * FROM `$table` WHERE `$column` = '$id'";
    $query = $conn->query($sqlIdCheck);
    $num_rows = mysqli_num_rows($query);

    if(!$num_rows) {
        throw new Exception();
    }
}