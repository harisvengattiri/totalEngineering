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
<!--<title> <?php //echo $title;?></title>-->
<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page { size: auto;  margin: 0mm; }
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
    
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
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: -3%;"><?php // echo strtoupper($status);?> SALES SUMMARY REPORT</h1></center>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table width="100%">
        <thead>
          <tr>    
              <th id="hitem">
                  Sold Items
              </th>
              
              <th id="hquan">
                  Quantity
              </th>
              <th id="hprice">
                  Average Price
              </th>
              <th>
                  Item Total
              </th>
              <th>
                  Sum Total
              </th>
              <th>
                  VAT
              </th>
              <th>
                  Grand Total
              </th>
                    
          </tr>
        </thead>
        <tbody>
		<?php
        $sql = "SELECT *,customers.id AS cid,sum(transport) AS transport,sum(total) AS total FROM delivery_note INNER JOIN customers ON delivery_note.customer=customers.id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY delivery_note.customer";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grandtotal=$tot_total=$tot_vat=0;
        // $tquantity=0;
        $tquantity = [];
        while($row = mysqli_fetch_assoc($result)) {
        $name1=$row["cid"];
        $cust=$row["name"];
        $transport=$row["transport"];
        $transport=($transport != NULL) ? $transport : 0;
        $faketotal=$row["total"];
        $faketotal=($faketotal != NULL) ? $faketotal : 0;
        $total = $faketotal-$transport;
        ?>
             <tr>
                  <td colspan="3" id="bcust"><b><?php echo $sl;?>: <?php echo $cust;?></b></td>
                  <td colspan="4"><b></b></td>
             </tr>
             <tr> 
               <td id="bitem">
                    <?php
                      $sql1 = "SELECT item,SUM(thisquantity) AS quant,AVG(price) AS average,SUM(amt) AS amount FROM delivery_note INNER JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND delivery_note.customer='$name1' GROUP BY delivery_item.item ORDER BY delivery_item.item";
                      $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                              $item=$row1["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              echo $item1=$fetchitem['items'].'<br>';
                         $quantity[]=$row1['quant'];
                         $avg[]=$row1['average'];
                         $amount[]=$row1['amount'];
                         }}
                    ?>
              </td>
               <td id="bquan">
                    <?php   
                    foreach($quantity as $quantity) {
                         echo $quantity.'<br>';
                         if (is_array($tquantity)) {
                            if($quantity != NULL) { $tquantity[] = $quantity; }
                          }
                    }
                    unset($quantity);
                    ?>
               </td>
               <td id="bprice">
                    <?php 
                      foreach ($avg as $avg){
                          echo number_format($avg, 2, '.', '').'<br>';
                    }
                    unset($avg);
                    ?>
               </td>
               <td>
                  <?php
                      foreach ($amount as $amount){
                         echo number_format($amount, 2, '.', '').'<br>';
                    }
                    unset($amount);
                  ?>
               </td>
               <td>
                  <?php echo number_format($total, 2, '.', ''); ?>
               </td>
               <td>
                  <?php $vat = $total*0.05; 
                     echo number_format($vat, 2, '.', '');
                  ?>
               </td>
               <td>
                  <?php $grand=$vat+$total; 
                     echo number_format($grand, 2, '.', '');
                  ?>  
               </td>
               
          </tr>
		<?php
                    $sl++;
                    $tot_total = $tot_total + $total;
                    $tot_vat = $tot_vat + $vat;
                    $grandtotal = $grandtotal+$grand;

                    if (is_array($tquantity)) {
                        $tquantity = array_sum($tquantity);
                      }
		}
		}
		?>
             <tr>
                  <td colspan="1"><b></b></td>
                  <td colspan="1"><b><?php echo number_format($tquantity, 2, '.', '');?></b></td>
                  <td colspan="2"><b></b></td>
                  <!--<td colspan="1"><b>GRAND TOTAL</b></td>-->
                  <td colspan="1"><b>Total<br><?php echo number_format($tot_total, 2, '.', '');?></b></td>
                  <td colspan="1"><b>Total VAT<br><?php echo number_format($tot_vat, 2, '.', '');?></b></td>
                  <td colspan="1"><b>Grand Total<br><?php echo number_format($grandtotal, 2, '.', '');?></b></td>
             </tr>
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>