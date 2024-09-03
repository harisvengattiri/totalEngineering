<?php

$baseurl = "http://localhost/totalEngineering";
// $baseurl = "http://gulfit.in/cybozErp";

$cdn_url = $baseurl."/cdn/mancon";

setlocale(LC_MONETARY,"en_US");
date_default_timezone_set('Asia/Dubai');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cybozErp";

$title="CERP | MOHAMMED AL NASERI CONCRETE PRODUCTION AND BLOCK FACTORY - "
.ucwords(str_ireplace(array($baseurl,'.php', '_', 'index', '/'), array('', '', ' ', 'dashboard', ' '), 'https://'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"]));

$erp_version="Version 2.1.6";

$db = new mysqli($servername,$username,$password,$dbname);

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


  function custom_money_format($format, $number) {
  $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
  $value = number_format($number, 2, '.', ',');
  return preg_replace($regex, $value, $format);
  }
  
?>