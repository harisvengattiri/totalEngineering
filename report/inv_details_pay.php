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
    $period = $_POST['date'];
}

$a1 = date('d/m/Y', strtotime("first day of -3 month"));
$a2 = date('d/m/Y', strtotime("last day of -3 month"));

$b1 = date('d/m/Y', strtotime("first day of -2 month"));
$b2 = date('d/m/Y', strtotime("last day of -2 month"));

$c1 = date('d/m/Y', strtotime("first day of -1 month"));
$c2 = date('d/m/Y', strtotime("last day of -1 month"));


if($period == 1)
{
    $from = $c1;
    $to = $c2;
}
if($period == 2)
{
    $from = $b1;
    $to = $c2;
}
if($period == 3)
{
    $from = $a1;
    $to = $c2;
}
// echo $from . '-' . $to;
// echo $a1.'-'.$a2.'<br/>'.$b1.'-'.$b2.'<br/>'.$c1.'-'.$c2.'<br/>';
?>
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

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>INVOICE DETAILS [Receivable]</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $from;?> - <?php echo $to;?></span></td>
     </tr>     
</table>
      
<?php
    // $sql="SELECT * FROM voucher WHERE STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND
    // STR_TO_DATE('$tdate', '%d/%m/%Y') AND status !='Cleared'";
    
    // $sql = "SELECT id,date,grand FROM `invoice` WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$from', '%d/%m/%Y') AND STR_TO_DATE('$to', '%d/%m/%Y') AND      
    //         id NOT IN (SELECT invoice FROM `reciept_invoice` GROUP BY invoice)";
            
    // $sql = "select inv.id,inv.date,inv.o_r,cst.name,inv.grand
    //         FROM invoice inv 
    //         LEFT JOIN reciept_invoice rinv ON inv.id = rinv.invoice
    //         LEFT JOIN credit_note cn ON inv.id=cn.invoice
    //         LEFT JOIN customers cst ON inv.customer = cst.id
    //         WHERE STR_TO_DATE(inv.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$from', '%d/%m/%Y') AND STR_TO_DATE('$to', '%d/%m/%Y') AND rinv.invoice IS NULL AND cn.invoice IS NULL";

    $sql = "SELECT invo,date,o_r,cid,cst.name as customer,grand FROM (
                (select inv.id as invo,inv.date,inv.o_r,inv.customer as cid,inv.grand
                FROM invoice inv
                WHERE inv.grand > 0 AND inv.status != 'Paid' AND STR_TO_DATE(inv.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$from', '%d/%m/%Y') AND STR_TO_DATE('$to', '%d/%m/%Y'))
                UNION ALL
                (select inv.id as invo,inv.date,inv.o_r,inv.customer as cid,inv.grand
                FROM invoice inv
                JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                JOIN reciept rpt ON rpt.id=rinv.reciept_id
                WHERE inv.grand > 0 AND inv.status = 'Paid' AND rpt.status!='Cleared' AND STR_TO_DATE(inv.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$from', '%d/%m/%Y') AND STR_TO_DATE('$to', '%d/%m/%Y'))
            ) results
            LEFT JOIN customers cst ON results.cid = cst.id";
    
    $result = mysqli_query($conn_backup, $sql);
    if (mysqli_num_rows($result) > 0) 
	{ $sl=1;       
?>
 <table id="tbl1" align="center" style="width:90%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                  Sl
              </th>
              <th>
                   Invoice
              </th>
              <th>
                   Date
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Purchase Order
              </th>
              <th>
                   Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) 
        {
        ?>     
            
             <tr>
                <td><?php echo $sl;?></td>
                <td><?php echo $row['invo'];?></td>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $row['customer'];?></td>
                <td><?php echo $row['o_r'];?></td>
                <td><?php echo $row['grand'];?></td>
             </tr>
             
        <?php 
            $sl=$sl+1;
        }
        ?>
       </tbody>
 </table>
 <?php } ?>
      

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>