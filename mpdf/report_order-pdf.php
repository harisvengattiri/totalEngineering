<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$rep=$_GET["rep"];
$item2=$_GET["item"];
$customer=$_GET["company"];
$site=$_GET["site"];
$po=$_GET["po"];

              if(!empty($rep))
              {
              $rep_sql = "AND salesrep='$rep'"; 
              }
              else {   $rep_sql = ''; }
             
              if(!empty($item2))
              {
              $item_sql = "AND order_item.item='$item2'";
              }
              else {   $item_sql = ''; }
              
if(!empty($customer))
              {
              $customer_sql = "AND customer ='".$customer."'"; 
              }
if(!empty($site))
              {
              $site_sql = " AND site ='".$site."'"; 
              }
if(!empty($po))
              {
              $po_sql = "AND lpo ='".$po."' ";
              }
  ?>

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?>COMPLETE ORDER REPORT</h1>
<table width="100%">
     <tr>
          <td width="62%">
<!--               <h4 style="">
                    <span>Sales rep: <?php echo $rep;?></span>
                    <br><span>Item: <?php echo $item2;?></span>
               </h4>-->
          </td>
          <td width="38%"><span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table width="100%">
 <thead>
          <tr>
               <th id="hsl" width="10%">
                  Sl No
              </th>
              <th id="hdate" width="10%">
                  Date
              </th>
              <th id="hso" width="10%">
                  Sales Order
              </th>
              <th id="hlpo" width="5%">
                  LPO
              </th>
              <th id="hcust" width="10%">
                  Contractor
              </th>
              <th id="hsite" width="10%">
                  Project
              </th>
              
              <th id="hitem" width="10%">
                  items
              </th> 
              <th id="hquan" width="10%">
                  Quantity
              </th>
<!--              <th>
                  Sales Person
              </th>-->
                      
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
//          $sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' ORDER BY date";
        $sql = "SELECT *,sales_order.id AS saleid, order_item.id AS iid FROM sales_order INNER JOIN order_item ON sales_order.id = order_item.item_id WHERE STR_TO_DATE(date, '%d/%m/%Y')"
                  . " BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ".$customer_sql.$site_sql.$po_sql.$rep_sql.$item_sql." GROUP BY sales_order.id  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) 
		{ $sl=1;
                  $tquantity;
        while($row = mysqli_fetch_assoc($result)) {
             $id=$row['saleid'];
             
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
               <td id="bsl" width="3%"><?php echo $sl;?></td>
               <td id="bdate" width="10%"><?php echo $row['date'];?></td>
               <td id="bso" width="10%"><?php echo $or;?></td>
               <!--<td id="blpo"><p style="width:5%;word-break: break-all;"><?php echo $lpo;?></p></td>-->
               <!--<td id="blpo"><?php echo wordwrap($lpo,15,"<br>\n");?></td>-->
               <td id="blpo" width="10%">
               <?php
               echo wordwrap($lpo, 10, "\n", true);
               ?>
               </td>
               
               <td id="bcust" width="15%"><?php echo $cust;?></td> 
               <td id="bsite" width="20%"><?php echo $site1;?></td>
               
               <?php if(!empty($item2)) {
                    $sqlitem="SELECT items from items where id='$item2'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item3=$fetchitem['items'];
                    $tquantity=$tquantity+$quantity;
               ?>
               <td id="bitem" width="20%"><?php echo $item3;?></td>
               <td id="bquan" width="10%"><?php echo $quantity;?></td>
               <?php } else { ?>
               <td id="bitem" width="20%">
                    <?php
                     	 $sql1 = "SELECT item FROM order_item where item_id='$id'";
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
                         echo $item1; echo '<br>';
                         }
                         }
                    ?>
               </td>
               <td id="bquan" width="10%">
                    <?php
                     	 $sql2 = "SELECT quantity FROM order_item where item_id='$id'";
                         $result2 = mysqli_query($conn, $sql2);
                         if (mysqli_num_rows($result2) > 0) 
                         {
                         while($row2 = mysqli_fetch_assoc($result2)) 
                         { 
                         echo $row2['quantity']; echo '<br>';
                         $tquantity=$tquantity+$row2['quantity'];
                         }
                         }
                    ?>
               </td>
               <?php } ?>
          </tr>
		<?php
                $sl=$sl+1;  
		}
		}
		?>
              <tr>
              <td colspan="6"><b></b></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              </tr>
        </tbody>  
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>