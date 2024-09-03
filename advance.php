<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Receipt Payments</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/receipt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Receipt</a></button>&nbsp;
         <!--<a href="<?php echo $baseurl; ?>/add/receipt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/advance" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{
?>
<a href="<?php echo $baseurl; ?>/advance?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">
        
        <form role="form" action="<?php echo $baseurl;?>/advance" method="POST">
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
                          <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"] ?></option>
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
                $period_sql = "WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
                
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
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
	<?php
		if (isset($_GET['view']))
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
              <th data-toggle="true" width="11%">
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
              <th data-hide="all">
                  P.D.C
              </th>
              <th>
                  Due Date
              </th>
               <th width="15%">
                  Clearance Date
              </th>
              <th data-hide="all" >
                  Invoice
              </th>
              
              <th >
                  Amount
              </th>
             <th data-hide="all" >
                      Payment
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
		$sql = "SELECT * FROM reciept WHERE type !=1 ORDER BY id DESC";
        }
        
        else if (isset($_POST['fdate'])) {
		    $sql = "SELECT * FROM reciept $period_sql $cust_sql AND type !=1 ORDER BY id ASC LIMIT 0,1000";
        }
        
        else
		{
		$sql = "SELECT * FROM reciept WHERE type !=1 ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $rcp=$row["id"];
              $name1=$row["customer"];
              // $site=$row['site'];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
              //  $sqlsite="SELECT p_name from customer_site where id='$site'";
              //  $querysite=mysqli_query($conn,$sqlsite);
              //  $fetchsite=mysqli_fetch_array($querysite);
              //  $site1=$fetchsite['p_name'];
               
              $pdc=$row['post_dated'];
              
             $status=$row["status"];
             if($status == '') {$status='Uncleared';}
             if($status=="Cleared")
                    {
                    $color="success";
                    }
                    elseif($status=="Bounce")
                    {
                    $color="danger";
                    }
                    else
                    {
                    $color="warning";
                    }

            $amount = $row["amount"];    
            $amount = ($amount != NULL) ? $amount : 0; 
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$rcp);?></td>
              <td><?php echo $cust;?></td>
              <!--<td><?php // echo $site1;?></td>-->
              <td><?php echo $row["pdate"];?></td>
              <td><?php if($pdc==1){ echo 'P.D.C';}?></td>
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
	      
	          <?php $company = $_SESSION["username"]; ?> 
	      
              <td>
              <!--<a href="<?php // echo $baseurl; ?>/prints/reciept?rcp=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <a href="<?php echo $baseurl; ?>/view/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon warn"><i class="fa fa-search-plus"></i></button></a>
              <a target="_blank" href="<?php echo $cdn_url; ?>/prints/receipt?rv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <a href="<?php echo $baseurl; ?>/add/receipt_advance_invoice?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-plus"></i></button></a>
              <!--<a href="<?php // echo $baseurl; ?>/edit/receipt_full?id=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
              <!--<a href="<?php // echo $baseurl; ?>/delete/quotation?id=<?php // echo $row["id"];?>&or=<?php // echo $row["order_referance"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
              <?php if($_SESSION['role'] == 'admin') { ?>
                <a href="<?php echo $baseurl; ?>/delete/receipt_cancel?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button title="Cancel Receipt" class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
