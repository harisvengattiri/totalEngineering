<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
       
    <div class="col-md-12">
	<?php
        if($_GET['status']) {
            
        $sts = $_GET['status']; 
        
        if($sts == "failed") {
    ?>
    	<p><a class="list-group-item b-l-danger">
              <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
              <span class="label rounded label danger pos-rlt m-r-xs">
    		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
    		  <span class="text-danger">Your Submission was Failed! Since you had already made receipt using this invoice. So please delete that first.</span>
        </a></p>
    <?php } } ?>
    </div>     
       
    <div class="box-header">
	<span style="float: left;"><h2>Invoice</h2></span> 
    <span style="float: right;">
         <!--<a href="<?php echo $baseurl; ?>/add/invoice" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
         <a href="<?php echo $baseurl; ?>/add/new_invoice" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New Invoice</a></button>&nbsp;

<?php // if (isset($_GET['view'])) { ?>
    <!--<a href="<?php // echo $baseurl; ?>/invoice" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>-->
<?php // }else { ?>
    <!--<a href="<?php // echo $baseurl; ?>/invoice?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>-->
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
    $sql="SELECT COUNT(id) as invoice FROM invoice";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
    $invoice = $result['invoice'];
    $btn = ceil($invoice/1000);
    ?>
    
    <?php 
    if (isset($_GET['view']) || isset($_GET['list']))
    {
        $list = $_GET['list'];
    ?>
    <div style="margin-left:5px;">
    <?php for($i=1;$i<=$btn;$i++){?>
    <a href="<?php echo $baseurl; ?>/invoice?list=<?php echo $i;?>"><button style="margin:2px 0;padding:5px 8px;" class="btn btn-outline btn-sm rounded b-info text-info <?php if($i==$list){?>list-active<?php } ?>">
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
        
        <form role="form" action="<?php echo $baseurl;?>/invoice" method="POST">
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
            </div>

            <div class="form-group row">
                <label for="Customer" class="col-sm-1 form-control-label">Customer</label>
                <div class="col-sm-3">
                    <select name="customer" class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                      <?php
                      $sql = "SELECT name,id FROM customers where type='Company' order by name";
                      $result = mysqli_query($conn, $sql);
                      if (mysqli_num_rows($result) > 0) {
                      ?><option value=""> </option>
                      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                          <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"];?>] <?php echo $row["name"];?></option>
                      <?php } } ?>
                    </select>
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
                $customer = $_POST['customer'];
                $period_sql = "WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
                if(!empty($customer)) {
                  $cust_sql = "AND `customer` = '$customer'";
                } else {
                    $cust_sql = "";
                }

                $mode = 'Search Mode';
                $show_date = "[$fdate - $tdate]";
            } else {
                $period_sql = "";
                $cust_sql = "";
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
    <!--<form action="<?php echo $baseurl;?>/edit/confirmDelivery" method="POST">-->
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <!--<tr>-->
          <!--  <td colspan="7" style="padding:0;border-top:0;"></td>-->
          <!--  <td style="padding:0;border-top:0;">-->
          <!--      <button class="btn btn-info" style="padding:3px 15px;" type="submit" name="submitConfirmInvoice" value="Confirm">Confirm Invoice</button>-->
          <!--  </td>-->
          <!--</tr>-->
          <tr>
              <th>
                 Invoice No
              </th>
              <th>
                 LPO
              </th>
	          <th>
                  Customer
              </th>
              <th>
                  Sales Order
              </th>
              <th>
                  Customer Site
              </th>
	      <th>
                  Date
              </th>
              <th>
                  Amount
              </th>
              
<!--              <th data-hide="all">
                  Notes
              </th>-->
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM invoice ORDER BY id DESC LIMIT 0,1000";
        }
        else if (isset($_GET['list']))
		{
		$list = $_GET['list'];
		    if($list==1){$start=1;}
		    else { $start = ($list-1)*1000;}
		$sql = "SELECT * FROM invoice ORDER BY id ASC LIMIT $start,1000";
        }
        
        else if (isset($_POST['fdate'])) {
		    $sql = "SELECT * FROM invoice $period_sql $cust_sql ORDER BY id ASC LIMIT 0,1000";
        }
        
        else {
		$sql = "SELECT * FROM invoice ORDER BY id DESC LIMIT 0,200";
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
                    $or=$row["o_r"];
                    $sql_lpo = "SELECT lpo_pdf FROM sales_order WHERE order_referance = $or";
                    $query_lpo=mysqli_query($conn,$sql_lpo);
                    $fetch_lpo=mysqli_fetch_array($query_lpo);
                    $lpopdf=$fetch_lpo['lpo_pdf'];
            
            $prints = $row["prints"];
            $prints1 = $row["prints1"];
            $prints2 = $row["prints2"];
            $prints3 = $row["prints3"];

            $grand = $row["grand"];
            $grand = ($grand != NULL) ? $grand : 0;
        ?>
          <tr>
              <td>INV <?php echo sprintf("%06d",$row["id"]);?></td>
              <td> <?php echo $row["lpo"];?></td>
              <td width="22%"><?php echo $cust?></td>
              <td><?php echo $row["o_r"];?></td>
              <td width="16%"><?php echo $site;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $grand);?></td>
	      
	            <?php 
        	    //  session_start();
        	     $company=$_SESSION["username"];
        	    ?>
              
              <td>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>" class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"><?php echo $prints;?></i></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_print?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>" class="btn btn-xs btn-icon info"><i class="fa fa-print"><?php echo $prints1;?></i></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_tax_split?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>" class="btn btn-outline-success btn-xs btn-icon"><i class="fa fa-folder-open"><?php echo $prints2;?></i></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_tax_split_print?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>" class="btn btn-outline-info btn-xs btn-icon"><i class="fa fa-print"><?php echo $prints3;?></i></a>
                  <a href="<?php echo $baseurl; ?>/add/trp_calc?inv=<?php echo $row["id"];?>" title="Transport Update" class="btn btn-xs btn-icon warning" onclick="return confirm('Are you sure to update Transportation charge?')">T</a>
                <?php if($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'accnt') { ?>
                  <a href="<?php echo $baseurl; ?>/edit/new_invoice?inv=<?php echo $row["id"];?>" class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></a>
                <?php } if($_SESSION['role'] == 'admin') { ?>  
                  <a href="<?php echo $baseurl; ?>/delete/invoice_cancel?id=<?php echo $row["id"];?>" title="Cancel" class="btn btn-xs btn-icon danger" onclick="return confirm('Are you sure?')"><i class="fa fa-times"></i></a>
                <?php } ?>
                <?php if(!empty($lpopdf)) { ?>
                  <a target="_blank" href="<?php echo $baseurl; ?>/uploads/lpo/<?php echo $lpopdf;?>" class="btn btn-xs btn-icon warn">L</a>
                <?php } ?>
                <?php // if($row['confirmation'] == 0) { ?>
                    <!--<input type="checkbox" style="width:20px;height:20px;vertical-align:middle;" name="invoices[]" value="<?php // echo $row["id"];?>">-->
                <?php // } ?>
              
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
     <!--</form>-->
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
