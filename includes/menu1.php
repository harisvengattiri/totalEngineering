<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8" />
  <title><?php echo $title; ?></title>
  <meta name="description" content="Responsive, Bootstrap, BS4" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="<?php echo $baseurl; ?>/images/icon.png">
  
  <!-- style -->
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/material-design-icons/material-design-icons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/ionicons/css/ionicons.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/simple-line-icons/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/bootstrap/dist/css/bootstrap.min.css" type="text/css" />

  <!-- build:css css/styles/app.min.css -->
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/styles/app.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/styles/style.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="<?php echo $baseurl; ?>/css/styles/font.css" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


</head>
<body>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->

  <!-- aside -->
  <div id="aside" class="app-aside modal md nav-expand black">
    <!-- fluid app aside -->
    <div class="navside dk" data-layout="column">
      <div class="navbar no-radius">
        <!-- brand -->
        <a href="<?php echo $baseurl; ?>/" class="navbar-brand">
        	<img src="<?php echo $baseurl; ?>/images/logo_full_white.png" alt="Cyboz ERP">
        </a>
        <!-- / brand -->
      </div>
      <div data-flex class="hide-scroll">
          <nav class="scroll nav-stacked nav-stacked-rounded nav-color">
            
            <ul class="nav" data-ui-nav>
              <li class="nav-header hidden-folded">
                <span class="text-xs">Main</span>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-filing"></i>
                  </span>
                  <span class="nav-text">Dashboard</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/customers" class="b-primary">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-person-stalker"></i>
                  </span>
                  <span class="nav-text">Contacts</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/customer_site" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-settings"></i>
                  </span>
                  <span class="nav-text">Customer Site</span>
                </a>
              </li>
               <li>
                <a href="<?php echo $baseurl; ?>/items" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-pricetags"></i>
                  </span>
                  <span class="nav-text">Items</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/operators" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-pricetags"></i>
                  </span>
                  <span class="nav-text">Operators</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/production" class="b-warning">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-network"></i>
                  </span>
                  <span class="nav-text">Production</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/lot_creation" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-settings"></i>
                  </span>
                  <span class="nav-text">Lot Creation</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/sales_order" class="b-warn">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-calendar"></i>
                  </span>
                  <span class="nav-text">Sales Order</span>
                </a>
              </li>
               <li>
                <a href="<?php echo $baseurl; ?>/delivery_note" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-briefcase"></i>
                  </span>
                  <span class="nav-text">Delivery Note</span>
                </a>
              </li>
