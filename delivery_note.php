<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0);?>

<style>
     #im{padding-left: 5px;padding-right: 5px;}
     .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th{
         padding:5px;
         font-size:13px;
     }
     .nameCell{cursor:pointer;}
</style>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
  <?php
        if(isset($_GET['status'])) {
            $sts = $_GET['status'];
        } else { $sts = ''; }
    ?>   
    <div class="col-md-12">
	<?php if($sts == "failed") {?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Since you had already made an invoice using this delivery note. So please delete that first.</span>
    </a></p>
        <?php } ?>
    </div>     
       
    <div class="box-header">   
	<span style="float: left;"><h2>Delivery Note</h2></span> 
    <span style="float: right;">
         <!--<a href="<?php echo $baseurl; ?>/add/new_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
         <a href="<?php echo $baseurl; ?>/add/new_dno" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New DNO</a></button>&nbsp;
         <!--<a href="<?php echo $baseurl; ?>/add/old_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Old</a></button>&nbsp;-->
         
<?php // if (isset($_GET['view'])) { ?>
    <!--<a href="<?php // echo $baseurl; ?>/delivery_note" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>-->
<?php // } else { ?>
    <!--<a href="<?php // echo $baseurl; ?>/delivery_note?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>-->
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
    $sql="SELECT COUNT(id) as delivery FROM delivery_note";
    $query = mysqli_query($conn,$sql);
    $result = mysqli_fetch_array($query);
    $delivery = $result['delivery'];
    $btn = ceil($delivery/1000);
    ?>
    
    <?php 
    if (isset($_GET['view']) || isset($_GET['list']))
    {
        $list = $_GET['list'];
    ?>
    <div style="margin-left:5px;">
    <?php for($i=1;$i<=$btn;$i++){?>
    <a href="<?php echo $baseurl; ?>/delivery_note?list=<?php echo $i;?>"><button style="margin:2px 0;padding:5px 8px;" class="btn btn-outline btn-sm rounded b-info text-info <?php if($i==$list){?>list-active<?php } ?>">
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
        
        
        <form role="form" action="<?php echo $baseurl;?>/delivery_note" method="POST">
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
                          <option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"]?>] <?php echo $row["name"] ?></option>
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
    <form action="<?php echo $baseurl;?>/edit/confirmDelivery" method="POST">
      <table width="100%" class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
            <td colspan="10" style="padding:0;border-top:0;"></td>
            <td style="padding:0;border-top:0;">
                <button class="btn btn-info" style="padding:3px 15px;" type="submit" name="submitApproval" value="Confirm">Confirm Delivery</button>
            </td>
          </tr>
          <tr>
              <th>
                 DNo
              </th>
              <th>
                Date
              </th>
              <th>
                 P.O
              </th>
	          <th>
                 Customer
              </th>
              <th>
                 Site
              </th>
              <th>
                  LPO
              </th>
	          <th>
                  Vehicle
              </th>
              <th>
                  Driver
              </th>
              <th>
                  Inv
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view'])) {
		$sql = "SELECT * FROM delivery_note ORDER BY id DESC LIMIT 0,1000";
        } else if (isset($_GET['list'])) {
		$list = $_GET['list'];
		    if($list==1){$start=1;}
		    else { $start = ($list-1)*1000;}
		$sql = "SELECT * FROM delivery_note ORDER BY id ASC LIMIT $start,1000";
        }
        
        else if (isset($_POST['fdate'])) {
		    $sql = "SELECT * FROM delivery_note $period_sql $cust_sql ORDER BY id ASC LIMIT 0,1000";
        }
        
        else {
		$sql = "SELECT * FROM delivery_note ORDER BY id DESC LIMIT 0,300";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $customer=$row["customer"];
                    $sqlcust="SELECT name from customers where id='$customer'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
                    
              $customersite=$row["customersite"];
                    $sqlsite="SELECT p_name from customer_site where id='$customersite'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $custsite=$fetchsite['p_name'];
              $vehicle=$row["vehicle"];
                    $sqlveh="SELECT vehicle from vehicle where id='$vehicle'";
                    $queryveh=mysqli_query($conn,$sqlveh);
                    $fetchveh=mysqli_fetch_array($queryveh);
                    $veh=$fetchveh['vehicle'];
              $driver=$row["driver"];
                    $sqldri="SELECT name from customers where id='$driver'";
                    $querydri=mysqli_query($conn,$sqldri);
                    $fetchdri=mysqli_fetch_array($querydri);
                    $dri=$fetchdri['name'];
              $lpo=$row["lpo"];
              $invoiced=$row["invoiced"];
              $invoiced = ($invoiced != NULL) ? 'Yes' : 'No';
              $total = $row["total"];
              $total = ($total != NULL) ? $total : 0;
        ?>
          <tr>
              <td>DN<?php echo sprintf("%06d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td>PO <?php echo $row["order_referance"];?></td>
              <?php
                 $cust = strlen($cust) > 15 ? substr($cust, 0,15) . "..." : $cust;
                //  $custsite = strlen($custsite) > 21 ? substr($custsite, 0, 21) . "..." : $custsite;
                 $lpo = strlen($lpo) > 12 ? substr($lpo, 0, 12) . "..." : $lpo;
              ?>
              <td><?php echo $cust?></td>
              <td class="nameCell truncatedString"><?php echo $custsite;?></td>
              <td><?php echo $lpo;?></td>
              <td><?php echo $veh;?></td>
              <td><?php echo $dri;?></td>
              <td style="text-align:center;"><?php echo $invoiced;?></td>
              <?php
                if($row['confirmation'] == 0) {
                    $font = 'font-weight:600;color:red;';
                } else {
                    $font = 'font-weight:400;color:black;';
                }
              ?>
              <td style="text-align:right;<?php echo $font;?>"><?php echo custom_money_format('%!i', $total);?></td>
              
	      
	     <?php
	     $company=$_SESSION["username"];
	     ?> 
              
              <td width="11%">
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/delivery_note?dno=<?php echo $row["id"];?>&open=<?php echo $company;?>" title="Print" class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"><?php echo $row["prints"];?></i></a>
              <a href="<?php echo $baseurl; ?>/edit/dno?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" title="Edit" class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/new_delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" title="Cancel" class="btn btn-xs btn-icon danger" onclick="return confirm('Are you sure?')"><i class="fa fa-times"></i></a>
              <?php }
                if($row['confirmation'] == 0) {
              ?>
                <input type="checkbox" style="width:20px;height:20px;vertical-align:middle;" name="deliveryNotes[]" value="<?php echo $row["id"];?>">
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
      </form>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll(".nameCell").forEach(function(cell) {
      cell.setAttribute("data-original-text", cell.textContent);
      cell.addEventListener('click', function() {
        const originalString = cell.getAttribute("data-original-text");
        const truncatedLength = 15;
        if (cell.classList.contains('truncatedString')) {
          cell.textContent = originalString.substring(0, truncatedLength);
        } else {
          cell.textContent = originalString;
        }
        cell.classList.toggle('truncatedString');
      });
      cell.click();
    });
  });
</script>
<?php include "includes/footer.php";?>
