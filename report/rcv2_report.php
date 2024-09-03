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
$customer=$_POST["company"];

               $sqlcust="SELECT name,op_bal from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $opening=$fetchcust['op_bal'];
               $opening = ($opening != NULL) ? $opening : 0;
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
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>RECEIVABLE II REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Customer: <?php echo $cust;?><br>Customer No: <?php echo $customer;?></span>
          </td>
          <td width="50%" style="text-align:right">
            <?php $today = date('d/m/Y');?>
            <span style="font-size:15px;"> Date: <?php echo $today;?></span>
               <!--<span style="font-size:15px;"> Date: <?php // if ($fdate=='') {echo "Since Inception";} else {echo $fdate;}?> - <?php // echo $tdate;?></span>-->
          </td>
     </tr>     
</table>
 
 
 
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th data-toggle="true">
                 Invoice No
              </th>
              <th>
                 Date
              </th>
              <th>
                  Customer Site
              </th>
              <th>
                  L.P.O
              </th>
              <th style="text-align: center;">
                  Total
              </th>
              <th style="text-align: center;">
                  Paid
              </th>
              <th style="text-align: center;">
                  Balance
              </th>
              <th style="text-align: center;">
                  Accumulated<br> Balance
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
                  
//		$sql="SELECT * FROM 
//                         (SELECT id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, 0 as received FROM invoice WHERE customer='$customer' AND id NOT IN (SELECT reciept_invoice.invoice FROM reciept_invoice GROUP BY reciept_invoice.invoice) 
//                         UNION ALL
//                         SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(reciept_invoice.total+credit_note.total) as received
//                         FROM invoice
//                         LEFT JOIN reciept_invoice ON invoice.id = reciept_invoice.invoice
//                         LEFT JOIN credit_note ON credit_note.invoice = reciept_invoice.invoice
//                         WHERE invoice.customer='$customer' GROUP BY reciept_invoice.invoice)
//                         results WHERE customer='$customer' AND total > received order by id";
                
                
                
