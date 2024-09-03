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

             
               $sqlitem="SELECT item,quantity,COC_No from batches_lots where batch='$lot'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item=$fetchitem['item'];
               $quantity=$fetchitem['quantity'];
               $coc=$fetchitem['COC_No'];


               $sqlitem1="SELECT items from items where id='$item'";
               $queryitem1=mysqli_query($conn,$sqlitem1);
               $fetchitem1=mysqli_fetch_array($queryitem1);
               $item1=$fetchitem1['items'];
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

tr:nth-child(even){background-color: #f2f2f2;}
</style>
<!--<center><h1>Mancon Block Factory.</h1>
     <h2><?php echo strtoupper($status);?> BATCH REPORT [<?php echo $lot;?>] </h2>
     <h2> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h2></center>-->

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: -3%;"><?php // echo strtoupper($status);?> BATCH REPORT </h1></center>
<h3 style="float:left;">Batch No: <?php echo $lot;?><br>
<span> Batch Qty: <?php echo $quantity;?></span><br>
<span> COC No: <?php echo $coc;?></span></h3>
<?php if($tdate!=''){?>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>
<?php } ?>
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
//		$sql = "SELECT * FROM delivery_note where date BETWEEN '$fdate' AND '$tdate' AND driver='$driver'";
       
        if($tdate != '')
        {        
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $total=0;
        $tamount=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
        $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        $site=$row["customersite"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
        
               
        $sql1 = "SELECT * FROM delivery_item where batch='$lot' and delivery_id='$id'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) > 0) 
	    {
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
        $price=$row1['price'];
        $quantity=$row1['thisquantity'];
        $total=$total+$quantity;
        $amount=$price*$quantity;
        $tamount=$tamount+$amount;
               
        ?>
          <tr>
               <td><?php echo $sl;?></td>
               <td><?php echo $row['date'];?></td>
               
               <td><?php echo sprintf("%06d",$id);?></td>
               <!--<td><?php echo $item;?></td>--> 
               <td><?php echo $cust;?></td>
               <td><?php echo $site1;?></td>
               <td><?php echo $quantity;?></td>
               <td><?php echo $price;?></td>
               <td><?php echo $amount;?></td>
               
          </tr>
		<?php
                $sl=$sl+1;
		}}
                }}
                
                
         }
	 ?>
          
          <?php
          if($tdate == '')
          {
          $sql1 = "SELECT * FROM delivery_item where batch='$lot' ORDER BY delivery_id DESC";
          $result1 = mysqli_query($conn, $sql1);
          if (mysqli_num_rows($result1) > 0) 
          {
          $sl=1;
          $total=0;
          $tamount=0;
          while($row1 = mysqli_fetch_assoc($result1)) 
          {
          $did=$row1['delivery_id'];
          $price=$row1['price'];
          $quantity=$row1['thisquantity'];
          $total=$total+$quantity;
          $amount=$price*$quantity; 
          $tamount=$tamount+$amount;
          
          
          $sql = "SELECT id,customer,customersite,date FROM delivery_note where id='$did'";
          $result = mysqli_query($conn, $sql);
          if (mysqli_num_rows($result) > 0) 
          {
          while($row = mysqli_fetch_assoc($result)) {
          $id=$row['id'];

          $name1=$row["customer"];
                 $sqlcust="SELECT name from customers where id='$name1'";
                 $querycust=mysqli_query($conn,$sqlcust);
                 $fetchcust=mysqli_fetch_array($querycust);
                 $cust=$fetchcust['name'];
          $site=$row["customersite"];
                 $sqlsite="SELECT p_name from customer_site where id='$site'";
                 $querysite=mysqli_query($conn,$sqlsite);
                 $fetchsite=mysqli_fetch_array($querysite);
                 $site1=$fetchsite['p_name'];
          $date=$row['date'];
                 
          }}
          

          ?>
            <tr>
                 <td><?php echo $sl;?></td>
                 <td><?php echo $date;?></td>

                 <td><?php echo sprintf("%06d",$id);?></td>
                 <!--<td><?php echo $item;?></td>--> 
                 <td><?php echo $cust;?></td>
                 <td><?php echo $site1;?></td>
                 <td><?php echo $quantity;?></td>
                 <td><?php echo $price;?></td>
                 <td><?php echo $amount;?></td>

            </tr>
                  <?php
                  $sl=$sl+1;
                  }}   
               
          }  
          ?>
          
          
        </tbody>
              <td colspan="4"></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $total;?></b></td>
              <td colspan="1"></td>
              <td colspan="1"><b><?php echo $tamount;?></b></td>
              
           
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>