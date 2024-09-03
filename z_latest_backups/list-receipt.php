<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Reciepts </h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/receipt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New Receipt</a></button>&nbsp;

<?php // if (isset($_GET['view'])) { ?>
    <!--<a href="<?php // echo $baseurl; ?>/receipt" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>-->
<?php // } else { ?>
    <!--<a href="<?php // echo $baseurl; ?>/receipt?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>-->
<?php // } ?>

</span>
    </div><br/>
    
<style>
    .list-active{
    color: rgba(255, 255, 255, 0.87) !important;
    background-color: #2196f3;
    }
</style>
    <?php
    $sql="SELECT COUNT(id) as receipt FROM reciept";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
    $receipt = $result['receipt'];
    $btn = ceil($receipt/1000);
    ?>
    
    <?php 
    if (isset($_GET['view']) || isset($_GET['list']))
    {
        $list = $_GET['list'];
    ?>
    <div style="margin-left:5px;">
    <?php for($i=1;$i<=$btn;$i++){?>
    <a href="<?php echo $baseurl; ?>/receipt?list=<?php echo $i;?>"><button style="margin:2px 0;padding:5px 8px;" class="btn btn-outline btn-sm rounded b-info text-info <?php if($i==$list){?>list-active<?php } ?>">
        <?php if($i==1){$start=0;$end=$start+1000;}
           else{$start = ($i-1)*1000;$end=$start+1000;}
        if($btn == $i){ echo $start+1;?> - Latest <?php ;
        }else{ echo $start+1;?> - <?php echo $end; }
        ?>
    </a></button>
    <?php } ?>
    </div>
    <?php } ?>
    
    
    
    <div class="box-body">
         
        <form role="form" action="<?php echo $baseurl;?>/receipt_customer_search" method="post">
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">&nbsp;</label>
              <div class="col-sm-4">
              <select name="customer" id="item" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT name,id FROM customers WHERE type='Company'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select> 
              </div>
                <!--<button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
            </div> 
        </form>
         
        <form role="form" action="<?php echo $baseurl;?>/receipt" method="POST">
            <div class="form-group row">
                <label for="Quantity" class="col-sm-1 form-control-label">From</label>
                <div class="col-sm-3">
                <input type="text" name="fdate" id="date" placeholder="From Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  }" required>
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-3">
                <input type="text" name="tdate" id="date" placeholder="To Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                  }" required>
              </div>
              
              <div class="col-sm-2">
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
        </form>
        <?php
            if($_POST) {
                $fdate = $_POST['fdate'];
                $tdate = $_POST['tdate'];
                $period_sql = "WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
                
                $mode = 'Search Mode';
                $show_date = "[$fdate - $tdate]";
            } else {
                $period_sql = "";
                $mode = 'Recent View';
                $show_date = "";
            }
        ?>
        <h4 style="padding: 15px 0;color: green;float:left;"><span style="font-weight:600;">Mode:</span> <?php echo $mode.$show_date;?></h4>
             
	<span style="float: left;"></span>
    <span style="float: right;">Filter: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
		<?php
		if (isset($_GET['view']))
		{
		$list_count = 100;
        }
        else if (isset($_GET['list']))
		{
		$list_count = 100;
        } else if (isset($_POST['fdate'])) {
		$list_count = 50;
        } else {
		$list_count = 50;
        }
        ?>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
<!--              <th data-toggle="true">
                  Code
              </th>-->    
              <th data-toggle="true">
                 Reciept No
              </th>
	      <th>
                  Customer
              </th>
<!--              <th>
                  Customer Site
              </th>-->
	      <th>
                  Date
              </th>
              <th>
                  Due Date
              </th>
              <th width="13%">
                  Clearance Date
              </th>
              <th data-hide="all">
                  Invoice
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Payment
              </th>
              <th>
                  View
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM reciept WHERE type='' ORDER BY id DESC LIMIT 0,1000";
        }
        else if (isset($_GET['list']))
		{
		$list = $_GET['list'];
		    if($list==1){$start=1;}
		    else { $start = ($list-1)*1000;}
		$sql = "SELECT * FROM reciept WHERE type='' ORDER BY id ASC LIMIT $start,1000";
        }
        
        else if (isset($_POST['fdate'])) {
		    $sql = "SELECT * FROM reciept $period_sql ORDER BY id ASC LIMIT 0,1000";
        }
        
        else
		{
		$sql = "SELECT * FROM reciept WHERE type='' ORDER BY id DESC LIMIT 0,250";
        }
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $rcp=$row["id"];
              $name1=$row["customer"];
            //   $site=$row['site'];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
            //    $sqlsite="SELECT p_name from customer_site where id='$site'";
            //    $querysite=mysqli_query($conn,$sqlsite);
            //    $fetchsite=mysqli_fetch_array($querysite);
            //    $site1=$fetchsite['p_name'];
               
              $pdc=$row['post_dated'];
              $amount = $row["amount"];
              $amount = ($amount != NULL) ? $amount : 0;
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$rcp);?></td>
              <td><?php echo $cust;?></td>
              <!--<td><?php // echo $site1;?></td>-->
              <td><?php echo $row["pdate"];?></td>
              
              <td><?php echo $row["duedate"];?></td>
              <td><?php echo $row["clearance_date"];?></td>

              <td><?php
                $sqlinv="SELECT invoice FROM reciept_invoice WHERE reciept_id='$rcp'";
                $queryinv=mysqli_query($conn,$sqlinv);
                while($fetchinv=mysqli_fetch_array($queryinv))
                {
                    echo $fetchinv['invoice'].'<br>';
                }
              ?></td>
              
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <td><?php echo $row["pmethod"];?></td>
	      
            <?php 
        	// session_start();
        	$company = $_SESSION["username"];
            ?> 
              <td>
              <a href="<?php echo $baseurl; ?>/view/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $cdn_url; ?>/prints/receipt?rv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/receipt_full?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/receipt?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
