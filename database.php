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

function getItemDetails($item) {
    global $conn;

    $sql = "SELECT * FROM `items` WHERE `id` = $item";
    checkAccountExist('items','id',$item);
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception();
    }
    return $row;
}

function insertItem($data) {
    global $conn;

    $sql = "INSERT INTO `items` (`name`,`price`,`description`,`dimension`,`unit`) 
            VALUES ('{$data["name"]}','{$data["price"]}','{$data["description"]}','{$data["dimension"]}','{$data["unit"]}')";
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('add','ITM',$conn->insert_id,$logQuery);
}

function editItem($data) {
    global $conn;

    $sql = "UPDATE items SET name = '{$data["name"]}',price = '{$data["price"]}',`description` = '{$data["description"]}',dimension = '{$data["dimension"]}',
            unit = '{$data["unit"]}' WHERE id = '{$data["id"]}'";
    checkAccountExist('items','id',$data['id']);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('edit','ITM',$data['id'],$logQuery);
}

function deleteItem($data) {
    global $conn;

    $sql = "DELETE FROM `items` WHERE `id` = {$data["id"]}";
    checkAccountExist('items','id',$data['id']);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('delete','ITM',$data['id'],$logQuery);
}
// ITEMS SECTION ENDS

// VEHICLE SECTION STARTS
function getVehicles() {
    global $conn;

    $sql = "SELECT * FROM `vehicles` ORDER BY id DESC LIMIT 0,100";
    $result = $conn->query($sql);
    $vehicles = [];
    while ($row = mysqli_fetch_array($result)) {
        $vehicles[] = $row;
    }
    return $vehicles;
}

function getVehicleDetails($id) {
    global $conn;

    $sql = "SELECT * FROM `vehicles` WHERE `id` = $id";
    checkAccountExist('vehicles','id',$id);
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception();
    }
    return $row;
}

function addVehicle($data) {
    global $conn;

    $sql = "INSERT INTO `vehicles` (`name`,`registration`) VALUES ('{$data["name"]}','{$data["registration"]}')";
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('add','VEH',$conn->insert_id,$logQuery);
}

function editVehicle($data) {
    global $conn;
    $vehicle = $data['id'];

    $sql = "UPDATE `vehicles` SET `name`='{$data["name"]}',`registration`='{$data["registration"]}' WHERE `id` = $vehicle";
    checkAccountExist('vehicles','id',$vehicle);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('edit','ITM',$vehicle,$logQuery);
}

function deleteVehicle($data) {
    global $conn;
    $vehicle = $data["id"];

    $sql = "DELETE FROM `vehicles` WHERE `id` = $vehicle";
    checkAccountExist('vehicles','id',$vehicle);
    $conn->query($sql);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('delete','VEH',$vehicle,$logQuery);
}
// VEHICLE SECTION ENDS

// QUOTATION SECTION STARTS
function getQuotationDetails($qn) {
    global $conn;

    $sql = "SELECT * FROM `quotation` WHERE `id` = $qn";
    checkAccountExist('quotation','id',$qn);
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        throw new Exception();
    }
    return $row;
}

function getQuotationItemDetails($qn) {
    global $conn;

    $sql = "SELECT * FROM `quotation_item` WHERE `quotation_id` = $qn ORDER BY `id`";
    checkAccountExist('quotation_item','quotation_id',$qn);
    $result = $conn->query($sql);
    $qtn_items = [];
    while ($row = mysqli_fetch_array($result)) {
        $qtn_items[] = $row;
    }
    return $qtn_items;
}

