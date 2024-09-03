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
              $customer_sql = "WHERE customer ='".$customer."'";
              

               $sqlcust="SELECT name from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
              }
if(!empty($site))
              {
              $site_sql = "AND site ='".$site."'"; 
              
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
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

<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?>PURCHASE ORDER REPORT</h1></center>
<h3 style="float:left;">Company Name: <?php echo $cust;?><br>
<?php if($site != NULL) { ?><span> Project Name: <?php echo $site1;?></span><?php } ?></h3>

<?php if(!empty($fdate)) { ?>
 <h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>
<?php } else { ?>
  <h3 style="float:right;"> Date: NIL</h3>
<?php } ?>
<table id="tbl1">
        <thead>
          <tr>
               <th>
                  Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Sales Order
              </th>
              <th>
                  LPO
              </th>
              <!--<th id="hcust">-->
              <!--    Contractor-->
              <!--</th>-->
              <?php if($site == NULL) { ?>
              <th>
                  Project
              </th>
              <?php } ?>
              
              <th>
                  items
              </th>
              <th>
                  Price
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Total
              </th>
              <th>
                  Sale
              </th>
              <th>
                  Balance
              </th>   
              <th>
                  Sale Amount
              </th>
               <th>
                  Balance Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
//          $sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' ORDER BY date";
        $sql = "SELECT *,sales_order.id AS saleid FROM sales_order INNER JOIN order_item ON"
                . " sales_order.id = order_item.item_id ".$customer_sql.$site_sql.$po_sql.$date_sql." ORDER BY saleid";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
               $sl=1;
               $tquantity=0;
               $tot_sale=0;
               $tot_amt=0;
               $tot_bal=0;
               $tot_samount=0;
        while($row = mysqli_fetch_assoc($result)) {
             $id=$row['saleid'];
             
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
             $site10=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site10'";
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
               
                        $item=$row["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                        $quantity=$row["quantity"];
                        $price=$row["unit"];
                        
                        $sqlsale="SELECT sum(thisquantity) as sale FROM delivery_item WHERE order_referance='$or' AND item='$item'";
                        $querysale=mysqli_query($conn,$sqlsale);
                        $fetchsale=mysqli_fetch_array($querysale);
                        $sale=$fetchsale['sale'];
                        
                        $balance = $quantity-$sale;
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
               <?php if($site == NULL) { ?>
               <td></td>
               <?php } ?>
                <td>
                    <?php echo $item1;?>
                </td>
                <td>
                    <?php echo $price;?>
                </td>
                <td>
                    <?php echo $quantity;?>
                </td>
                <td style="text-align: right;">
                    <?php
                    $tamount = $price*$quantity;
                    echo custom_money_format('%!i', $tamount);
                    ?>
                </td>
                <td style="text-align: right;">
                    <?php echo $sale;?>
                </td>
                <td style="text-align: right;">
                    <?php echo $balance;?>
                </td>
                 <td style="text-align: right;">
                  <?php
                  $samount=$price*$sale;
                  echo custom_money_format('%!i', $samount);
                  ?>
                </td>
                 <td style="text-align: right;">
                    <?php
                    $bamount=$price*$balance;
                    echo custom_money_format('%!i', $bamount);
                    ?>
                </td>
            </tr>
            
        <?php } else { ?>
                
            <tr>
               <td><?php echo $sl;?></td>
               <td><?php echo $row['date'];?></td>
               <td><b><?php echo $or;?></b></td>
               <td><?php echo $lpo;?></td>
               <!--<td id="bcust"><?php echo $cust;?></td> -->
               <?php if($site == NULL) { ?>
               <td><?php echo $site1;?></td>
               <?php } ?>
                <td>
                    <?php echo $item1;?>
                </td>
                <td>
                    <?php echo custom_money_format('%!i', $price);?>
                </td>
                <td>
                    <?php echo $quantity;?>
                </td>
                <td style="text-align: right;">
                    <?php
                    $tamount = $price*$quantity;
                    echo custom_money_format('%!i', $tamount);
                    ?>
                </td>
                <td style="text-align: right;">
                    <?php echo $sale;?>
                </td>
                <td style="text-align: right;">
                    <?php echo $balance;?>
                </td>
                <td style="text-align: right;">
                    <?php  $samount=$price*$sale;
                      echo custom_money_format('%!i', $samount);
                    ?>
                </td>
                <td style="text-align: right;">
                    <?php  $bamount=$price*$balance;
                     echo custom_money_format('%!i', $bamount);
                    ?>
                </td>
               <?php  $sl=$sl+1; ?>
            </tr>
        <?php } ?>
        
        <?php
        
        $tot_sale=$tot_sale+$sale;
        $tot_bal=$tot_bal+$balance;
        $tot_amt=$tot_amt+$tamount;
        $tot_samount=$tot_samount+$samount;
        $tot_bamt=$tot_bamt+$bamount;
        ?>
		<?php } } ?>
        </tbody>
        <tr>
              <td <?php if($site == NULL) { ?>colspan="7"<?php }else{ ?> colspan="6"<?php } ?>><b></b></td>
              <td><b>Total</b></td>
               <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $tot_amt);?></b></td>
               <td style="text-align: right;"><b><?php echo $tot_sale;?></b></td>
               <td style="text-align: right;"><b><?php echo $tot_bal;?></b></td>
               <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $tot_samount);?></b></td>
               <td style="text-align: right;"><b><?php echo custom_money_format('%!i', $tot_bamt);?></b></td>
            </tr>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>