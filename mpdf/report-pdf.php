<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$lot=$_GET["lot"];
  ?>

<?php
$sqlitem="SELECT item,COC_No from batches_lots where batch='$lot'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item=$fetchitem['item'];
               $coc=$fetchitem['COC_No'];


               $sqlitem1="SELECT items from items where id='$item'";
               $queryitem1=mysqli_query($conn,$sqlitem1);
               $fetchitem1=mysqli_fetch_array($queryitem1);
               $item1=$fetchitem1['items'];  
?>

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?> BATCH REPORT </h1>
<table width="100%">
     <tr>
          <td width="62%"><h4 style=""><span>Batch No: <?php echo $lot;?></span><br><span>COC No: <?php echo $coc;?></span></h4></td>
          <td width="38%"><span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

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
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $total=0;
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
		}
                
                }
                }}
		?>
        </tbody>
<!--              <td colspan="4"></td>
              <td colspan="2"><b>Total</b></td>
              <td colspan="1"><b><?php //echo $totaldue;?> Dhs</b></td>
              <td colspan="1"><b><?php //echo $totalpaid;?> Dhs</b></td>
              <td colspan="1"><b>Balance</b></td>
              <td colspan="1"><b><?php //echo $totaldue-$totalpaid;?> Dhs</b></td>-->
       <tr><td></td><td></td><td></td><td></td><td>Total</td><td> <?php echo $total;?></td><td></td><td></td> </tr>   
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>