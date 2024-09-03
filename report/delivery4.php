<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];

$customer=$_POST["company"];
$site=$_POST["site"];
if(!empty($customer))
              {
              $customer_sql = "AND customer ='".$customer."' AND customersite ='".$site."'"; 
              }
$driver=$_POST["driver"];
if(!empty($driver))
              {
              $driver_sql = "AND driver ='".$driver."' "; 
              }
$item=$_POST["item"];
if(!empty($item))
              {
              $item_sql = "AND item ='".$item."' ";
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
      #hprice,#bprice{width:100px;word-break: break-all;}
      #hsite,#bsite{width:200px;word-break: break-all;}
      #hitem,#bitem{width:200px;word-break: break-all;}
      #hcust,#bcust{width:300px;word-break: break-all;}
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
    font-size:13px;
}

/*tr:nth-child(even){background-color: #f2f2f2}*/
tr:nth-child(even){
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;}
tr:nth-child(odd){
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;}
</style>

<!--<center><h1>Mancon Block Factory.</h1>
     <h2><?php echo strtoupper($status);?> DELIVERY REPORT </h2>
     <h2> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h2></center>-->

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> DELIVERY REPORT </h1></center>
<!--<h3 style="float:left;">Batch No: <?php echo $lot;?><br><span> COC No: <?php echo $coc;?></span></h3>-->
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table width="100%">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  DNO
              </th>
              <th id="hcust">
                  Contractor
              </th>
              <th id="hsite">
                  Project
              </th>
              <th id="hitem">
                  Item
              </th>
              
              <th id="hquan">
                  Quantity
              </th>
              <th id="hprice">
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
                $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ".$customer_sql.$driver_sql." ";
                $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grand=0;
        $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
        $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        $site=$row["customersite"];
               $sqlsite="SELECT p_name,location from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
               

        ?>
          <tr>
               
                    <?php
                    $sql1 = "SELECT item FROM delivery_item where delivery_id='$id' and batch!='' ".$item_sql." ";
                         $result1 = mysqli_query($conn, $sql1);
                         $item='';
                         $item1='';
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                              $item=$row1["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1[]=$fetchitem['items'];
                         }
                         }
                    ?>
              
                    <?php
                     	 $sql2 = "SELECT thisquantity FROM delivery_item where delivery_id='$id' and batch!='' ".$item_sql." ";
                         $result2 = mysqli_query($conn, $sql2);
                         $thisquantity='';
                         if (mysqli_num_rows($result2) > 0) 
                         {
                         while($row2 = mysqli_fetch_assoc($result2)) 
                         { 
                         $thisquantity[]=$row2['thisquantity'];
                         $tquantity=$tquantity+$row2['thisquantity'];
                         }
                         }
                    ?>
               
                    <?php
                     	 $sql1 = "SELECT price FROM delivery_item where delivery_id='$id' and batch!='' ".$item_sql." ";
                         $result1 = mysqli_query($conn, $sql1);
                         $price1='';
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         $price1[]=$row1['price'];
                         }
                         }
                    ?>
               
                    <?php
                     	 $sql1 = "SELECT thisquantity,price FROM delivery_item where delivery_id='$id' and batch!='' ".$item_sql." ";
                         $result1 = mysqli_query($conn, $sql1);
                         $amount='';
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         $quan= $row1['thisquantity'];
                         $price= $row1['price'];
                         $amt=$quan*$price;
                         $amount=$amount+$amt;
                         }
                         }
                         $amount;
                    ?>
               
               
               <?php
                if($amount > 0){ 
                
               ?>
               
               
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf('%06d',$id);?></td>
               <!--<td><?php echo $driver;?></td>-->
               <td id="bcust"><?php echo $cust;?></td>
               <td id="bsite"><?php echo $site1;?></td>
               <td><?php
                   foreach ( $item1 as $item1 ) 
                     {
                         echo $item1 . "<br/>";
                     }
               ?></td>
               <td> <?php
                   foreach ( $thisquantity as $thisquantity ) 
                     {
                         echo $thisquantity . "<br/>";
                     }
               ?></td>
               <td><?php
                   foreach ( $price1 as $price1 )
                     {
                     echo $price1 . "<br/>";
                     } 
               ?></td>
               <td><?php echo $amount;?>
                   <?php $grand=$grand+$amount;?>
               </td>
               
                <?php $sl=$sl+1; } ?>
              
          </tr>
		<?php
		}
		}
		?>
        </tbody>
              <tr>
                   <td colspan="6"></td>
                   <td colspan="1"><b><?php echo $tquantity;?></b></td>
                   <td colspan="1"><b>Grand Total</b></td>
                   <td colspan="1"><b><?php echo $grand;?></b></td>
              </tr>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>