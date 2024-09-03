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
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> DELIVERY SUMMARY REPORT</h1></center>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table width="100%">
        <thead>
          <tr>
<!--              <th>
                  Sl No
              </th>
              
              <th id="hcust">
                  Contractor
              </th>-->
              
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
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY customer ORDER BY customer";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grandtotal=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
        $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        ?>
             <tr>
                  <td colspan="3" id="bcust"><b><?php echo $sl;?>: <?php echo $cust;?></b></td>
                  <td colspan="4"><b></b></td>
             </tr>
             <tr> 
               <td id="bitem">
                    <?php
                      $sql1 = "SELECT *,SUM(thisquantity) AS quant,AVG(price) AS average FROM delivery_note INNER JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND delivery_note.customer='$name1' and delivery_item.batch!='' GROUP BY delivery_item.item ORDER BY delivery_item.item";
                      $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         $rid=$row1["id"];
                              $item=$row1["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                         echo $item1; echo '<br>';
                         
                         }}
                    ?>
              </td>
               
               
               <td id="bquan">
                    <?php
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         echo $row1['quant']; echo '<br>';
                         }}
                         
                    ?>
               </td>
               
               <td id="bprice">
                    <?php
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         $avg=$row1['average'];
                         echo(round($avg,2)).'<br>';
                         }}
                         
                    ?>
               </td>
               
               <td>
                  <?php
                    
                    $sql3 = "SELECT item FROM delivery_note INNER JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE"
                            . " STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') "
                            . "AND delivery_note.customer='$name1' and delivery_item.batch!='' GROUP BY delivery_item.item ORDER BY delivery_item.item";
                    
                    $result3 = mysqli_query($conn, $sql3);
                    while($row3 = mysqli_fetch_assoc($result3))
                    {
                    $item=$row3['item'];
                    
                    $sql2 = "SELECT * FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$name1' AND delivery_item.batch!='' AND delivery_item.item='$item'";
                    
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
                    echo $sumtotal.'<br>';
                    }
                  ?>
               </td>
               
               
               <td>
                  <?php
                    $sql2 = "SELECT * FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$name1' AND delivery_item.batch!=''";
                    
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
               <td>
                  <?php echo $vat=0.05*$sumtotal; ?>
               </td>
               <td>
                  <?php echo $grand=$vat+$sumtotal; ?>  
               </td>
               
          </tr>
		<?php
                  $sl=$sl+1;
                  $grandtotal=$grandtotal+$grand;
		}
		}
		?>
             <tr>
                  <td colspan="5"><b></b></td>
                  <td colspan="1"><b>GRAND TOTAL</b></td>
                  <td colspan="1"><b><?php echo $grandtotal;?></b></td>
             </tr>
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>