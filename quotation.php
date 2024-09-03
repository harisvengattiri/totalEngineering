<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Quotation </h2></span> 
    <span style="float: right;">
        <a href="<?php echo $baseurl; ?>/add/new_qno" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New Quotation</a></button>&nbsp;
        <!--<a href="<?php echo $baseurl; ?>/add/quotation" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
<?php 
// if (isset($_GET['view'])) {
?>
<!--<a href="<?php // echo $baseurl; ?>/quotation" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>-->
<?php // } else { ?>
<!--<a href="<?php // echo $baseurl; ?>/quotation?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>-->
<?php // } ?>
</span>
    </div><br/>
    <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/quotation" method="POST">
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
                          <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"]?>] <?php echo $row["name"];?></option>
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
<!--              <th data-toggle="true">
                  Date
              </th>
              <th data-hide="all">
                  Start Date
              </th>
              <th data-hide="all">
                  End Date
              </th>
              <th data-hide="all">
                  Customer
              </th>
              <th data-hide="all">
                  Tags
              </th>-->
              <th>
                 Quotation No
              </th>
              
	      <th>
                  Customer
              </th>
              <th style="width:20%;">
                  Customer Site
              </th>
	      <th>
                  Date
              </th>
              <th>
                  Total
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
		    $sql = "SELECT * FROM quotation ORDER BY id DESC";
        }
        else if (isset($_POST['fdate'])) {
		   $sql = "SELECT * FROM quotation $period_sql $cust_sql ORDER BY id ASC LIMIT 0,1000";
        }
        else
		{
		    $sql = "SELECT * FROM quotation ORDER BY id DESC LIMIT 0,250";
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
               
               $status = $row["status"];
               $site = $row["site"];
                if($status == 'Sales Order' && $site != NULL) {
                $sql_site = "SELECT p_name FROM customer_site WHERE id=$site";
                $query_site = mysqli_query($conn,$sql_site);
                $fetch_site = mysqli_fetch_array($query_site);
                $site_name = $fetch_site['p_name'];
                } else {
                    $site_name = $site;
                }
            $subtotal = $row["subtotal"];
            $subtotal = ($subtotal != NULL) ? $subtotal : 0;
        ?>
            
          <tr>
              <td>QTN|<?php echo sprintf("%06d",$row["id"]);?></td>
              <!--<td>SO <?php // echo $row["or"];?></td>-->
              <td><?php echo $cust;?></td>
              <td><?php echo $site_name;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $subtotal);?></td>
	          <td><?php echo $status;?></td>
              
              <td>
              <!--<a target="_blank" href="http://manconcdn.webdesignoman.com/prints/quotation?qno=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/quotation?qno=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/qno?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/quotation?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
