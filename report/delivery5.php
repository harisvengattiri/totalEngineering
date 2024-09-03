<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];

$fdate='25/06/2019';
$tdate='27/06/2019';

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
              $item_sql = "AND item ='".$item."' ";
              }
$po=$_POST["po"];
if(!empty($po))
              {
              $po_sql = "AND lpo ='".$po."' ";
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
              <th>
                  P.O
              </th>
              <th id="hcust">
                  Contractor
              </th>
              <th id="hsite">
                  Project
              </th>
              <th>
                   Driver
              </th>
              <th id="hitem">
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
//		$sql = "SELECT * FROM delivery_note where date BETWEEN '$fdate' AND '$tdate' AND driver='$driver'";
                $sql = "SELECT * FROM delivery_note LEFT JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ".$customer_sql.$site_sql.$driver_sql.$po_sql." ";
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
        $dri=$row["driver"];
               $sqldri="SELECT name from customers where id='$dri'";
               $querydri=mysqli_query($conn,$sqldri);
               $fetchdri=mysqli_fetch_array($querydri);
               $driver=$fetchdri['name'];

        ?>
 
          <tr>

               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf('%06d',$id);?></td>
               <td><?php echo $row['order_referance'];?></td>
               <!--<td><?php echo $driver;?></td>-->
               <td id="bcust"><?php echo $cust;?></td>
               <td id="bsite"><?php echo $site1;?></td>
               <td><?php echo $driver;?></td>
               <td><?php
                   echo $row["item"];
               ?></td>
               <td><?php
                   foreach ( $coc1 as $coc1 ) 
                     {
                         echo $coc1 . "<br/>";
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
               <td><?php echo $amt;?>
                   <?php $grand=$grand+$amt;?>
               </td>
               
                <?php $sl=$sl+1; ?>
              
          </tr>
		<?php
		}
		}
		?>
        </tbody>
              <tr>
                   <td colspan="9"></td>
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