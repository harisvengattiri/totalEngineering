<?php
  include "../config.php";
  error_reporting(0);
  ?>

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?> NON COC PRODUCTION REPORT </h1>
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
                  Balance in production
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                
        $sqlitem="SELECT items,id from items";
        $queryitem=mysqli_query($conn,$sqlitem);
        $sl=1;
        while($fetchitem = mysqli_fetch_assoc($queryitem)) {
        
        $item=$fetchitem['id'];
        $item1=$fetchitem['items'];
        
        $sqlprod="SELECT quantity FROM prod_items WHERE item='$item'";
        $resultprod=$conn->query($sqlprod);
        $prodquan=0;
        while($rowprod=$resultprod->fetch_assoc())
        {
           $prodquan=$prodquan+$rowprod['quantity'];
        }
        
	$sql = "SELECT quantity FROM batches_lots where item='$item'";
        $result = mysqli_query($conn, $sql);
        $batchquan=0;
        if (mysqli_num_rows($result) > 0) 
        {
        while($row = mysqli_fetch_assoc($result)) 
        {
           $batchquan=$batchquan+$row['quantity'];   
        }}
        
        $balquan=$prodquan-$batchquan;
                 
        ?>
             
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $item1;?></td>
             <td><?php echo $balquan;?></td>
          </tr>
		<?php
                $sl=$sl+1;
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