// PREVIOUS SQL                
        // $sql="SELECT id,lpo,site,date,ROUND(`total`,2) as total,ROUND(sum(`received`),2) as received FROM 
        //     (SELECT id as id,lpo as lpo, invoice.customer as customer, invoice.site as site, invoice.date as date, invoice.grand as total, 0 as received FROM invoice WHERE customer='$customer' 
        //         AND id NOT IN (SELECT reciept_invoice.invoice FROM reciept_invoice GROUP BY reciept_invoice.invoice)
        //         AND id NOT IN (SELECT credit_note.invoice FROM credit_note GROUP BY credit_note.invoice)
        //     UNION ALL
        //     SELECT invoice.id as id, invoice.lpo as lpo, invoice.customer as customer, invoice.site as site, invoice.date as date, invoice.grand as total, sum(reciept_invoice.total) as received
        //     FROM invoice
        //     LEFT JOIN reciept_invoice ON invoice.id = reciept_invoice.invoice WHERE invoice.customer='$customer' GROUP BY reciept_invoice.invoice  
        //     UNION ALL
        //     SELECT invoice.id as id, invoice.lpo as lpo, invoice.customer as customer, invoice.site as site, invoice.date as date, invoice.grand as total, sum(credit_note.total) as received
        //     FROM invoice
        //     LEFT JOIN credit_note ON invoice.id = credit_note.invoice WHERE invoice.customer='$customer' GROUP BY credit_note.invoice)                  
        //     results WHERE customer='$customer' AND total > received GROUP BY id order by id";
        
    //  NEW SQL       
            $sql = "SELECT invoice,lpo,site,date,balance FROM (
                    SELECT inv.id AS invoice,inv.lpo AS lpo,inv.site AS site,inv.date AS date,ROUND(sum(rcp.`amount`),2) AS balance FROM `invoice` inv
                    INNER JOIN `additionalRcp` rcp ON inv.id=rcp.invoice
                    WHERE inv.customer='$customer'
                    GROUP BY rcp.invoice
                    ) result WHERE balance > 0";
                
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0)
		{
        $sl=1;
        $ttotal=0;
        $treceived=0;
        $tbalance=0;
    ?>    
        
    <!--OLD STARTS HERE--------------------------------------------------------> 
    <?php
        // while($row = mysqli_fetch_assoc($result)) {
        //       $id=$row['id'];
        //       $balance=$row["total"]-$row["received"];
        //       if($balance > 0.01) {

        //         $site_id = $row["site"];
        //         if($site_id != NULL) {
        //         $sql_site="SELECT p_name FROM customer_site WHERE id='$site_id'";
        //         $query_site=mysqli_query($conn,$sql_site);
        //         $fetch_site=mysqli_fetch_array($query_site);
        //         $site_name=$fetch_site['p_name'];
        //         } else {$site_name = 'No Site';}
          ?>  
         <!--<tr>-->
         <!--     <td>INV <?php // echo sprintf("%05d",$row["id"]);?></td>-->
         <!--     <td><?php // echo $row["date"];?></td>-->
         <!--     <td><?ph //p echo $site_name;?></td>-->
              <!--<td><?php // echo $cust;?></td>-->
         <!--     <td><?php // echo $row["lpo"];?></td>-->
         <!--     <td style="text-align: right;"><?php // echo $row["total"];?>-->
         <!--                <?php // $ttotal =  $ttotal+$row["total"];?>-->
         <!--     </td>-->
         <!--     <td style="text-align: right;"><?php // echo $row["received"];?>-->
         <!--                <?php // $treceived =  $treceived+$row["received"];?>-->
         <!--     </td>-->
         <!--     <td style="text-align: right;"><?php // echo custom_money_format("%!i",$balance);?>-->
         <!--                <?php // $tbalance =  $tbalance+$balance;?>-->
         <!--     </td>-->
         <!--     <td style="text-align: right;">-->
         <!--         <?php // echo custom_money_format("%!i",$tbalance);?>-->
         <!--     </td>-->
         <!-- </tr>-->
		<?php
		  //  } $sl=$sl+1; }
		?>
		<!--OLD ENDS HERE----------------------------------------------------->
		
		<!--NEW STARTS HERE--------------------------------------------------->
		<?php
		  while($row = mysqli_fetch_assoc($result)) {
               $invoice=$row['invoice'];
               $balance=$row["balance"];
                $sql_invo = "SELECT `grand` FROM `invoice` WHERE id='$invoice'";
                $query_invo = mysqli_query($conn,$sql_invo);
                $result_invo = mysqli_fetch_array($query_invo);
                $invoice_amount = $result_invo['grand'];
                $invoice_amount = ($invoice_amount != NULL) ? $invoice_amount : 0;
                
                $receipted_amount = $invoice_amount-$balance;

                $site_id = $row["site"];
                if($site_id != NULL) {
                $sql_site="SELECT p_name FROM customer_site WHERE id='$site_id'";
                $query_site=mysqli_query($conn,$sql_site);
                $fetch_site=mysqli_fetch_array($query_site);
                $site_name=$fetch_site['p_name'];
                } else {$site_name = 'No Site';}
          ?>  
         <tr>
              <td>INV <?php echo sprintf("%05d",$invoice);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $site_name;?></td>
              <td><?php echo $row["lpo"];?></td>
              <td style="text-align: right;"><?php echo $invoice_amount;?>
                         <?php $ttotal =  $ttotal+$invoice_amount;?>
              </td>
              <td style="text-align: right;"><?php echo $receipted_amount;?>
                         <?php $treceived =  $treceived+$receipted_amount;?>
              </td>
              <td style="text-align: right;"><?php echo custom_money_format("%!i",$balance);?>
                         <?php $tbalance =  $tbalance+$balance;?>
              </td>
              <td style="text-align: right;">
                  <?php echo custom_money_format("%!i",$tbalance);?>
              </td>
          </tr>
		<?php
		     $sl=$sl+1; }
		?>
		<!--NEW ENDS HERE------------------------------------------------------->
            <tr>
                <td colspan="3"></td>
                <td colspan="1" style="text-align:right;"><b>Total</b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$ttotal);?></b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$treceived);?></b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$tbalance);?></b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$tbalance);?></b></td>
            </tr>
        <?php } ?>

        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>