<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['userid'])) {
    require_once("login.php");
    exit();
}

// Totally Handling Switch Here
$controller = $_REQUEST['controller'];
switch ($controller) {
    case 'contacts':
        handleCustomer();
        break;
}

// Customer Section here
function handleCustomer() {
    if (isset($_REQUEST['submit_add_customer'])) {
        try {
            insertCustomer($_REQUEST);
            header('Location: '.BASEURL.'/customers?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/customers?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_delete_customer'])) {
        try {
            deleteCustomer($_REQUEST);
            header('Location: '.BASEURL.'/customers?status=deleted');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/customers?status=failed');
            exit();
        }
    }
    
}
