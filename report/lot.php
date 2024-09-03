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
$lot=$_POST["lot"];
    if($fdate != NULL && $tdate != NULL) {
        $date_sql = "AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    } else {
        $date_sql = '';
    }

    $sqlitem="SELECT quantity,COC_No from batches_lots where batch='$lot'";
    $queryitem=mysqli_query($conn,$sqlitem);
    $fetchitem=mysqli_fetch_array($queryitem);
    $lot_quantity=$fetchitem['quantity'];
    $coc=$fetchitem['COC_No'];
}
?>
<style type = "text/css">
      @media print {
           @page { size: auto;  margin: 0mm; }
      }
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
tr:nth-child(even){background-color: #f2f2f2;}
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>

<center> <h1 style="margin-bottom: -3%;">BATCH REPORT </h1></center>
<h3 style="float:left;">Batch No: <?php echo $lot;?><br>
<span> Batch Qty: <?php echo $lot_quantity;?></span><br>
<span> COC No: <?php echo $coc;?></span></h3>

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
                  Delivery No
              </th>
              <th>
                  Contractor
              </th>
              <th>
                  Site
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Price
              </th>
               <th>
                  Amount
              </th> 
          </tr>

        </thead>
        <tbody>

		<?php       
        $sql1 = "SELECT * FROM delivery_item where batch='$lot' $date_sql AND thisquantity != ''";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) > 0) 
	    {
        $sl = 1; $t_quantity = 0; $t_amount = 0;
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
        $deliId=$row1['delivery_id'];
        $quantity=$row1['thisquantity'];
        $price=$row1['price'];
        $amount=$row1['amt'];

        $t_quantity = $t_quantity + $quantity;
        $t_amount = $t_amount + $amount;

            $sql_delivery = "SELECT customer,customersite FROM delivery_note where id='$deliId'";
            $result_delivery = mysqli_query($conn, $sql_delivery);
            $row_delivery = mysqli_fetch_assoc($result_delivery);

            $custId=$row_delivery["customer"];
                 $sqlcust="SELECT name from customers where id='$custId'";
                 $querycust=mysqli_query($conn,$sqlcust);
                 $fetchcust=mysqli_fetch_array($querycust);
                 $customer=$fetchcust['name'];
            $siteId=$row_delivery["customersite"];
                 $sqlsite="SELECT p_name from customer_site where id='$siteId'";
                 $querysite=mysqli_query($conn,$sqlsite);
                 $fetchsite=mysqli_fetch_array($querysite);
                 $site=$fetchsite['p_name'];
        
        ?>
          <tr>
               <td><?php echo $sl;?></td>
               <td><?php echo $row1['date'];?></td>
               
               <td><?php echo sprintf("%06d",$deliId);?></td>
               <td><?php echo $customer;?></td>
               <td><?php echo $site;?></td>
               <td><?php echo $quantity;?></td>
               <td><?php echo $price;?></td>
               <td style="text-align:right"><?php echo custom_money_format('%!i', $amount);?></td>
               
          </tr>
		<?php $sl++; } } ?>  
        </tbody>
              <td colspan="4"></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo custom_money_format('%!i', $t_quantity);?></b></td>
              <td colspan="1"></td>
              <td colspan="1" style="text-align:right"><b><?php echo custom_money_format('%!i', $t_amount);?></b></td>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>