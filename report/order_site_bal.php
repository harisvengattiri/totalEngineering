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
if(!empty($fdate))
              {
              $date_sql = "AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('".$fdate."', '%d/%m/%Y') AND STR_TO_DATE('".$tdate."', '%d/%m/%Y')";
              }

$customer=$_POST["company"];
$site=$_POST["site"];
if(!empty($customer))
              {
              $customer_sql = "WHERE customer ='".$customer."' AND site ='".$site."'"; 
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
           @page {margin: 0mm;}
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

<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> ORDER REPORT </h1></center>
<?php if(!empty($fdate)) { ?>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>
<?php } else { ?>
  <h3 style="float:right;"> Date: NIL</h3>
<?php } ?>
<table id="tbl1">
        <thead>
          <tr>
               <th id="hsl">
                  Sl No
              </th>
              <th id="hdate">
                  Date
              </th>
              <th id="hso">
                  Sales Order
              </th>
              <th id="hlpo">
                  LPO
              </th>
              <th id="hcust">
                  Contractor
              </th>
              <th id="hsite">
                  Project
              </th>
              
              <th id="hitem">
                  Items
              </th>
              <th id="hitem">
                  Order Quantity
              </th>
              <th id="hitem">
                  Supplied Quantity
              </th>
                      
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
//          $sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' ORDER BY date";
        $sql = "SELECT * FROM sales_order ".$customer_sql.$po_sql.$date_sql." ";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) { 
               $sl=1;
               $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
             $id=$row['id'];
             
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
             $site=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
             $rep1=$row["salesrep"];
               $sqlrep="SELECT name from customers where id='$rep1'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
               
               $lpo=$row['lpo'];
               $or=$row['order_referance'];
               
               $quantity=$row['quantity']; 
               
        ?>
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td id="bdate"><?php echo $row['date'];?></td>
               <td id="bso"><?php echo $or;?></td>
               <td id="blpo"><?php echo $lpo;?></td>
               <td id="bcust"><?php echo $cust;?></td> 
               <td id="bsite"><?php echo $site1;?></td>
               <td id="bitem">
                    <?php
                     	 $sql1 = "SELECT item,quantity FROM order_item where item_id='$id' ORDER BY item ASC";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                              $item=$row1["item"];
                              $quantity[]=$row1["quantity"];
                             
                              
                              $sqlsale="SELECT sum(thisquantity) AS sale from delivery_item where order_referance = '".$or."' AND item = '".$item."'";
                              echo $sqlsale;
                              $querysale=mysqli_query($conn,$sqlsale);
                              $fetchsale=mysqli_fetch_array($querysale);
                              
                              $sale[]=$fetchsale['sale'];
                             
                              
                              
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                         echo $item1; echo '<br>';
                         }
                         }
                    ?>
               </td>
               <td id="bitem">
                    <?php
                     foreach ( $quantity as $quantity )
                     {
                     echo $quantity . "<br/>";
                     }
                    ?>
               </td>
               <td id="bitem">
                    <?php
                      print_r($sale); 
                     foreach ( $sale as $sale )
                     {
                         
                     echo $sale . "<br/>";
                    
                     }
                     
                    ?>
               </td>
               
               
          </tr>
		<?php
                $sl=$sl+1;  
		}
		}
		?>
        </tbody>
<!--              <tr>
              <td colspan="6"><b></b></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              </tr>-->
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>