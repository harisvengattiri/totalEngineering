<?php
  include "../config.php";
//   error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
  ?>
  
<?php
// $sqlrep="SELECT name from customers where id='$sr'";
//                $queryrep=mysqli_query($conn,$sqlrep);
//                $fetchrep=mysqli_fetch_array($queryrep);
//                $rep=$fetchrep['name'];
?>
  

<h2 style="text-align:center;margin-bottom:-10px;"><?php // echo strtoupper($status);?>ITEM SALES SUMMERY</h2>
<table align="center" width="90%">
     <tr>
          <td width="60%"></td>
          <td width="40%"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table width="90%" class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Sale
              </th>
              <th>
                  Unit Price
              </th>
              <th style="text-align:right;">
                  Total
              </th>
          </tr>
        </thead>
        <tbody>
		<?php   
                $sql = "SELECT id,items FROM items GROUP BY items ORDER BY items ASC";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $grand=0;
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                    $id=$row['id'];
                    $item=$row["items"];
               
        ?>
          <tr>
               <?php
                    $unit=0;
                    $sql2 = "SELECT *,SUM(thisquantity) sale,SUM(amt) total,AVG(price) unit FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $row2 = mysqli_fetch_assoc($result2);
                    $sale=$row2['sale'];
                    $unit = ($row2['unit'] != NULL) ? $row2['unit'] : 0;
                    $total = ($row2['total'] != NULL) ? $row2['total'] : 0;
                    $grand = $grand + $total;
                    if($sale > 0) 
                    {
                  ?>
               
              
             <td><?php echo $sl;?></td>
             <td><?php echo $item;?></td>
             
             <td>
                  <?php
                    echo $sale;
                  ?>
             </td>
             
             <td>
                  <?php
                    echo custom_money_format("%!i", $unit);
                  ?>
             </td>
             <td align="right">
                  <?php
                    echo custom_money_format("%!i", $total);
                  ?>
             </td>
                    <?php $sl=$sl+1; } } ?>
          </tr>
		<?php
		}
		}
		?>
          <tr>
            <td colspan="3"><b></b></td>
            <td colspan="1" align="right"><b>GRAND TOTAL</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $grand);?></b></td>
          </tr>
        </tbody>
      </table>


<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print'])) { ?>
<body onload="window.print()">
<?php } ?>