<!--              <li>
                <a href="<?php echo $baseurl; ?>/batches" class="b-warn">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-calendar"></i>
                  </span>
                  <span class="nav-text">Batches/Lots</span>
                </a>
              </li>-->
             
              <li>
                <a href="<?php echo $baseurl; ?>/bank_cash_flow" class="b-success">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-list"></i>
                  </span>
                  <span class="nav-text">Sales</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/maintenances" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-settings"></i>
                  </span>
                  <span class="nav-text">Lot Creation</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/shops" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-pricetags"></i>
                  </span>
                  <span class="nav-text">Invoice</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/restricted" class="b-primary">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-bonfire"></i>
                  </span>
                  <span class="nav-text">Misc. Income</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/restricted" class="b-warning">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-help-buoy"></i>
                  </span>
                  <span class="nav-text">Staff Loan</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/payments" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-arrow-return-right"></i>
                  </span>
                  <span class="nav-text">Client Payments</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/works" class="b-warn">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-calendar"></i>
                  </span>
                  <span class="nav-text">Daily Works</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/work_invoices" class="b-success">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-social-usd"></i>
                  </span>
                  <span class="nav-text">Work Invoicing</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/internal_transfers" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-arrow-swap"></i>
                  </span>
                  <span class="nav-text">Internal Transfers</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/purchases" class="b-primary">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-ios-cart"></i>
                  </span>
                  <span class="nav-text">Purchases</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/credit_settlements" class="b-warning">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-arrow-return-left"></i>
                  </span>
                  <span class="nav-text">Credit Settlements</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/office_expenses" class="b-info">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-ios-location"></i>
                  </span>
                  <span class="nav-text">Office Expenses</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/vehicles" class="b-warn">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-car"></i>
                  </span>
                  <span class="nav-text">Vehicles</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/vehicle_expenses" class="b-success">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-android-bicycle"></i>
                  </span>
                  <span class="nav-text">Vehicle Expenses</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/reports" class="b-danger">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-ios-pulse-strong"></i>
                  </span>
                  <span class="nav-text">Reports</span>
                </a>
              </li>
              <li>
                <a href="<?php echo $baseurl; ?>/settings" class="b-primary">
                  <span class="nav-icon text-white no-fade">
                    <i class="ion-gear-b"></i>
                  </span>
                  <span class="nav-text">Settings</span>
                </a>
              </li>
              <li>
                <a href="http://cyboz.co.in/contact/" target="_blank" class="b-default">
                  <span class="nav-label">
                    <b class="label label-xs rounded success"></b>
                  </span>
                  <span class="nav-icon">
                    <i class="ion-chatbubble-working"></i>
                  </span>
                  <span class="nav-text">Support</span>
                </a>
              </li>
            </ul>
          </nav>
      </div>
      <div data-flex-no-shrink>
        <div class="nav-fold dropup">
          <a data-toggle="dropdown">
              <div class="pull-left">
                <div class="inline"><span class="avatar w-40 grey">
                 <?php echo strtoupper(substr($_SESSION['username'],0,1).substr($_SESSION['username'],-1));?></span></div>
              </div>
              <div class="clear hidden-folded p-x">
                <span class="block _500 text-muted"><?php echo ucwords($_SESSION['username']);?></span>
                <div id="clock"></div>
              </div>
          </a>
          <div class="dropdown-menu w dropdown-menu-scale ">
                     <a class="dropdown-item" href="#">
                        <span>Profile</span>
                      </a>
                      <a class="dropdown-item" href="<?php echo $baseurl; ?>/settings">
                        <span>Settings</span>
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" target="_blank" href="http://cyboz.co.in/contact/">
                        Need help?
                      </a>
            <a class="dropdown-item" href="<?php echo $baseurl; ?>/logout">Sign out</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / -->
  <!-- content -->
  <div id="content" class="app-content box-shadow-z2 bg pjax-container" role="main">
    <div class="app-header white bg b-b">
          <div class="navbar" data-pjax>
                <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up p-r m-a-0">
                  <i class="ion-navicon"></i>
                </a>
                <div class="navbar-item pull-left h5" id="pageTitle"> Mancon Block Factory</div>
                <!-- nabar right -->
                <ul class="nav navbar-nav pull-right">
<style>
.clockout {
	display: none;
}
</style>

<li class="nav-item dropdown pos-stc-xs">
<?php $x=0;
$fdate="01/01/2000";
$tdate=date("d/m/Y");
							
		$sql = "SELECT count(*) as pending_invoices from work_invoices WHERE status='Pending' AND STR_TO_DATE(duedate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$x=0+$row['pending_invoices'];
		}
		}
