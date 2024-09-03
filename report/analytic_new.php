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
}
?>

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
<center> <h1 style="margin-bottom: -3%;"> SALES SUMMARY REPORT</h1></center>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table width="100%">
        <thead>
          <tr>  
          <th>
            Sl No
          </th>
              <th>
                  Sold Items
              </th>
              
              <th style="text-align:center">
                  Quantity
              </th>
              <th style="text-align:center">
                  Average Price
              </th>
              <th style="text-align:center">
                  Item Total
              </th>
              <th style="text-align:center">
                  VAT
              </th>
              <th style="text-align:center">
                  Total Amount
              </th>   
          </tr>
        </thead>
        <tbody>

        <?php
        $sql = "SELECT customers.id AS cid, customers.name AS customer_name,delivery_item.item,items.items AS itemName,
               SUM(delivery_item.thisquantity) AS quant,
               FORMAT(AVG(delivery_item.price), 2) AS average,
               ROUND(SUM(delivery_item.amt), 2) AS amount
        FROM delivery_note
        INNER JOIN customers ON delivery_note.customer = customers.id
        INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id
        INNER JOIN items ON delivery_item.item = items.id
        WHERE STR_TO_DATE(delivery_note.date, '%d/%m/%Y')
        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
        GROUP BY customers.id, delivery_item.item
        ORDER BY customers.id, delivery_item.item";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $sl = 1;
            $current_customer = null;
            $total_amount = 0;
            $grand_vat = 0;
            $grand_total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($current_customer !== $row['cid']) { 
                    if ($current_customer !== null) { ?>
                        <tr>
                            <td colspan='3'></td>
                            <td style="text-align:right;"><b>Total Amount : </b></td>
                            <td style="text-align:right;"><b><?php echo number_format($total_amount, 2);?></b></td>
                            <td style="text-align:center;"><b><?php echo number_format($vat, 2);?></b></td>
                            <td style="text-align:center;"><b><?php echo number_format($sub_total, 2);?></b></td>
                        </tr>
                        <?php
                          $grand += $total_amount;
                          $grand_vat += $vat;
                          $grand_total += $sub_total;
                          $total_amount = 0;
                        }
                        ?>
                        <tr>
                            <td><b><?php echo $sl;?></b></td>
                            <td colspan='6'><b><?php echo $row['customer_name'];?></b></td>
                        </tr>
                <?php $current_customer = $row['cid'];$sl++; }
        ?>
        <tr>
            <td></td>
            <td><?php echo $row['itemName'];?></td>
            <td style="text-align:right;"><?php echo $row['quant'];?></td>
            <td style="text-align:right;"><?php echo $row['average'];?></td>
            <td style="text-align:right;"><?php echo $row['amount'];?></td>
            <td colspan='2'></td>
        </tr>
        
        <?php $total_amount += $row['amount'];
              $vat = $total_amount*0.05;
              $sub_total = $total_amount*1.05;
        }

              $vat_last = $total_amount * 0.05;
              $sub_total_last = $total_amount * 1.05;
              $grand += $total_amount;
              $grand_vat += $vat_last;
              $grand_total += $sub_total_last;
            echo "<tr>
                    <td colspan='3'></td>
                    <td style='text-align:right'><b>Total Amount : </b></td>
                    <td style='text-align:right'><b>".number_format($total_amount, 2)."</b></td>
                    <td style='text-align:center'><b>".number_format($vat_last, 2)."</b></td>
                    <td style='text-align:center'><b>".number_format($sub_total_last, 2)."</b></td>
                  </tr>";
        } ?>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"></td>
                <td style='text-align:right'><b>Grand Total : </b></td>
                <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$grand);?></b></td>
                <td colspan="1" style="text-align:center;"><b><?php echo custom_money_format("%!i",$grand_vat);?></b></td>
                <td colspan="1" style="text-align:center;"><b><?php echo custom_money_format("%!i",$grand_total);?></b></td>
            </tr>
        </tfoot>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>