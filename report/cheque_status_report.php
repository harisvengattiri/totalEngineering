<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(!empty($_POST))
{
$status=$_POST["status"];
$fdate=$_POST["fdate"];
    $start_date = '01/01/2016';
    $inception = 'Since Inception';
    $show_fdate = ($fdate != NULL) ? $fdate : $inception;
    $fdate = ($fdate != NULL) ? $fdate : $start_date;
$tdate=$_POST["tdate"];
}
?>
<!--<title> <?php //echo $title;?></title>-->
<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page {margin: 0mm;}
           #hlpo,#blpo{width:100px;word-break: break-all;}
           #hsite,#bsite{width:200px;word-break: break-all;}
           #hitem,#bitem{width:200px;word-break: break-all;}
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
           
         /*p.bodyText {font-size:10pt}*/
      }
  
</style>

<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
h1, h2 {
    font-family: Arial, Helvetica, sans-serif;
}
th,td {
    font-family: verdana;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>

<!--<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<center> <h1 style="margin-bottom: -3%;"><?php // echo strtoupper($status);?> ACCOUNT STATEMENT </h1></center><br>
<h3 style="float:left;">Customer: <?php echo $cust;?></h3>
<h3 style="float:right;"> Date: From <?php // echo $fdate;?> - To <?php // echo $tdate;?></h3>-->

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>CHEQUE STATUS REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Status: <?php echo $status;?></span>
          </td>
          <td width="50%" style="text-align:right">
            <!--<span style="font-size:15px;"> Date: <?php // echo $fdate;?></span>-->
            <span style="font-size:15px;"> Date: <?php echo $show_fdate;?> - <?php echo $tdate;?></span>
          </td>
     </tr>     
</table>
 
 
 
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th data-toggle="true">
                 Sl No
              </th>
              <th>
                  Receipt No
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Cheque No
              </th>
              <th>
                 Date
              </th>
              <th>
                 Due Date
              </th>
              <th style="text-align: center;">
                  Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
                
        $sql="SELECT cs.name AS customer,rcp.id AS receipt,rcp.cheque_no AS cheque,rcp.pdate AS date,rcp.duedate AS duedate,rcp.grand as amount
              FROM `reciept` rcp INNER JOIN `customers` cs ON rcp.customer=cs.id
              WHERE rcp.`status`='$status' AND rcp.`pdate`!= '' AND rcp.`grand`>0
              AND STR_TO_DATE(rcp.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0)
		{
        $sl=1;
        $total_grand=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        $grand=$row['amount'];
        $total_grand = $total_grand+$grand;
        ?>  
        <tr>
            <td><?php echo $sl;?></td>
            <td>RPT <?php echo sprintf("%05d",$row["receipt"]);?></td>
            <td><?php echo $row["customer"];?></td>
            <td><?php echo $row["cheque"];?></td>
            <td><?php echo $row["date"];?></td>
            <td><?php echo $row["duedate"];?></td>
            <td style="text-align: right;">
                  <?php echo custom_money_format("%!i",$grand);?>
            </td>
        </tr>
		<?php $sl=$sl+1; } ?>
            <tr>
                <td colspan="6" style="text-align:right;"><b>Total Amount</b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$total_grand);?></b></td>
            </tr>
        <?php } ?>

        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>