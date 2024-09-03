<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <?php
        if($_GET) {
            $sts = $_GET['status'];
        } else { $sts = ''; }
    ?>
    <div class="col-md-12">
	<?php if($sts == "failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Since this same PO is used for delivery. Please delete that delivery note first</span>
    </a></p>
        <?php } if($sts == "failed1") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger"> Unable to update from Quotation</span>
    </a></p>
        <?php } ?>
    </div>     
       
    <div class="box-header">
	<span style="float: left;"><h2>Sales Order</h2></span> 
    <span style="float: right;">
     
         <!--<a href="<?php echo $baseurl; ?>/add/sales_order" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
<a href="<?php echo $baseurl; ?>/add/new_sales_order_qtn" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add From Quotation</a></button>&nbsp;
<!--<a href="<?php echo $baseurl; ?>/add/new_sales_order" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
<!--<a href="<?php echo $baseurl; ?>/add/old_sales_order" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Old</a></button>&nbsp;-->

<?php // if (isset($_GET['view'])) { ?>
    <!--<a href="<?php // echo $baseurl; ?>/sales_order_new" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>-->
<?php // } else { ?>
    <!--<a href="<?php // echo $baseurl; ?>/sales_order_new?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>-->
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
    $sql="SELECT COUNT(id) as orders FROM sales_order";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
    $orders = $result['orders'];
    $btn = ceil($orders/1000);
    ?>
    
    <?php 
    if (isset($_GET['view']) || isset($_GET['list']))
    {
        $list = $_GET['list'];
    ?>
    <div style="margin-left:5px;">
    <?php for($i=1;$i<=$btn;$i++) { ?>
    <a href="<?php echo $baseurl; ?>/sales_order_new?list=<?php echo $i;?>"><button style="margin:2px 0;padding:5px 8px;" class="btn btn-outline btn-sm rounded b-info text-info <?php if($i==$list){?>list-active<?php } ?>">
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
        
        <form role="form" action="<?php echo $baseurl;?>/sales_order_new" method="POST">
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
                $period_sql = "WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
                
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
		if (isset($_GET['view'])) {
		$list_count = 100;
        } else if (isset($_GET['list'])) {
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
              <th data-toggle="true">
                  Date
              </th>
              <th>
                   Order Ref.
              </th> 
              <th>
                  Customer
              </th>
              <th data-hide="all">
                  Site
              </th>
              <th>
                  Sales Rep
              </th>
        
              <th data-hide="all">
                   LPO No
              </th>
              <th>
                   Total
              </th>
              <th>
                   Delivery<br> Amount
              </th>
              <th>
                  Status
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
		$sql = "SELECT * FROM sales_order ORDER BY order_referance DESC LIMIT 0,1000";
        }
        else if (isset($_GET['list']))
		{
		$list = $_GET['list'];
		    if($list==1){$start=1;}
		    else { $start = ($list-1)*1000;}
		$sql = "SELECT * FROM sales_order ORDER BY order_referance ASC LIMIT $start,1000";
        }
        
        else if (isset($_POST['fdate'])) {
		    $sql = "SELECT * FROM sales_order $period_sql ORDER BY id ASC LIMIT 0,1000";
        }
        
        else
		{
		$sql = "SELECT * FROM sales_order ORDER BY order_referance DESC LIMIT 0,250";
        }
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
             $site1=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site1'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site=$fetchsite['p_name'];
               
            $rep1=$row["salesrep"];
               $sqlrep="SELECT name from customers where id='$rep1'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
               
            $or=$row["order_referance"];
                $sqlor="SELECT sum(total) AS deliveryAmt,sum(transport) AS trans FROM `delivery_note` WHERE `order_referance`='$or'";
                $queryor=mysqli_query($conn,$sqlor);
                $fetchor=mysqli_fetch_array($queryor);
                $deliveryAmt1=$fetchor['deliveryAmt'];
                $deliveryAmt1 = ($deliveryAmt1 != NULL) ? $deliveryAmt1 : 0;
                $trans=$fetchor['trans'];
                $trans = ($trans != NULL) ? $trans : 0;
                
                $deliveryAmt = (($deliveryAmt1-$trans)*1.05)+$trans;
                // $deliveryAmt = $deliveryAmt1;
                
               
            $status=$row["status"];
            if($status == '') {
                $status='Active';
                $color='success';
            }
            else {
                $status='Inactive';
                $color='danger';
            }

        $grand_total = $row["grand_total"];
        $grand_total = ($grand_total != NULL) ? $grand_total : 0;
        
        $cust = strlen($cust) > 25 ? substr($cust, 0, 25) . "..." : $cust;
        $rep = strlen($rep) > 16 ? substr($rep, 0, 16) . "..." : $rep;
        ?>
          <tr>
              
              <!--<td><?php echo $row["id"];?></td>-->
              <td><?php echo $row["date"];?></td>
              <td><?php echo $or;?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $site;?></td>
	        <td><?php echo $rep;?></td> 
            <td><?php echo $row["lpo"];?></td>
            <td style="text-align: right;"><?php echo custom_money_format('%!i', $grand_total);?></td>
            <td style="text-align: right;"><?php echo custom_money_format('%!i', $deliveryAmt);?></td>
            
            <td>
                <div class="btn-group dropdown dropdown">
                    <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                    <div class="dropdown-menu dropdown-menu-scale">
                    <!--<a class="dropdown-item">Change Status</a>-->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status_po?id=<?php echo $row["id"];?>&status=Active">Active</a>
                    <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status_po?id=<?php echo $row["id"];?>&status=Inactive">Inactive</a>
                </div>
            </td> 
              
              
          <td>
              <?php 
        	    //  session_start();
        	     $company=$_SESSION["username"];
        	  ?>
        	  <a href="<?php echo $baseurl;?>/edit/update_po_qtn?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon warning"><i class="fa fa-refresh"></i></button></a>
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/sales_order?sono=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/sales_order_new?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <!--<a href="<?php // echo $baseurl; ?>/edit/sales_order?id=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/sales_order?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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