<?php
  include "../config.php";
  error_reporting(0);
  
  $item=$_GET["item"];
  ?>
  <?php
     $sl=1;
     $sqlitem="SELECT items from items where id='$item'";
     $queryitem=mysqli_query($conn,$sqlitem);
     $fetchitem=mysqli_fetch_array($queryitem);
     $item1=$fetchitem[items];
  ?>
<h2 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?>STOCK REPORT</h2>
<table align="center" width="90%">
     <tr>
          <td width="60%">
              <?php $today=date('d/m/Y');?>
          </td>
          <td width="40%" style="text-align: right;"><span style="font-size:15px;"> Date:<?php echo $today;?></span></td>
     </tr>     
</table>


<table width="90%" align="center" class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl No
              </th> 
              <th>
                  Item
              </th>
              <th>
                  NON COC
              </th>
              <th>
                  COC
              </th>
              <th>
                  TOTAL STOCK
              </th>
          </tr>
        </thead>
        <?php if($item!=''){ ?>
        <tbody>
	<?php
	$sql = "SELECT SUM(quantity) quantity FROM batches_lots where item='$item' GROUP BY item";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        $quantity=$row["quantity"];  
          
        $sqlprod="SELECT SUM(quantity) prodquan FROM prod_items WHERE item='$item'";
        $resultprod=$conn->query($sqlprod);
        $rowprod=$resultprod->fetch_assoc();
        $prodquan=$rowprod['prodquan'];
        
        $balquan=$prodquan-$quantity;
        
                 $sqllot="SELECT SUM(thisquantity) thisquantity FROM delivery_item WHERE item='$item'";
                 $resultlot=$conn->query($sqllot);
                 $rowlot=$resultlot->fetch_assoc();
                 $delquan=$rowlot['thisquantity'];
                 
                 $sqlrtn="SELECT SUM(returnqnt) returnqnt FROM stock_return WHERE item='$item'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rowrtn=$resultrtn->fetch_assoc();
                 $rtnquan=$rowrtn['returnqnt'];
         
                 $lotquan=$quantity+$rtnquan-$delquan;
                 
         $stock = $balquan + $lotquan;      
        ?>
             
          <tr>
             <td colspan="1">
             <b> <?php echo $sl;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $item1;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $balquan;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $lotquan;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $stock;?></b>
             </td>
        </tr>
		<?php
                
		}
		}
		?>
        </tbody>
        <?php } else { ?>
        <tbody>
	<?php
	$sql2 = "SELECT item,SUM(quantity) quan FROM batches_lots GROUP BY item ORDER BY CAST(item AS unsigned)";
        $result2 = mysqli_query($conn, $sql2);
        if (mysqli_num_rows($result2) > 0) 
	{
        while($row2 = mysqli_fetch_assoc($result2)) {
        $item=$row2["item"];
        $sqlitem="SELECT items from items where id='$item'";
          $queryitem=mysqli_query($conn,$sqlitem);
          $fetchitem=mysqli_fetch_array($queryitem);
          $item1=$fetchitem[items];
        $quan=$row2["quan"];  
          
        $sqlprod="SELECT SUM(quantity) prodquan FROM prod_items WHERE item='$item'";
        $resultprod=$conn->query($sqlprod);
        $rowprod=$resultprod->fetch_assoc();
        $prodquan=$rowprod['prodquan'];
        
        $balquan=$prodquan-$quan;
        
                 $sqllot="SELECT SUM(thisquantity) thisquantity FROM delivery_item WHERE item='$item'";
                 $resultlot=$conn->query($sqllot);
                 $rowlot=$resultlot->fetch_assoc();
                 $delquan=$rowlot['thisquantity'];
                 
                 $sqlrtn="SELECT SUM(returnqnt) returnqnt FROM stock_return WHERE item='$item'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rowrtn=$resultrtn->fetch_assoc();
                 $rtnquan=$rowrtn['returnqnt'];
         
                 $lotquan=$quan+$rtnquan-$delquan;
                 
        $stock = $balquan + $lotquan;
        ?>
        <tr>
             <td colspan="1">
             <b> <?php echo $sl;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $item1;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $balquan;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $lotquan;?></b>
             </td>
             <td colspan="1">
             <b> <?php echo $stock;?></b>
             </td>
             <?php $sl=$sl+1; ?>
        </tr>
          <?php }} ?>
        </tbody>
        <?php } ?>
      </table>


<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>