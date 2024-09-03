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
<center> <h1 style="margin-bottom: -1%;"><?php echo strtoupper($status);?> CUSTOMER DELIVERY SUMMARY REPORT</h1></center>
<h3 style="float:left;">CUSTOMER: <?php echo $cust;?></h3>
<h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>


<table width="100">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              
              <th id="hitem">
                  Items
              </th>
              <th>
                  Sold Quantity
              </th>
              <th>
                  Average Price
              </th>
              <th>
                  Sum Total
              </th>
                      
          </tr>
        </thead>
        
        <tbody>
		<?php
                $sql = "SELECT delivery_item.item AS items FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$company' AND delivery_item.batch!='' GROUP BY delivery_item.item ORDER BY delivery_item.item"; 
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $grand=0;
                    $tquantity=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row["items"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
             <td>
                  <?php
                    $sql1="SELECT items FROM items where id='$id'";
                    $result1 = mysqli_query($conn, $sql1);
                    $row1 = mysqli_fetch_assoc($result1);
                    echo $row1["items"];
                    ?>
             </td>
             
             <td>
                  <?php
                    $sql2 = "SELECT *,SUM(thisquantity) sale FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$company' AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $row2 = mysqli_fetch_assoc($result2);
                    echo $sale=$row2['sale'];
                         $tquantity=$tquantity+$row2['sale'];
                    if($sale<1){ echo '0';}
                    }
                  ?>
             </td>
             <td>
                  <?php
                    $sql2 = "SELECT *,SUM(price) tprice,COUNT(price) entry FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$company' AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $row2 = mysqli_fetch_assoc($result2);
                    $tprice=$row2['tprice'];
                    $count=$row2['entry'];
                    echo $price=$tprice/$count;
                    if($price<1){ echo '0';}
                    }
                  ?>
             </td>
             <td>
                  <?php
                    $sql2 = "SELECT * FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$company' AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    $sumtotal=0;
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                    $quan=$row2['thisquantity'];
                    $price=$row2['price'];
                    $sum=$quan*$price;
                    $sumtotal=$sumtotal+$sum;
                    }
                    }
                    echo $sumtotal;
                    
                  ?>
             </td>
          </tr>
		<?php
                $sl=$sl+1;
                $grand=$grand+$sumtotal;
		}
		}
		?>
        </tbody>
        
        
        <td colspan="2"><b></b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              <td colspan="1"><b>Grand Total</b></td>
              <td colspan="1"><b><?php echo $grand;?></b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>