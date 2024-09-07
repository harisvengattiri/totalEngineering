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
    case 'items':
        handleItems();
        break;
    case 'quotations':
        handleQuotations();
        break;
    case 'vehicles':
        handleVehicles();
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
    if (isset($_REQUEST['submit_edit_customer'])) {
        try {
            editCustomer($_REQUEST);
            header('Location: '.BASEURL."/edit/customer?id=$_REQUEST[id]&status=success");
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

// Items Section here
function handleItems() {
    if (isset($_REQUEST['submit_add_item'])) {
        try {
            insertItem($_REQUEST);
            header('Location: '.BASEURL.'/items?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/items?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_edit_item'])) {
        try {
            editItem($_REQUEST);
            header('Location: '.BASEURL.'/items?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/items?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_delete_item'])) {
        try {
            deleteItem($_REQUEST);
            header('Location: '.BASEURL.'/items?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/items?status=failed');
            exit();
        }
    }
}

// Vehicles Section here
function handleVehicles() {
    if (isset($_REQUEST['submit_add_vehicle'])) {
        try {
            addVehicle($_REQUEST);
            header('Location: '.BASEURL.'/vehicles?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/vehicles?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_edit_vehicle'])) {
        try {
            editVehicle($_REQUEST);
            header('Location: '.BASEURL.'/vehicles?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/vehicles?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_delete_vehicle'])) {
        try {
            deleteVehicle($_REQUEST);
            header('Location: '.BASEURL.'/vehicles?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/vehicles?status=failed');
            exit();
        }
    }
}

// Quotations Section here
function handleQuotations() {
    if (isset($_REQUEST['submit_add_quotation'])) {
        try {
            addQuotation($_REQUEST);
            header('Location: '.BASEURL.'/quotation?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/quotation?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_edit_quotation'])) {
        try {
            editQuotation($_REQUEST);
            header('Location: '.BASEURL.'/quotation?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/quotation?status=failed');
            exit();
        }
    }
    if (isset($_REQUEST['submit_delete_quotation'])) {
        try {
            deleteQuotation($_REQUEST);
            header('Location: '.BASEURL.'/quotation?status=success');
            exit();
        } catch (Exception $e) {
            header('Location: '.BASEURL.'/quotation?status=failed');
            exit();
        }
    }
}
