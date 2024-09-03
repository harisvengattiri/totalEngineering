<?php
include "../config.php";
error_reporting(0);
?>
<?php
session_start();
if (!isset($_SESSION['userid'])) {
  header("Location:$baseurl/login/");
}
?>
<?php
if (isset($_POST)) {
  $fdate = $_POST["fdate"];
  $tdate = $_POST["tdate"];
  $driver = $_POST["driver"];

    $sqlType = "SELECT `vehicleType` FROM `driverType` WHERE `driver`='$driver'";
    $queryType = mysqli_query($conn,$sqlType);
    $resultType = mysqli_fetch_array($queryType);
    $driverType = $resultType['vehicleType'];
      
    switch ($driverType) {
      case "Tipper":
        $fairType = 'fair';
        break;
      case "6 Wheel":
        $fairType = 'fair1';
        break;
      case "2XL Trailor":
        $fairType = 'fair2';
        break;
      case "3XL Trailor":
        $fairType = 'fair3';
        break;
      default:
        $fairType = 'fair';
    }

    $sqldri = "SELECT name from customers where id='$driver'";
    $querydri = mysqli_query($conn, $sqldri);
    $fetchdri = mysqli_fetch_array($querydri);
    $driverName = $fetchdri['name'];
}
?>
<!--<title> <?php //echo $title;
            ?></title>-->
<style type="text/css">
  @media screen {
    /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
  }

  @media print {
    @page {
      size: auto;
      margin: 0mm;
    }

    /*p.bodyText {font-family:georgia, times, serif;}*/
  }

  @media screen,
  print {

    /*p.bodyText {font-size:10pt}*/
  }
</style>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    text-align: left;
    padding: 8px;
  }

  th {
    background-color: #4CAF50;
    color: white;
  }

  h1,
  h2 {
    font-family: Arial, Helvetica, sans-serif;
  }

  th,
  td {
    font-family: verdana;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2
  }
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<center>
  <h1 style="margin-bottom: -3%;">DRIVER TRIP ALLOWANCE REPORT</h1>
</center>
<h3 style="float:left;">Driver: <?php echo $driverName;?> [<?php echo $driverType;?>]</h3>
<h3 style="float:right;"> Date: From <?php echo $fdate; ?> - To <?php echo $tdate; ?></h3>

<table>
  <thead>
    <tr>
      <th>
        Sl No
      </th>
      <th>
        Date
      </th>
      <th>
        DNO
      </th>
      <th>
        Contractor
      </th>
      <th>
        Location
      </th>
      <th>
        Amount
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND driver='$driver' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $sl = 1;
      $tfair = 0;
      while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];

        $name1 = $row["customer"];
        $sqlcust = "SELECT name from customers where id='$name1'";
        $querycust = mysqli_query($conn, $sqlcust);
        $fetchcust = mysqli_fetch_array($querycust);
        $cust = $fetchcust['name'];
        $site = $row["customersite"];
        $sqlsite = "SELECT p_name,location from customer_site where id='$site'";
        $querysite = mysqli_query($conn, $sqlsite);
        $fetchsite = mysqli_fetch_array($querysite);
        $site1 = $fetchsite['p_name'];
        $loc = $fetchsite['location'];

        $sqlfair = "SELECT `$fairType` AS fair,`location` FROM `fair` WHERE `id`='$loc'";
        $queryfair = mysqli_query($conn, $sqlfair);
        $fetchfair = mysqli_fetch_array($queryfair);
        $fair = $fetchfair['fair'];
        $fair = ($fair != NULL) ? $fair : 0;
        $loc1 = $fetchfair['location'];

    ?>
        <tr>
          <td><?php echo $sl; ?></td>
          <td><?php echo $row['date']; ?></td>
          <td><?php echo sprintf("%06d", $id); ?></td>
          <td><?php echo $cust; ?></td>
          <td><?php echo $loc1; ?></td>
          <td><?php echo $fair; ?></td>
        </tr>
    <?php
        $sl = $sl + 1;
        $tfair = $tfair + $fair;
      }
    }
    ?>
  </tbody>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td><b>Total</b></td>
    <td><b><?php echo $tfair; ?></b></td>
  </tr>
</table>
<?php
if (isset($_POST['print'])) { ?>

  <body onload="window.print()">
  <?php } ?>