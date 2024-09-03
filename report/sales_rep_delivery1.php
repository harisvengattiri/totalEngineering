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


               $sqlrep="SELECT name from customers where id='$rep'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $repname=$fetchrep['name'];

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
<center> <h1 style="margin-bottom: -1%;"><?php echo strtoupper($status);?>SALES REPORT</h1></center>
<h3 style="float:left;">SALESMAN: <?php echo $repname;?></h3>
<h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>


<table style="width:100%;">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Amount
              </th>
              <th>
                  VAT
              </th>
              <th>
                  Total
              </th>
                      
          </tr>
        </thead>
        <tbody>
		<?php
//	$sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' AND customer='$company' AND site='$site'";
        $sql = "SELECT customer FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND rep='$rep' GROUP BY customer";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grand=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        $customer=$row['customer'];
        $sqlcust="SELECT name from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        ?>
          <tr>
               <!--<td><?php echo sprintf('%06d',$id);?></td>-->
               <td><?php echo $sl;?></td>
               <td><?php echo $cust;?></td>
             
               <td>
                    <?php
                     	 $sql1 = "SELECT id FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND rep='$rep' AND customer='$customer'";
                         $result1 = mysqli_query($conn, $sql1);
                         $total=0;
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         $dn=$row1['id'];
                              $sql2="SELECT thisquantity,price FROM delivery_item WHERE delivery_id='$dn' AND batch!=''";
                              $result2 = mysqli_query($conn, $sql2);
                              $sum=0;
                              while($row2 = mysqli_fetch_assoc($result2)) 
                              {
                               $quantity=$row2['thisquantity'];
                               $price=$row2['price'];
                               $amount=$quantity*$price;
                               $sum=$sum+$amount;
                              }
                         $total=$total+$sum;
                         }
                         }
                         echo $total;
                    ?>
               </td>
               <td>
                    <?php echo $vat=$total*0.05; ?>
               </td>
               <td>
                    <?php echo $sumtotal=$total+$vat;?>
               </td>
             
          </tr>
		<?php
                  $grand=$grand+$sumtotal;
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
        
              <td colspan="3"><b></b></td>
              <td colspan="1"><b>Grand Total</b></td>
              <td colspan="1"><b><?php echo $grand;?></b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>