<?php include "config.php";?>
<?php include "includes/menu.php";?>

<script>
function target_popup(form) {
    window.open('', 'formpopup', 'width=1350,height=650,resizeable,scrollbars');
    form.target = 'formpopup';
}
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script>
$(document).ready(function(){
    $("#gobatch").click(function(){
        $("#batch").toggle();
        $("#lot").toggle();
    });
});
</script>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">  
   <div id="lot" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
             <h2>Batch Report <span style="color:red;">  [Date selection is not mandatory] </span></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/lot" method="post" onsubmit="target_popup(this)">
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Batch</label>
              <div class="col-sm-3">
               <!--<select name="lot" class="form-control">-->
               <select name="lot" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT batch FROM batches_lots order by batch";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["batch"]; ?>"><?php echo $row["batch"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
            format: 'DD/MM/YYYY',
            icons: {
              time: 'fa fa-clock-o',
              date: 'fa fa-calendar',
              up: 'fa fa-chevron-up',
              down: 'fa fa-chevron-down',
              previous: 'fa fa-chevron-left',
              next: 'fa fa-chevron-right',
              today: 'fa fa-screenshot',
              clear: 'fa fa-trash',
              close: 'fa fa-remove'
            }
          }">
              </div>
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>       
 
    <div id="avl" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Availability of Item</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Item</label>
              <div class="col-sm-6">
               <!--<select name="lot" class="form-control">-->
               <select name="item" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,items FROM items order by items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
        ?>
          <option value="">ALL</option>
				<?php
        while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["items"]; ?></option>
				<?php 
				}}
				?>
				</select>
              </div>
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit_avl" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Check</button>
                
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

     <?php if(isset($_POST['submit_avl'])) {
     $item=$_POST['item'];
     $sqlitem="SELECT items from items where id='$item'";
     $queryitem=mysqli_query($conn,$sqlitem);
     $fetchitem=mysqli_fetch_array($queryitem);
     $item1=$fetchitem['items'];
     
        $sqlprod="SELECT quantity FROM prod_items WHERE item='$item'";
        $resultprod=$conn->query($sqlprod);
        $prodquan=0;
        while($rowprod=$resultprod->fetch_assoc())
        {
           $prodquan=$prodquan+$rowprod['quantity'];
        }
        
	$sqllot = "SELECT quantity FROM batches_lots where item='$item'";
        $resultlot = mysqli_query($conn, $sqllot);
        $batchquan=0;
        if (mysqli_num_rows($resultlot) > 0) 
        {
        while($rowlot = mysqli_fetch_assoc($resultlot)) 
        {
           $batchquan=$batchquan+$rowlot['quantity'];   
        }}
        $balquan=$prodquan-$batchquan;
     ?>
     <div class="col-md-10">
      <div class="box">
        
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
             
          <?php if($item!=''){ ?>   
          <div class="box-header">
             <h2>Available Batches for the Item :<b> <?php echo $item1;?></b><br>
             <span>NON COC Production Available: <b> <?php echo $balquan;?></b> </span></h2>
          </div>
          <?php } ?>
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
                  Total Quantity
              </th>
              <th>
                  Sold Quantity
              </th>
              <th>
                  Balance in LOT
              </th>
          </tr>
        </thead>
        <?php if($item!=''){ ?>
        <tbody>
	<?php
	$sql = "SELECT * FROM batches_lots where item='$item' ORDER BY item DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		    {
        $sl = 0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>
               <?php $batch=$row["batch"]; ?>
               <?php $item=$row["item"]; 
               $sqlitem="SELECT items from items where id='$item'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item1=$fetchitem['items'];
               ?>
               <?php $quantity=$row["quantity"]; ?>
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
                 
                 if($lotquan > 0) {
                 $sl = $sl + 1;
        ?>
             
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $item1;?></td>
             <td><?php echo $batch;?></td>
             <td><?php echo $row["date"];?></td>
             <td><?php echo $quantity;?></td>
             <td><?php echo $delquan;?></td>
             <td><?php echo $lotquan;?></td>
          </tr>
		<?php
                }
		}
		}
		?>
        </tbody>
        <?php } else { ?>
        <tbody>
	<?php
	$sql2 = "SELECT item FROM batches_lots GROUP BY item";
        $result2 = mysqli_query($conn, $sql2);
        if (mysqli_num_rows($result2) > 0) 
	{
        while($row2 = mysqli_fetch_assoc($result2)) {
        $item=$row2["item"];
        $sqlitem="SELECT items from items where id='$item'";
          $queryitem=mysqli_query($conn,$sqlitem);
          $fetchitem=mysqli_fetch_array($queryitem);
          $item1=$fetchitem[items];
          
        $sqlprod="SELECT quantity FROM prod_items WHERE item='$item'";
        $resultprod=$conn->query($sqlprod);
        $prodquan=0;
        while($rowprod=$resultprod->fetch_assoc())
        {
           $prodquan=$prodquan+$rowprod['quantity'];
        }
        
	$sqllot = "SELECT quantity FROM batches_lots where item='$item'";
        $resultlot = mysqli_query($conn, $sqllot);
        $batchquan=0;
        if (mysqli_num_rows($resultlot) > 0) 
        {
        while($rowlot = mysqli_fetch_assoc($resultlot)) 
        {
           $batchquan=$batchquan+$rowlot['quantity'];   
        }}
        
        $balquan=$prodquan-$batchquan;
        ?>
        <tr>
             <td colspan="3">
             Available Batches for the Item :<b> <?php echo $item1;?></b>
             </td>
             <td colspan="3">
             NON COC Production Available :<b> <?php echo $balquan;?></b>
             </td>
        </tr>
        <?php $sql = "SELECT * FROM batches_lots where item='$item'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	      {
        $sl = 0;
        while($row = mysqli_fetch_assoc($result)) {   
        ?>
               <?php $batch=$row["batch"]; ?>
               <?php $item=$row["item"]; 
               $sqlitem="SELECT items from items where id='$item'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item1=$fetchitem[items];
               ?>
               <?php $quantity=$row["quantity"]; ?>
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
                 $sl = $sl + 1;
        ?>
             
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $item1;?></td>
             <td><?php echo $batch;?></td>
             <td><?php echo $row["date"];?></td>
             <td><?php echo $quantity;?></td>
             <td><?php echo $delquan;?></td>
             <td><?php echo $lotquan;?></td>
          </tr>
		<?php
                }
		}
                }}}
		?>
        </tbody>
        <?php } ?>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>