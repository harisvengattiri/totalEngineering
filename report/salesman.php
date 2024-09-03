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
$sr=$_POST["sales_rep"];

               $sqlrep="SELECT name from customers where id='$sr'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
               
$item=$_POST["item"];
if(!empty($item))
              {
              $item_sql = "AND order_item.item = '".$item."'";
              }              
               
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

<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>ITEM ORDER REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">SalesMan: <?php echo $rep;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
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
                   $sql = "SELECT *,SUM(quantity) quantity,SUM(total) total,AVG(unit) price FROM sales_order INNER JOIN order_item ON sales_order.id = order_item.item_id WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND salesrep='$sr' ".$item_sql." GROUP BY order_item.item ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
    
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
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
                $tquantity=$tquantity+$quantity;
                $total=$row["total"];
                $tamount=$tamount+$total;
                $price=$row["price"];
               ?>
          <tr>
               <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
               <td><?php echo $quantity;?></td>
               <td><?php echo custom_money_format("%!i", $price);?></td>
               <td style="float:right;"><?php echo custom_money_format("%!i", $total);?></td>
              
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
                    <td colspan="1" style="float:right;"><b><?php echo custom_money_format("%!i", $tamount);?></b></td>
                    </tr>

      </table>
<?php
if(isset($_POST['print']))
{ ?>
<body onload="window.print()">
<?php } ?>