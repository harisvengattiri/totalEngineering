<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php // error_reporting(0); ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Lot Creation</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/lot_creation_coc" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add COC</a></button>    
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/lot_creation_non-coc" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add NON-COC</a></button>&nbsp;
<?php 
if (isset($_GET['view']))
{ 
?>
<a href="<?php echo $baseurl; ?>/lot_creation" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/lot_creation?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">
         <form role="form" action="<?php echo $baseurl;?>/lot_creation" method="post">
         <div class="form-group row">
              <div class="col-sm-2">
                <input type="text" name="date1" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div class="col-sm-2">
                   <?php $today=date("d/m/Y"); ?>
                <input type="text" name="date2" value="<?php echo $today;?>" id="date" placeholder="Production Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div class="col-sm-3">
              <select name="item" id="item" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT items,id FROM items";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["items"]?></option>
				<?php 
				}} 
				?>
                </select> 
              </div>
                <!--<button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
             </div> 
         </form>    
         
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    
    <?php if(isset($_POST['submit']))
    {
         $item = $_POST['item'];
         $date1=$_POST['date1'];
         $date2=$_POST['date2'];
     ?>
    <div>
		<?php
		if (isset($_GET['view']))
		{
		$list_count = 100;
    }
    else
		{
		$list_count = 10;
    }
    ?>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Batch No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Balance
              </th>
              <th>
                   COC No
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
//              $sql = "SELECT * FROM batches_lots where item='$item' AND (date BETWEEN '$date1' AND '$date2') and quantity!=0 ORDER BY batch DESC";
		$sql = "SELECT * FROM batches_lots WHERE item='$item' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY batch DESC";
    }
    else
		{
		$sql = "SELECT * FROM batches_lots WHERE item='$item' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY batch DESC LIMIT 0,100";
    }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $item1=$row["item"];
             if(!empty($item1)) {
             $sqlitem="SELECT items,sqm from items where id='$item1'";
             $queryitem=mysqli_query($conn,$sqlitem);
             $fetchitem=mysqli_fetch_array($queryitem);
             $sqm=$fetchitem['sqm'];
             }
             $sqm = ($sqm != NULL) ? $sqm : 1;
        ?>
          <tr>
              <?php $batch=$row["batch"]; ?>
              <?php $quantity=$row["quantity"];
                    $quantity = ($quantity != NULL) ? $quantity : 0;
                    $quantity=$quantity/$sqm;
              ?> 
              <td><?php echo $row["batch"];?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $fetchitem['items'];?></td>
              <td><?php echo $quantity;?></td>
              <?php
                 $sqllot="SELECT thisquantity FROM delivery_item WHERE batch='$batch'";
                 $resultlot=$conn->query($sqllot);
                 $delquan=0;
                 if(mysqli_num_rows($resultlot) > 0) {
                 while($rowlot=$resultlot->fetch_assoc())
                 {
                  if($rowlot['thisquantity'] != NULL) {
                    $delquan=$delquan+$rowlot['thisquantity'];
                  }
                 } }

                 $sqlrtn="SELECT returnqnt FROM stock_return WHERE batch='$batch'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rtnquan=0;
                 if(mysqli_num_rows($resultrtn) > 0) {
                 while($rowrtn=$resultrtn->fetch_assoc())
                 {
                  if($rowrtn['returnqnt'] != NULL) {
                    $rtnquan=$rtnquan+$rowrtn['returnqnt'];
                  }
                 } }

                 $lotquan = $quantity + $rtnquan - $delquan;
                 if(fmod($lotquan, 1) !== 0.00)
                 {
                     $lotquan = number_format($lotquan, 2, '.', '');
                 }
               ?>
              <td><?php echo $lotquan;?></td>
              <td><?php echo $row["COC_No"];?></td>
              
              <!--<a href="<?php echo $baseurl; ?>/maintenance_cash_flow?mnt=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
            <td>
                <a href="<?php echo $baseurl; ?>/edit/lot_creation?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
                <?php if($_SESSION['role'] == 'admin') { ?>
                <a href="<?php echo $baseurl; ?>/delete/lot_creation?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
                <?php } ?>
            </td>
          </tr>
		<?php
		}
		}
		?>
        </tbody>
        <tfoot class="hide-if-no-paging">
          <tr>
              <td colspan="5" class="text-center">
                  <ul class="pagination"></ul>
              </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php     
    }
    else {
    ?>
    
    <div>
		<?php
		if (isset($_GET['view']))
		{
		$list_count = 100;
    }
    else
		{
		$list_count = 10;
    }
    ?>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Batch No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Prod Date
              </th>
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
              <th>
                  Balance
              </th>
              <th>
                   COC No
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM batches_lots ORDER BY id DESC";
    }
    else
		{
		$sql = "SELECT * FROM batches_lots ORDER BY id DESC LIMIT 0,100";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $item1=$row["item"];
             if(!empty($item1)) {
             $sqlitem="SELECT items,sqm from items where id='$item1'";
             $queryitem=mysqli_query($conn,$sqlitem);
             $fetchitem=mysqli_fetch_array($queryitem);
             $sqm = $fetchitem['sqm'];
             }
             $sqm = ($sqm != NULL) ? $sqm : 1;
        ?>
          <tr>
               <?php $batch=$row["batch"]; ?>
               <?php $quantity=$row["quantity"];
                     $quantity = ($quantity != NULL) ? $quantity : 0;
                     $quantity=$quantity/$sqm;
               ?>
              <td><?php echo $row["batch"];?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["pdate"];?></td>
              <td><?php echo $fetchitem['items'];?></td>
              <td><?php echo $quantity;?></td>
              <?php
                 $sqllot="SELECT thisquantity FROM delivery_item WHERE batch='$batch'";
                 $resultlot=$conn->query($sqllot);
                 $delquan = 0;
                 if(mysqli_num_rows($resultlot) > 0) {
                 while($rowlot=$resultlot->fetch_assoc())
                 {
                    if($rowlot['thisquantity'] != NULL) {
                    $delquan = $delquan + $rowlot['thisquantity'];
                    }
                 } }

                 $sqlrtn="SELECT returnqnt FROM stock_return WHERE batch='$batch'";
                 $resultrtn=$conn->query($sqlrtn);
                 $rtnquan=0;
                 if(mysqli_num_rows($resultrtn) > 0) {
                 while($rowrtn=$resultrtn->fetch_assoc())
                 {
                  if($rowrtn['returnqnt'] != NULL) {
                    $rtnquan=$rtnquan+$rowrtn['returnqnt'];
                  }
                 } }

                 $lotquan = $quantity + $rtnquan - $delquan;
                 if(fmod($lotquan, 1) !== 0.00)
                 {
                     $lotquan=number_format($lotquan, 2, '.', '');
                 }
               ?>
              <td><?php echo $lotquan;?></td>
              <!--<td><?php // echo $row["quantity"];?></td>-->
              <td><?php echo $row["COC_No"];?></td>
            <td>
                <a href="<?php echo $baseurl; ?>/edit/lot_creation?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
                <?php if($_SESSION['role'] == 'admin') { ?>
                <a href="<?php echo $baseurl; ?>/delete/lot_creation?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
                <?php } ?>
            </td>
          </tr>
		<?php
		}
		}
		?>
        </tbody>
        <tfoot class="hide-if-no-paging">
          <tr>
              <td colspan="5" class="text-center">
                  <ul class="pagination"></ul>
              </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php } ?>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