if($x==0)
{
?>
<a class="nav-link clear" data-toggle="dropdown" aria-expanded="false"><span class="label rounded success pos-rlt text-sm m-r-xs"><b class="arrow bottom b-success pull-in"></b><i class="fa fa-check"></i></span></a>
<div class="dropdown-menu pull-right w-xl animated fadeIn no-bg no-border no-shadow"><div class="scrollable" style="max-height: 220px"><ul class="list-group list-group-gap m-a-0">
<li class="list-group-item dark-white box-shadow-z0 b"><span class="pull-left m-r"><img src="images/a8.jpg" alt="..." class="w-40 img-circle"></span> <span class="clear block">No notifications for now!<br><small class="text-muted">in Work Invoices</small></span></li>
<?php
}
else
{
?>
<a class="nav-link clear" data-toggle="dropdown" aria-expanded="false"><span class="label rounded danger pos-rlt text-sm m-r-xs"><b class="arrow bottom b-danger pull-in"></b><i class="fa fa-slack"></i> <?php echo $x;?></span></a>
<div class="dropdown-menu pull-right w-xl animated fadeIn no-bg no-border no-shadow"><div class="scrollable" style="max-height: 220px"><ul class="list-group list-group-gap m-a-0">
<?php 
$fdate="01/01/2000";
$tdate=date("d/m/Y");
							
		$sql = "SELECT work, wtype, due from work_invoices WHERE status='Pending' AND STR_TO_DATE(duedate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$work=$row['work'];
			$wtype=$row['wtype'];
			$dueamt=$row['due'];
			$duedate=$row['duedate'];
?>	
<li class="list-group-item dark-white box-shadow-z0 b"><span class="pull-left m-r"><img src="images/a11.jpg" alt="..." class="w-40 img-circle"></span> <span class="clear block"><?php echo $dueamt;?> AED Pending from 
<?php
              if($row["wtype"]=="maintenance")
              {
              echo "MNT".sprintf("%04d", $row["work"]);
              }
              elseif($row["wtype"]=="project")
              {
              echo "PRJ".sprintf("%04d", $row["work"]);
              }
              
				$id=$row["work"];
                                $work=$row["wtype"];
                                $work=$work."s";
				$subsql = "SELECT name FROM $work where id=$id";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				echo "<br/>[".$subrow["name"]."]";
				}} 
?>
<br><small class="text-muted">on <?php echo $duedate;?></small></span></li>
<?php
		}
		}
?>
<li class="list-group-item dark-white box-shadow-z0 b"><span class="pull-left m-r"><img src="images/a4.jpg" alt="..." class="w-40 img-circle"></span> <span class="clear block"><?php echo $x;?> Work Invoices are Pending!<br><small class="text-muted">Click here to view all</small></span></li>
<?php
}
?>
</ul></div></div></li>

                  <li class="nav-item dropdown">
                    <a class="nav-link clear" data-toggle="dropdown">
                      <span class="avatar w-32">
                        <img src="images/user.png" class="w-full rounded" alt="...">
                      </span>
                    </a>
                    <div class="dropdown-menu w dropdown-menu-scale pull-right">
                      <a class="dropdown-item" href="#">
                        <span>Hello, <b><?php echo ucwords($_SESSION['username']);?></b></span>
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">
                        <span>Profile</span>
                      </a>
                      <a class="dropdown-item" href="<?php echo $baseurl; ?>/settings">
                        <span>Settings</span>
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" target="_blank" href="http://cyboz.co.in/contact/">
                        Need help?
                      </a>
                      <a class="dropdown-item" href="<?php echo $baseurl; ?>/logout">Sign out</a>
                    </div>
                  </li>
                </ul>
                <!-- / navbar right -->
          </div>
    </div>
    <div class="app-footer white bg p-a b-t">
      <div class="pull-right text-sm text-muted"><?php echo $erp_version;?></div>
      <span class="text-sm text-muted">&copy; Cyboz Technologies Pvt. Ltd.</span>
    </div>
<script>
var countDownDate = <?php echo $_SESSION['time']*1000;?>;

var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now + 1800000;
    
    var minutes = ("0" + Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).slice(-2);
    var seconds = ("0" + Math.floor((distance % (1000 * 60)) / 1000)).slice(-2);

    document.getElementById("clocktime").innerHTML = minutes + "m " + seconds + "s";
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("clocktime").innerHTML = "Clocked!";
    }}, 1000);

$('.clock').hover(function() {
        $(this).find('.clockin').hide();
        $(this).find('.clockout').show();
    }, function() {
        $(this).find('.clockout').hide();
        $(this).find('.clockin').show();
});
</script>