function addQuotation($data) {
    global $conn;

    $sql = "INSERT INTO `quotation` (`customer`, `date`,`attention`,`terms`,`transportation`) 
      VALUES ('{$data["customer"]}','{$data["date"]}','{$data["attention"]}','{$data["terms"]}','{$data["transportation"]}')";
    $conn->query($sql);
    $quotation_id = $conn->insert_id;
        $item = $data["item"];
        $quantity = $data["quantity"];
        $unit = $data["unit"];
        $item_count = sizeof($item);
        $sum = 0;
        for ($i = 0; $i < $item_count; $i++) {
        $quantity[$i] = ($quantity[$i] != NULL) ? $quantity[$i] : 0;
        $unit[$i] = ($unit[$i] != NULL) ? $unit[$i] : 0;
        $total[$i] = $quantity[$i] * $unit[$i];
        $sql1 = "INSERT INTO `quotation_item` (`quotation_id`, `item`, `quantity`, `price`, `total`) 
                 VALUES ('$quotation_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
        $conn->query($sql1);
        $sum = $sum + $total[$i];
        }
        $vat = $sum*0.05;
        $grand = $sum*1.05;
        $sql2 = "UPDATE `quotation` SET `subtotal`='$sum',`vat`='$vat',`grand`='$grand' WHERE id='$quotation_id'";
        $conn->query($sql2);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('add','QNO',$quotation_id,$logQuery);
}

function editQuotation($data) {
    global $conn;

    $quotation_id = $data["id"];
    $sql = "UPDATE `quotation` SET `customer` =  '{$data["customer"]}', `date` =  '{$data["date"]}', `attention` =  '{$data["attention"]}', `terms` =  '{$data["terms"]}',
            `transportation` =  '{$data["transportation"]}' WHERE `id` = $quotation_id";
    checkAccountExist('quotation','id',$quotation_id);
    $conn->query($sql);
        deleteQuotationItems($quotation_id);
        $item = $data["item"];
        $quantity = $data["quantity"];
        $unit = $data["unit"];

        $count = sizeof($item);
        $sum = 0;
        for ($i = 0; $i < $count; $i++) {
            $item[$i] = mysqli_real_escape_string($conn, $item[$i]);
            $quantity[$i] = ($quantity[$i] != NULL) ? $quantity[$i] : 0;
            $unit[$i] = ($unit[$i] != NULL) ? $unit[$i] : 0;
            $total[$i] = $quantity[$i] * $unit[$i];
            $sql1 = "INSERT INTO `quotation_item`(`quotation_id`,`item`, `quantity`, `price`, `total`) 
            VALUES ('$quotation_id','$item[$i]', '$quantity[$i]', '$unit[$i]', '$total[$i]')";
            $conn->query($sql1);
            $sum = $sum + $total[$i];
        }
        $vat = $sum*0.05;
        $grand = $sum*1.05;
        $sql2 = "UPDATE `quotation` SET `subtotal`='$sum',`vat`='$vat',`grand`='$grand' WHERE id='$quotation_id'";
        $conn->query($sql2);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('edit','QNO',$quotation_id,$logQuery);
}

function deleteQuotation($data) {
    global $conn;
    $quotation_id = $data["id"];

    $sql = "DELETE FROM `quotation` WHERE `id` = $quotation_id";
    checkAccountExist('quotation','id',$quotation_id);
    $conn->query($sql);
    deleteQuotationItems($quotation_id);
    $logQuery = mysqli_real_escape_string($conn,$sql);
    logActivity('delete','QNO',$quotation_id,$logQuery); 
}

function deleteQuotationItems($qn) {
    global $conn;

    $sql = "DELETE FROM quotation_item WHERE `quotation_id` = $qn";
    $conn->query($sql);
}
// QUOTATION SECTION ENDS


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

// SEARCH FILTER SECTION
function getSearchFilters() {
    $period_sql = "";
    $cust_sql = "";
    $mode = 'Recent View';
    $show_date = "";

    if ($_POST) {
        $fdate = $_POST['fdate'];
        $tdate = $_POST['tdate'];
        $customer = $_POST['customer'];

        $period_sql = "WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        if (!empty($customer)) {
            $cust_sql = "AND `customer` = '$customer'";
        }
        $mode = 'Search Mode';
        $show_date = "[$fdate - $tdate]";
    }
    return [
        'period_sql' => $period_sql,
        'cust_sql' => $cust_sql,
        'mode' => $mode,
        'show_date' => $show_date
    ];
}
