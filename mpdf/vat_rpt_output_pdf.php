<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
  ?> 
<!--<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>-->
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>OUTPUT VAT REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <?php if($customer !=NULL){ ?>
               <span style="font-size:15px;">Supplier: <?php echo $cust;?></span>
               <?php } ?>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;">Date : <?php echo $fdate;?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  Date
              </th>
              <th style="text-align: center;">
                   Invoice
              </th>
              <th style="text-align: center;">
                  Supplier
              </th>
              <th style="text-align: center;">
                  TRN
              </th>
              <th style="text-align: center;">
                  Amount
              </th>
              <th style="text-align: center;">
                  VAT
              </th>
              <th style="text-align: center;">
                  Total
              </th>
          </tr>
        </thead>
        <tbody>    
	<?php
         $sql = "SELECT `shop` as customer, `name` as customername, `tin` as tin, `date` as date, `expenses`.`id` as expenses, `expenses`.`inv` as invoice, `amt` as amount, `vat` as vat, `amount` as total
                   FROM `expenses` INNER JOIN `customers` ON `customers`.`id` = `expenses`.`shop` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY `expenses` ORDER BY `expenses`";
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        $tamount = 0;
        $tvat = 0;
        $ttransport = 0;
        $grand = 0;
        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
        ?>
    
          <tr>
             <td><?php echo $sl;?></td>
             
             <td style="text-align: right;"><?php
                echo $date = $row["date"];
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $invoice = $row["invoice"];
             ?></td>
             
             <td style="text-align: center;"><?php
                  echo $customer = $row["customername"];
             ?></td>
             
             <td style="text-align: center;"><?php
                echo $trn = $row["tin"];
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $amount = $row["amount"];
                $tamount = $tamount + $amount;
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $vat = $row["vat"];
                $tvat = $tvat + $vat;
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $total = $row["total"];
                $grand = $grand + $total;
             ?></td>
          </tr>

        <?php $sl++; } } ?>
          <tr>
              <td colspan="4"></td>
              <td colspan="1" style="text-align: right;"><b>Total</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tamount);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tvat);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $grand);?></b></td>   
          </tr>
        </tbody>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>