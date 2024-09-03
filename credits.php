<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">


    <div class="box-header">
	<span style="float: left;"><h2>Credit Applications</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/credit_application" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;

<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/credits" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/credits?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">


        <div class="row">
            <div class="col-md-6">
            

          <form role="form" action="" method="post">
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-8">
                <select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
                    //   $sql="SELECT customer AS r FROM delivery_note JOIN customers ON delivery_note.customer = customers.id
                    //          GROUP BY delivery_note.customer ORDER BY customers.name";
                             
                $sql="SELECT id,name FROM customers WHERE type='Company' ORDER BY customers.name";          
                $result = mysqli_query($conn_backup, $sql);
				if (mysqli_num_rows($result) > 0)
				{
                ?><option value=""></option><?php     
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"];?>"><?php echo $row["name"];?></option>
				<?php 
				}}
                    ?>
                 </select>
              </div>
              <div class="col-sm-2">
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
          </form>
          
          
          </div>
          <div class="col-md-6" style="text-align:left;">
              <a target="_blank" href="<?php echo $baseurl;?>/report/deadline" class="btn btn-info btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Show Customers on Deadline</a>
          </div>
        </div>
          
          


	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
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
      <?php
      if($_POST) {
        $customer = $_POST['customer'];
      } else { $customer = NULL ; }
      if($customer == NULL) {
      ?>  
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">        <thead>
          <tr>
              <th data-toggle="true">
                  Application
              </th>
              <th style="width:20%;">
                  Company
              </th>
              <th data-hide="all">
                  Director
              </th>
              <th data-hide="all">
                  Bank
              </th>
              <th data-hide="all">
                  Sales Rep
              </th>
              <th>
                  Credit Limit
              </th>
              <th>
                  Extended Limit
              </th>
              <th>
                  Uncleared Supplies
              </th>
              <th>
                  Credit Limit Available
              </th>
              <th data-hide="all">
                  Payment
              </th>
              <th>
                  Guarantee Cheque amount
              </th>
              <th>
                  Status
              </th>
              <th style="width:13%;">
                  Actions
              </th>  
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM credit_application ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM credit_application ORDER BY id DESC LIMIT 0,10";
                }
        $result = mysqli_query($conn_backup, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $company=$row["company"];
             $sqlcust="SELECT name,op_bal FROM customers where id='$company'";
             $querycust=mysqli_query($conn_backup,$sqlcust);
             $fetchcust=mysqli_fetch_array($querycust);
             $cust=$fetchcust['name'];
             $opening=$fetchcust['op_bal'];
             $opening=($opening != NULL) ? $opening : 0;
             
             $rep=$row["rep"];
             $sqlrep="SELECT name FROM customers where id='$rep'";
             $queryrep=mysqli_query($conn_backup,$sqlrep);
             $fetchrep=mysqli_fetch_array($queryrep);
             $rep1=$fetchrep['name'];
             
                  $sqlinvo="SELECT ROUND(SUM(total), 2) AS grand FROM delivery_note WHERE customer = $company";
                  $queryinvo=mysqli_query($conn_backup,$sqlinvo);
                  $fetchinvo=mysqli_fetch_array($queryinvo);
                  $invoamt=$fetchinvo['grand'];
                  $invoamt=($invoamt != NULL) ? $invoamt : 0;
                  $invoamt = $invoamt*1.05;
                  $invoamt = $invoamt + $opening;
                  
                  $sqlrpt="SELECT ROUND(SUM(grand), 2) AS amount from reciept where customer=$company AND status='Cleared'";
                  $queryrpt=mysqli_query($conn_backup,$sqlrpt);
                  $fetchrpt=mysqli_fetch_array($queryrpt);
                  $amountrpt=$fetchrpt['amount'];
                  $amountrpt=($amountrpt != NULL) ? $amountrpt : 0;
                  
                  $sqlcdt="SELECT ROUND(SUM(total), 2) AS credited from credit_note where customer=$company";
                  $querycdt=mysqli_query($conn_backup,$sqlcdt);
                  $fetchcdt=mysqli_fetch_array($querycdt);
                  $amountcdt=$fetchcdt['credited'];
                  $amountcdt=($amountcdt != NULL) ? $amountcdt : 0;

                  $sqlrfd="SELECT sum(`amount`) AS rfd FROM `refund` WHERE `customer`=$company" ;
                  $queryrfd=mysqli_query($conn,$sqlrfd);
                  $fetchrfd=mysqli_fetch_array($queryrfd);
                  $amountrfd=0+$fetchrfd['rfd'];
                  $amountrfd=($amountrfd != NULL) ? $amountrfd : 0;

                  $current_bal = ($invoamt+$amountrfd)-($amountrpt+$amountcdt);
                  
                  $cd1 = $row["credit"];
                  $cd1 = ($cd1 != NULL) ? $cd1 : 0;
                  $cd2 = $row["credit1"];
                  $cd2 = ($cd2 != NULL) ? $cd2 : 0;
                  $total_cdt = $cd1 + $cd2;
                  $credit_bal = $total_cdt - $current_bal;
             
             $status=$row["status"];
             if($status == '') {$status='Pending';}
             if($status=="Approved")
                    {
                    $color="success";
                    }
                    elseif($status=="Rejected")
                    {
                    $color="danger";
                    }
                    else
                    {
                    $color="warning";
                    }
                    
            if($row["credit"] != NULL) { $crdt1 = $row["credit"]; } else { $crdt1 = 0;}
            if($row["credit1"] != NULL) { $crdt2 = $row["credit1"]; } else { $crdt2 = 0;}
        ?>
          <tr>
              
              <td>CDT<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["d_name1"];?></td>
              <td><?php echo $row["bank"];?></td>
              <td><?php echo $rep1;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $crdt1);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $crdt2);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $current_bal);?></td>
             <td style="text-align: right;"><?php echo custom_money_format('%!i', $credit_bal);?></td>
              <td><?php echo $row["mode"];?> <?php echo $row["period"];?></td>
              <td><?php echo $row["g_amt"];?></td>
              
               <td>
                   <div class="btn-group dropdown dropdown">
                     <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                     <div class="dropdown-menu dropdown-menu-scale">
                       <a class="dropdown-item">Change Status</a>
                       <div class="dropdown-divider"></div>                                       		                          
                       <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Pending">Pending</a>
                       <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Approved">Approved</a>
                       <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Rejected">Rejected</a>
                     </div>
               </td>
              
              
              <!--<td><a href="<?php echo $baseurl; ?>/production_cash_flow?prj=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
            <td>

             <a target="_blank" href="<?php echo $cdn_url;?>/prints/application?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>       
             <!--<a href="<?php // echo $baseurl; ?>/edit/credit?id=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
             <?php if($_SESSION['username'] == 'suraj' || $_SESSION['role'] == 'admin') { ?>
             <a href="<?php echo $baseurl; ?>/edit/credit_application?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
             <?php if($_SESSION['username'] != 'suraj') { ?>
             <a href="<?php echo $baseurl; ?>/edit/credit_application_ext?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon warning"> E </button></a>
             <a href="<?php echo $baseurl; ?>/delete/credits?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
             <?php } } ?> 
            </td>
          </tr>
		<?php
        //   $sl = $sl + 1;
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
      <?php } else { ?>
         <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">        <thead>
          <tr>
              <th data-toggle="true">
                  Application
              </th>
              <th style="width:20%;">
                  Company
              </th>
              <th data-hide="all">
                  Director
              </th>
              <th data-hide="all">
                  Bank
              </th>
              <th data-hide="all">
                  Sales Rep
              </th>
              <th>
                  Credit Limit
              </th>
              <th>
                  Extended Limit
              </th>
              <th>
                  Uncleared Supplies
              </th>
              <th>
                  Credit Balance
              </th>
              <th data-hide="all">
                  Payment
              </th>
              <th>
                  Guarantee Cheque amount
              </th>
              <th>
                  Status
              </th>
              <th style="width:13%;">
                  Actions
              </th>  
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM credit_application WHERE company = '$customer'";
        }
        else
		{
		$sql = "SELECT * FROM credit_application WHERE company = '$customer'";
        }
        $result = mysqli_query($conn_backup, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $company=$row["company"];
             $sqlcust="SELECT name,op_bal FROM customers where id='$company'";
             $querycust=mysqli_query($conn_backup,$sqlcust);
             $fetchcust=mysqli_fetch_array($querycust);
             $cust=$fetchcust['name'];
             $opening=$fetchcust['op_bal'];
             $opening=($opening != NULL) ? $opening : 0;
             
             $rep=$row["rep"];
             $sqlrep="SELECT name FROM customers where id='$rep'";
             $queryrep=mysqli_query($conn_backup,$sqlrep);
             $fetchrep=mysqli_fetch_array($queryrep);
             $rep1=$fetchrep['name'];
             
                // $sqlinvo="SELECT ROUND(SUM(delivery_item.amt), 2) AS grand FROM delivery_note LEFT JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE delivery_note.customer = $company";
                  $sqlinvo="SELECT ROUND(SUM(total), 2) AS grand FROM delivery_note WHERE customer = $company";
                  $queryinvo=mysqli_query($conn_backup,$sqlinvo);
                  $fetchinvo=mysqli_fetch_array($queryinvo);
                  $invoamt=$fetchinvo['grand'];
                  $invoamt=($invoamt != NULL) ? $invoamt : 0;
                  $invoamt=$invoamt*1.05;
                  $invoamt=$invoamt+$opening;
                  
                  $sqlrpt="SELECT ROUND(SUM(grand), 2) AS amount from reciept where customer=$company AND status='Cleared'";
                  $queryrpt=mysqli_query($conn_backup,$sqlrpt);
                  $fetchrpt=mysqli_fetch_array($queryrpt);
                  $amountrpt=$fetchrpt['amount'];
                  $amountrpt=($amountrpt != NULL) ? $amountrpt : 0;
                  
                  $sqlcdt="SELECT ROUND(SUM(total), 2) AS credited from credit_note where customer=$company";
                  $querycdt=mysqli_query($conn_backup,$sqlcdt);
                  $fetchcdt=mysqli_fetch_array($querycdt);
                  $amountcdt=$fetchcdt['credited'];
                  $amountcdt=($amountcdt != NULL) ? $amountcdt : 0;

                  $sqlrfd="SELECT sum(`amount`) AS rfd FROM `refund` WHERE `customer`=$company" ;
                  $queryrfd=mysqli_query($conn,$sqlrfd);
                  $fetchrfd=mysqli_fetch_array($queryrfd);
                  $amountrfd=0+$fetchrfd['rfd'];
                  $amountrfd=($amountrfd != NULL) ? $amountrfd : 0;

                  $current_bal = ($invoamt+$amountrfd)-($amountrpt+$amountcdt);
                  
                  $cdt1 = $row["credit"];
                  $cdt1=($cdt1 != NULL) ? $cdt1 : 0;
                  $cdt2 = $row["credit1"];
                  $cdt2=($cdt2 != NULL) ? $cdt2 : 0;

                  $credit_bal = $cdt1 + $cdt2 - $current_bal;
             
             $status=$row["status"];
             if($status == '') {$status='Pending';}
             if($status=="Approved")
                    {
                    $color="success";
                    }
                    elseif($status=="Rejected")
                    {
                    $color="danger";
                    }
                    else
                    {
                    $color="warning";
                    }
                    
              
            if($row["credit"] != NULL) { $crdt1 = $row["credit"]; } else { $crdt1 = 0;}
            if($row["credit1"] != NULL) { $crdt2 = $row["credit1"]; } else { $crdt2 = 0;}
                    
        ?>
          <tr>
              
              <td>CDT<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["d_name1"];?></td>
              <td><?php echo $row["bank"];?></td>
              <td><?php echo $rep1;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $crdt1);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $crdt2);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $current_bal);?></td>
             <td style="text-align: right;"><?php echo custom_money_format('%!i', $credit_bal);?></td>
              <td><?php echo $row["mode"];?> <?php echo $row["period"];?></td>
              <td><?php echo $row["g_amt"];?></td>
              
              
               <td>
                   <div class="btn-group dropdown dropdown">
                     <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                     <div class="dropdown-menu dropdown-menu-scale">
                       <a class="dropdown-item">Change Status</a>
                       <div class="dropdown-divider"></div>                                       		                          
                       <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Pending">Pending</a>
                       <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Approved">Approved</a>
                       <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status?id=<?php echo $row["id"];?>&status=Rejected">Rejected</a>
                     </div>
               </td>
              
              
              <!--<td><a href="<?php echo $baseurl; ?>/production_cash_flow?prj=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
            <td>

             <a href="<?php echo $cdn_url;?>/prints/application?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>       
             <!--<a href="<?php echo $baseurl; ?>/edit/credit?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
             <?php if($_SESSION['username'] == 'suraj' || $_SESSION['role'] == 'admin') { ?>
             <a href="<?php echo $baseurl; ?>/edit/credit_application?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
             <a href="<?php echo $baseurl; ?>/edit/credit_application_ext?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon warning"> E </button></a>
             <a href="<?php echo $baseurl; ?>/delete/credits?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
             <?php } ?>
            </td>
          </tr>
		<?php
                //   $sl = $sl + 1;
		}
		}
		?>
        </tbody>
      </table>
         
         
      <?php } ?>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
