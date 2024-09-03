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

$customer=$_POST["company"];
if(!empty($customer))
              {
              $customer_sql = "AND customer ='".$customer."'"; 
              }
$site=$_POST["site"];
if(!empty($site))
              {
              $site_sql = "AND customersite ='".$site."'"; 
              }
              
$driver=$_POST["driver"];
if(!empty($driver))
              {
              $driver_sql = "AND driver ='".$driver."' "; 
              }
$item=$_POST["item"];
if(!empty($item))
              {
              $item_sql = "AND delivery_item.item ='".$item."' ";
              }
$po=$_POST["po"];
if(!empty($po))
              {
              $po_sql = "AND lpo ='".$po."' ";
              }
}
?>
<!--<title> <?php //echo $title;?></title>-->
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
    /*border-top: 1px solid #ccc;*/
    margin: 1em 0;
    padding: 0;}
tr:nth-child(odd){
    height: 1px;
    border: 0;
    /*border-top: 1px solid #ccc;*/
    margin: 1em 0;
    padding: 0;}
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> DELIVERY REPORT </h1></center>
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
              <th>
                  P.O
              </th>
              <th style="width:18%;">
                  Contractor
              </th>
              <th style="width:18%;">
                  Project
              </th>
              <th>
                   Driver
              </th>
              <th style="width:15%;">
                  Item
              </th>
              <th>
                  COC
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
//                $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ".$customer_sql.$site_sql.$driver_sql.$po_sql." ORDER BY id";
                
                $sql = "SELECT *,delivery_note.id as dno,delivery_note.order_referance as o_r FROM delivery_note INNER JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id"
                        . " WHERE STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')".$customer_sql.$site_sql.$driver_sql.$po_sql.$item_sql." ORDER BY delivery_note.id";
                
                $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['dno'];
        
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
        $dri=$row["driver"];
               $sqldri="SELECT name from customers where id='$dri'";
               $querydri=mysqli_query($conn,$sqldri);
               $fetchdri=mysqli_fetch_array($querydri);
               $driver=$fetchdri['name'];
        $item = $row['item'];
               $sqlitem="SELECT items from items where id='$item'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item1=$fetchitem['items'];
        ?>
        
        <?php
        $b = $a;
        $a = $id;
        
        if($a == $b)
        {
        ?>
            <tr>  
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>
               <td></td>

               <td><?php echo $item1;?></td>
               <td><?php echo $row['coc'];?></td>
               <td><?php echo $row['thisquantity'];
                   $tquantity[] = $row['thisquantity'];
               ?>
               </td>
               <td><?php echo $row['price'];?></td>
               <td><?php echo $row['amt'];
                    $grand[] = $row['amt'];
               ?>
               </td>
           </tr>
        <?php } else { ?>
        
          <tr>  
               <td><?php echo $sl;?></td>
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf('%06d',$id);?></td>
               <td><?php echo $row['o_r'];?></td>
               <td id="bcust"><?php echo $cust;?></td>
               <td id="bsite"><?php echo $site1;?></td>
               <td><?php echo $driver;?></td>

               <td><?php echo $item1;?></td>
               <td><?php echo $row['coc'];?></td>
               <td><?php echo $row['thisquantity'];
                    $tquantity[] = $row['thisquantity'];
               ?>
               </td>
               <td><?php echo $row['price'];?></td>
               <td><?php echo $row['amt'];
                    $grand[] = $row['amt'];
               ?>
               </td>
 
               <?php $sl=$sl+1; ?>
          </tr>
        <?php } ?>   
          
		<?php
		}
		}
		?>
        </tbody>
              <tr>
                   <td colspan="9"></td>
                   <td colspan="1"><b><?php echo array_sum($tquantity);?></b></td>
                   <td colspan="1"><b>Grand Total</b></td>
                   <td colspan="1"><b><?php echo array_sum($grand);?></b></td>
              </tr>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>