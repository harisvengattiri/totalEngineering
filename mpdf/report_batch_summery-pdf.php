<?php
  include "../config.php";
  error_reporting(0);
  ?>

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?> STOCK SUMMARY REPORT </h1>
<table width="100%">
     <tr>
          <td width="62%">
<!--               <h4 style="">
                    <span>Sales rep: <?php echo $rep;?></span>
                    <br><span>Item: <?php echo $item2;?></span>
               </h4>-->
          </td>
          <td width="38%">
               <!--<span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span>-->
          </td>
     </tr>     
</table>

<table width="100%" class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl No
              </th>
              <th>
                  Item
              </th>
              <th>
                  Batch
              </th>
              <th>
                  Date
              </th>
              <th>
                  Balance in LOT
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                
        $sqlitem="SELECT items,id from items";
        $queryitem=mysqli_query($conn,$sqlitem);
        while($fetchitem = mysqli_fetch_assoc($queryitem)) {
        
        $item=$fetchitem['id'];
        $item1=$fetchitem['items'];
                  
	$sql = "SELECT * FROM batches_lots where quantity!=0 AND item='$item' ORDER BY batch DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
               <?php$batch=$row["batch"]; ?>
               <?php$quantity=$row["quantity"]; ?>
               <?php
                 $sqllot="SELECT thisquantity FROM delivery_item WHERE batch='$batch'";
                 $resultlot=$conn->query($sqllot);
                 $delquan=0;
                 while($rowlot=$resultlot->fetch_assoc())
                 {
                    $delquan=$delquan+$rowlot['thisquantity'];
                 }
                 $sqlrtn="SELECT returnqnt FROM stock_return WHERE batch='$batch'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rtnquan=0;
                 while($rowrtn=$resultrtn->fetch_assoc())
                 {
                    $rtnquan=$rtnquan+$rowrtn['returnqnt'];
                 }
                 $lotquan=$quantity+$rtnquan-$delquan;
                 
                 if($lotquan > 0){
                 $sl=$sl+1;
        ?>
             
          <tr>
             <td width="10%"><?php echo $sl;?></td>
             <td width="30%"><?php echo $item1;?></td>
             <td width="20%"><?php echo $batch;?></td>
             <td width="20%"><?php echo $row["date"];?></td>
<!--             <td><?php // echo $quantity;?></td>
             <td><?php // echo $delquan;?></td>-->
             <td width="20%"><?php echo $lotquan;?></td>
          </tr>
		<?php
                }
		}
		}
                
                }
		?>
        </tbody>
      </table>



<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>