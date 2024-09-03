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
$company=$_POST["cust"];


               $sqlcust="SELECT name from customers where id='$company'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];

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
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: -1%;"><?php echo strtoupper($status);?> CUSTOMER DELIVERY REPORT</h1></center>
<h3 style="float:left;">CUSTOMER: <?php echo $cust;?></h3>
<h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>


<table width="100">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
               <th>
                  Date
              </th>
              <th>
                  Delivery
              </th>
              <th>
                  Sales Order
              </th>
              <th id="hitem">
                  Items
              </th>
              <th id="hbatch">
                  Batch
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Price
              </th>
              <th>
                  Total
              </th>
                      
          </tr>
        </thead>
        <tbody>
		<?php
//	$sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' AND customer='$company' AND site='$site'";
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND customer='$company' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grand=0;
        $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf('%06d',$id);?></td>
               <td><?php echo $row['order_referance'];?></td>
               <td id="bitem">
                    <?php
                     	 $sql1 = "SELECT item FROM delivery_item where delivery_id='$id' AND batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         $item=$row1["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                         echo $item1; echo'<br>';
                         }
                         }
                    ?>
               </td>
               <td id="batch">
                    <?php
                     	 $sql1 = "SELECT batch FROM delivery_item where delivery_id='$id' AND batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         echo $row1["batch"]; echo'<br>';
                         }
                         }
                    ?>
               </td>
               <td>
                    <?php
                     	 $sql1 = "SELECT thisquantity FROM delivery_item where delivery_id='$id' AND batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         echo $row1['thisquantity']; echo'<br>';
                         $tquantity=$tquantity+$row1['thisquantity'];
                         }
                         }
                    ?>
               </td>
               <td>
                    <?php
                     	 $sql1 = "SELECT price FROM delivery_item where delivery_id='$id' AND batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         $price1=$row1['price'];
                         $price = str_replace(' ', '', $price1);
                         echo $price.'<br>';
                         }
                         }
                    ?>
               </td>
               <td>
                    <?php
                      $sql1 = "SELECT sum(amt) AS amt FROM delivery_item where delivery_id='$id' AND batch!='' GROUP BY delivery_id";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $row1 = mysqli_fetch_assoc($result1);
                         $amt=$row1['amt'];
                         echo custom_money_format("%!i", $amt);
                         }
                      
                    ?>
               </td>
          </tr>
		<?php
                  $grand=$grand+$amt;
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
        
        <td colspan="6"><b></b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              <td colspan="1"><b>Grand Total</b></td>
              <td colspan="1"><b><?php echo custom_money_format("%!i", $grand);?></b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>