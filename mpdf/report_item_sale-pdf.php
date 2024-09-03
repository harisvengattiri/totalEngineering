<?php
include "../config.php";
error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$sr=$_GET["sales_rep"];
$item=$_GET["item"];
?>
  
<?php
$sqlrep="SELECT name from customers where id='$sr'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
               
if(!empty($item))
{
$item_sql = "AND delivery_item.item = '".$item."'";
}
?>
  

<h2 style="text-align:center;margin-bottom:-10px;"><?php // echo strtoupper($status);?>ITEM SALE REPORT</h2>
<table align="center" width="90%">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">SalesMan: <?php echo $rep;?></span>
          </td>
          <td width="50%" align="right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table align="center" style="width:90%;">
        <thead id="thead">
          <tr>
              <th>
                  Sl No
              </th>
<!--              <th>
                  Date
              </th>-->
<!--              <th>
                  Sales Rep
              </th>-->
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Average Price
              </th>
              <th style="text-align:right;">
                  Amount
              </th>
            
          </tr>
        </thead>
        <tbody>
		<?php
//                   $sql = "SELECT * FROM sales_order WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND salesrep='$sr' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
                   $sql = "SELECT *,SUM(thisquantity) quantity,SUM(amt) total,AVG(price) price FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND rep='$sr' ".$item_sql." GROUP BY delivery_item.item ORDER BY STR_TO_DATE(delivery_note.date, '%d/%m/%Y') ASC";
    
                   $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $quantity=0;
                    $tquantity=0;
                    $tamount=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $name1=$row["customer"];
                    $date=$row["date"];
               if(!empty($name1)) {    
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               }
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
                $quantity = ($quantity != NULL) ? $quantity : 0;
                $tquantity = $tquantity + $quantity;
                $total=$row["total"];
                $total = ($total != NULL) ? $total : 0;
                $tamount = $tamount + $total;
                $price=$row["price"];
               ?>
          <tr>
               <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
               <td><?php echo $quantity;?></td>
               <td><?php echo custom_money_format("%!i", $price);?></td>
               <td align="right"><?php echo custom_money_format("%!i", $total);?></td>
              
          </tr>
		<?php
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
                    <tr>
                    <td colspan="2"></td>
                    <td colspan="1"><b><?php echo $tquantity;?></b></td>
                    <td colspan="1"></td>
                    <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $tamount);?></b></td>
                    </tr>

      </table>

<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>