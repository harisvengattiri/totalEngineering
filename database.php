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