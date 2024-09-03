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
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$rep=$_POST["sales_rep"];
    if(!empty($rep)){
        $sqlrep="SELECT name from customers where id='$rep'";
        $queryrep=mysqli_query($conn,$sqlrep);
        $fetchrep=mysqli_fetch_array($queryrep);
        $repname=$fetchrep['name'];
        
        $sql_rep = "AND rep='$rep'";
    } else {
        $repname = "ALL";
        $sql_rep = "";
    }
}
?>
<!--<title> <?php //echo $title;?></title>-->
<style type = "text/css">
   
      @media screen {
           @page { size: auto;  margin: 0mm; }
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page { size: auto;  margin: 0mm; }
           
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

<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>SALES REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">SALESMAN:<b> <?php echo $repname;?></b></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Customer
              </th>
              <th style="text-align:right;">
                  Amount
              </th>
              <th style="text-align:right;">
                  VAT
              </th>
              <th style="text-align:right;">
                  Total
              </th>
                      
          </tr>
        </thead>
        <tbody>
		<?php
//	$sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' AND customer='$company' AND site='$site'";
        $sql = "SELECT SUM(amt) AS amt,customers.name AS customer FROM delivery_note
                INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id
                INNER JOIN customers ON delivery_note.customer = customers.id
                WHERE STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y')
                AND STR_TO_DATE('$tdate', '%d/%m/%Y') $sql_rep GROUP BY customer ORDER BY customer";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $ttotal=0;
        $tvat=0;
        $grand=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        $customer=$row['customer'];
        $total=$row['amt'];       
        ?>
          <tr>
               <!--<td><?php echo sprintf('%06d',$id);?></td>-->
               <td><?php echo $sl;?></td>
               <td><?php echo $customer;?></td>
               <td style="text-align: right;">
                    <?php echo custom_money_format('%!i', $total);
                    $ttotal = $ttotal+$total;
                    ?>
               </td>
               
               <td style="text-align: right;">
                    <?php $vat=$total*0.05;
                       $tvat = $tvat+$vat;
                    echo custom_money_format('%!i', $vat);?>
               </td>
               <td style="text-align: right;">
                    <?php $sumtotal=$total+$vat;?>
                    <?php echo custom_money_format('%!i', $sumtotal);?>
               </td>
             
          </tr>
		<?php
                  $grand=$grand+$sumtotal;
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
        
              <td colspan="1"><b></b></td>
              <td colspan="1" style="text-align:right;"><b>Grand Total</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $ttotal);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tvat);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $grand);?></b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>