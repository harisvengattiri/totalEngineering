<?php include "config.php"; ?>
<?php include "includes/menu.php"; ?>
<?php
error_reporting(E_ERROR | E_PARSE);

$sql = "SELECT count(*) as contacts_total from customers";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$contacts_total = $row['contacts_total'];
	}
}
$sql = "SELECT count(*) as staff_total from customers where type='staff'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$staff_total = $row['staff_total'];
	}
}

$sql = "SELECT count(*) as vehicles_total from vehicle";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$vehicles_total = $row['vehicles_total'];
	}
}
$sql = "SELECT count(*) as recent_contacts from customers WHERE (`current_timestamp` > DATE_SUB(now(), INTERVAL 30 DAY))";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$recent_contacts = 0 + $row['recent_contacts'];
	}
}
$sql = "SELECT name,id,type from customers ORDER BY id DESC LIMIT 6";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$customer1 = $customer2;
		$customer2 = $customer3;
		$customer3 = $customer4;
		$customer4 = $customer5;
		$customer5 = $customer6;
		$customer6 = $row['name'];
		$customer1id = $customer2id;
		$customer2id = $customer3id;
		$customer3id = $customer4id;
		$customer4id = $customer5id;
		$customer5id = $customer6id;
		$customer6id = $row['id'];
		$customer1type = $customer2type;
		$customer2type = $customer3type;
		$customer3type = $customer4type;
		$customer4type = $customer5type;
		$customer5type = $customer6type;
		$customer6type = $row['type'];
	}
}
$sql = "SELECT count(*) as shops from customers WHERE type='Shop'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$totalshops = $row['shops'];
	}
}

$sql = "SELECT COUNT(*) AS cnt FROM customers WHERE `current_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 day) group by date(`current_timestamp`);";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
	$custcnt = "1";
	while ($row = mysqli_fetch_assoc($result)) {
		$custcnt = $custcnt . ", " . $row['cnt'];
		$custcnt6 = $custcnt7;
		$custcnt7 = $row['cnt'];
	}
}
if ($custcnt6 > $custcnt7) {
	$custcntarrow = "down";
} else {
	$custcntarrow = "up";
}

?>
<style>
	.mobile-content {
		display: none;
		padding-top: 5em;
	}

	.mobile-content p {
		font-size: 20px;
	}

	.mobile-content p span {
		font-size: 16px;
	}

	/* Styles for mobile */
	@media screen and (max-width: 768px) {
		.desktop-content {
			display: none;
		}

		.mobile-content {
			display: block;
		}
	}
</style>


<div class="app-body desktop-content">

	<!-- ############ PAGE START-->
	<div class="row-col">
		<div class="col-lg b-r">
			<div class="row no-gutter">
				<div class="col-xs-6 col-sm-3 b-r b-b">
					<div class="padding">
						<div>
							<span class="pull-right"><i class="fa fa-caret-<?php echo $custcntarrow; ?> text-primary m-y-xs"></i></span>
							<span class="text-muted l-h-1x"><i class="ion-person-stalker text-muted"></i></span>
						</div>
						<div class="text-center">
							<h2 class="text-center _600"><?php echo $contacts_total; ?></h2>
							<p class="text-muted m-b-md">Total Contacts</p>
							<div>
								<span data-ui-jp="sparkline" data-ui-options="[<?php echo $custcnt; ?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 b-r b-b">
					<div class="padding">
						<div>
							<span class="pull-right"><i class="fa fa-caret-<?php echo $prjcntarrow; ?> text-primary m-y-xs"></i></span>
							<span class="text-muted l-h-1x"><i class="ion-network text-muted"></i></span>
						</div>
						<div class="text-center">
							<h2 class="text-center _600"><?php echo $project_total; ?></h2>
							<p class="text-muted m-b-md">Total Projects</p>
							<div>
								<span data-ui-jp="sparkline" data-ui-options="[<?php echo $prjcnt; ?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 b-r b-b">
					<div class="padding">
						<div>
							<span class="pull-right"><i class="fa fa-caret-<?php echo $mntcntarrow; ?> text-primary m-y-xs"></i></span>
							<span class="text-muted l-h-1x"><i class="ion-settings text-muted"></i></span>
						</div>
						<div class="text-center">
							<h2 class="text-center _600"><?php echo $maintenance_total; ?></h2>
							<p class="text-muted m-b-md">Total Maintenances</p>
							<div>
								<span data-ui-jp="sparkline" data-ui-options="[<?php echo $mntcnt; ?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3 b-b">
					<div class="padding">
						<div>
							<span class="pull-right"><i class="fa fa-caret-<?php echo $vhlcntarrow; ?> text-primary m-y-xs"></i></span>
							<span class="text-muted l-h-1x"><i class="ion-android-car text-muted"></i></span>
						</div>
						<div class="text-center">
							<h2 class="text-center _600"><?php echo $vehicles_total; ?></h2>
							<p class="text-muted m-b-md">Total Vehicles</p>
							<div>
								<span data-ui-jp="sparkline" data-ui-options="[<?php echo $vhlcnt; ?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
							</div>
						</div>
					</div>
				</div>
			</div>






			<div class="padding">

				<div class="row">
					<div class="col-sm-6">
						<div class="box">
							<div class="box-header">
								<span class="label white text-success pull-right">in Last 30 Days</span>
								<span class="label success pull-right"><?php echo $recent_contacts; ?></span>
								<h3>New Contacts</h3>
							</div>
							<div class="p-b-sm">
								<ul class="list no-border m-a-0">
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar danger">
												<span><?php echo ucfirst(substr("$customer1", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer1id; ?>"><?php echo $customer1; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer1type; ?></small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar purple">
												<span><?php echo ucfirst(substr("$customer2", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer2id; ?>"><?php echo $customer2; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer2type; ?></small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar info">
												<span><?php echo ucfirst(substr("$customer3", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer3id; ?>"><?php echo $customer3; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer3type; ?></small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar warning">
												<span><?php echo ucfirst(substr("$customer4", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer4id; ?>"><?php echo $customer4; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer4type; ?></small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar success">
												<span><?php echo ucfirst(substr("$customer5", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer5id; ?>"><?php echo $customer5; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer5type; ?></small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar grey">
												<span><?php echo ucfirst(substr("$customer6", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer6id; ?>"><?php echo $customer6; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $customer6type; ?></small>
										</div>
									</li>
								</ul>
							</div>
						</div>

						<div class="box">
							<div class="box-header">
								<span class="label white text-success pull-right">Total Sellers</span>
								<span class="label success pull-right"><?php echo $totalshops; ?></span>
								<h3>Top Sellers</h3>
							</div>
							<div class="p-b-sm">
								<ul class="list no-border m-a-0">
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar success">
												<span><?php echo ucfirst(substr("$shop1", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop1id; ?>"><?php echo $shop1; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop1amount; ?> AED</small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar warning">
												<span><?php echo ucfirst(substr("$shop2", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop2id; ?>"><?php echo $shop2; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop2amount; ?> AED</small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar primary">
												<span><?php echo ucfirst(substr("$shop3", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop3id; ?>"><?php echo $shop3; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop3amount; ?> AED</small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar warn">
												<span><?php echo ucfirst(substr("$shop4", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop4id; ?>"><?php echo $shop4; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop4amount; ?> AED</small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar brown">
												<span><?php echo ucfirst(substr("$shop5", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop5id; ?>"><?php echo $shop5; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop5amount; ?> AED</small>
										</div>
									</li>
									<li class="list-item">
										<a href="#" class="list-left">
											<span class="w-40 avatar danger">
												<span><?php echo ucfirst(substr("$shop6", 0, 1)); ?></span>
											</span>
										</a>
										<div class="list-body">
											<div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop6id; ?>"><?php echo $shop6; ?></a></div>
											<small class="text-muted text-ellipsis"><?php echo $shop6amount; ?> AED</small>
										</div>
									</li>
								</ul>
							</div>
						</div>


					</div>

					<div class="col-sm-6">
						<?php if ($_SESSION['role'] == 'admin' || $_SESSION['username'] == 'noushad') { ?>
							<div class="box">
								<div class="box-header">
									<h3 style="color:red;"><b>Latest Salesorders to approve</b></h3>
								</div>


								<?php
								//  $sql_po_appr = "SELECT * FROM `sales_order` WHERE approve != 1 ORDER BY id DESC LIMIT 10";
								//  $query_po_appr = mysqli_query($conn,$sql_po_appr);
								//  while($fetch_po_appr = mysqli_fetch_array($query_po_appr)){
								?>
								<!--<ul class="list no-border m-a-0">-->
								<!--<li class="list-item">-->
								<!--<div class="list-body"><a href="<?php echo $baseurl; ?>/edit/approve_po?id=<?php echo $fetch_po_appr['id']; ?>">PO|<?php echo $fetch_po_appr['order_referance']; ?><span class="pull-right text-danger">Approve</span></a></div>-->
								<!--</li>-->
								<!--</ul>-->
								<?php // } 
								?>



								<form style="padding-bottom:10px;" role="form" action="<?php echo $baseurl; ?>/edit/po_approval" method="post">
									<div class="form-group row">
										<label for="type" align="right" class="col-sm-3 form-control-label">Sales Order</label>
										<div class="col-sm-5">
											<select name="order_refrence" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
												<option value="">Select Sales Order</option>
												<?php
												$sql_po_appr = "SELECT * FROM `sales_order` WHERE approve != 1 ORDER BY id DESC";
												$query_po_appr = mysqli_query($conn, $sql_po_appr);
												while ($fetch_po_appr = mysqli_fetch_array($query_po_appr)) {
												?>
													<option value="<?php echo $fetch_po_appr['order_referance']; ?>">PO|<?php echo $fetch_po_appr['order_referance']; ?></option>
												<?php } ?>
											</select>
										</div>
										<button name="submit_appr" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Approve</button>
									</div>
								</form>


							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>



		<!-- ############ Sidebar Start-->


		<div class="col-lg w-lg w-auto-md white bg">
			<div>
				<div class="p-a">
					<h6 class="text-muted m-a-0">Last Login</h6>
				</div>
				<div class="list inset">


					<?php
					$username = $_SESSION['username'];
					$sql = "select * from login_log where username='$username' ORDER BY `id` DESC LIMIT 1,1";

					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							if ($row["status"] == 'success') {
					?>
								<a class="list-item" data-toggle="modal" data-target="#login-log" data-dismiss="modal">
									<div class="sl-date text-muted"><b class="label label-xs rounded success"></b> &nbsp;
										<?php echo "<b>" . strtoupper($row["time"]) . "</b><br/>from <b>" . $row["location"] . "</b><br/>with IP <b>" .
											$row["ip"] . "</b> was successfull!"; ?></div>
								</a>
							<?php
							} else {
							?>
								<a class="list-item" data-toggle="modal" data-target="#login-log" data-dismiss="modal">
									<div class="sl-date text-muted"><b class="label label-xs rounded danger"></b> &nbsp;
										<?php echo "<b>" . strtoupper($row["time"]) . "</b><br/>from <b>" . $row["location"] . "</b><br/>with IP <b>" .
											$row["ip"] . "</b> was failed!"; ?></div>
								</a>
					<?php
							}
						}
					}
					?>
					</a>
				</div>
				<div class="p-a">
					<h6 class="text-muted m-a-0">Activities</h6>
				</div>
				<div class="streamline streamline-theme m-b">
					<?php
					$sql = "select * from activity_log ORDER BY `id` DESC LIMIT 28";

					$result = mysqli_query($conn, $sql);
					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_assoc($result)) {
							if ($row["process"] == 'delete') {
					?>
								<div class="sl-item b-danger">
									<div class="sl-content">
										<!--<div><?php echo strtoupper(substr($row["code"], 0, 3)) . sprintf("%04d", substr($row["code"], 3)); ?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>-->
										<div><?php echo strtoupper($row["code"]) ?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>
										<div class="sl-date text-muted"><?php echo strtoupper($row["time"]); ?></div>
									</div>
								</div>
							<?php
							} elseif ($row["process"] == 'edit') {
							?>
								<div class="sl-item b-info">
									<div class="sl-content">
										<!--<div><?php echo strtoupper(substr($row["code"], 0, 3)) . sprintf("%04d", substr($row["code"], 3)); ?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>-->
										<div><?php echo strtoupper($row["code"]) ?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>
										<div class="sl-date text-muted"><?php echo strtoupper($row["time"]); ?></div>
									</div>
								</div>
							<?php
							} elseif ($row["process"] == 'add') {
							?>
								<div class="sl-item b-success">
									<div class="sl-content">
										<!--<div><?php echo strtoupper(substr($row["code"], 0, 3)) . sprintf("%04d", substr($row["code"], 3)); ?> was added by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>-->
										<div><?php echo strtoupper($row["code"]) ?> was added by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>
										<div class="sl-date text-muted"><?php echo strtoupper($row["time"]); ?></div>
									</div>
								</div>
							<?php
							} elseif ($row["process"] == 'status') {
							?>
								<div class="sl-item b-warning">
									<div class="sl-content">
										<!--<div><?php echo strtoupper(substr($row["code"], 0, 3)) . sprintf("%04d", substr($row["code"], 3)); ?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>-->
										<div><?php echo strtoupper($row["code"]) ?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]); ?></a>.</div>
										<div class="sl-date text-muted"><?php echo strtoupper($row["time"]); ?></div>
									</div>
								</div>
							<?php
							} else {
							?>
								<div class="sl-item b-warn">
									<div class="sl-content">
										<div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]); ?></a>.</div>
										<div class="sl-date text-muted"><?php echo strtoupper($row["time"]); ?></div>
									</div>
								</div>
					<?php
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<!-- ############ Sidebar End-->




	<!-- ############ Recent Login Side Popup Start-->

	<div class="modal fade inactive" id="login-log" data-backdrop="false">
		<div class="modal-right w-xxl dark-white b-l">
			<div class="row-col">
				<a data-dismiss="modal" class="pull-right text-muted text-lg p-a-sm m-r-sm">&times;</a>
				<div class="p-a b-b">
					<span class="h5">Last 25 Login List</span>
				</div>
				<div class="row-row light">
					<div class="row-body scrollable hover">
						<div class="row-inner">
							<div class="p-a-md">

								<?php
								$username = $_SESSION['username'];
								$sql = "select * from login_log where username='$username' ORDER BY `id` DESC LIMIT 25";

								$result = mysqli_query($conn, $sql);
								if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) {
								?>

										<div class="m-b">

											<?php
											if ($row["status"] == 'success') {
											?>
												<a class="pull-left w-50 m-r-sm">
													<button class="btn btn-sm btn-icon success rounded"><i class="ion-thumbsup"></i></button>
												</a>
												<div class="clear">
													<div class="p-a p-y-sm dark-white inline r">
														<?php echo $row["location"] . " [" . $row["ip"] . "] - Successfull"; ?>
													</div>
												<?php
											} else {
												?>
													<a class="pull-left w-50 m-r-sm">
														<button class="btn btn-sm btn-icon danger rounded"><i class="ion-thumbsdown"></i></button>
													</a>
													<div class="clear">
														<div class="p-a p-y-sm dark-white inline r">
															<?php echo $row["location"] . " [" . $row["ip"] . "] - Failed"; ?>
														</div>
													<?php
												}
													?>
													<div class="text-muted text-xs m-t-xs"><i class="fa fa-ok text-success"></i><?php echo strtoupper($row["time"]); ?></div>
													</div>
												</div>

										<?php
									}
								}
										?>

										</div>
							</div>
						</div>
					</div>
					<div class="p-a b-t">
						Listed above are logins with username (<?php echo $username; ?>)
					</div>
				</div>
			</div>
		</div>

		<!-- ############ Recent Login Side Popup End-->
		<!-- ############ PAGE END-->
	</div>
</div>
<!-- / -->

<div class="app-body mobile-content">
	<div style="background-color: #a3cfe5;" class="row-col warning">
		<div class="row-cell">
			<div class="text-center col-sm-6 offset-sm-3 p-y-lg">
				<img align="center" src="<?php echo $baseurl; ?>/images/logo.png">
				<h1 class="display-4 m-y-lg">WELCOME</h1>
				<h2 class="display-5 m-y-lg">To Our Admin Panel.</h2>
				<p class="m-y text-muted h4">
					for any related queries contact:
					<span>info@gulfitinnovations.com | +971 506 989 487</span>
				</p>
			</div>
		</div>
	</div>
</div>


<?php include "includes/footer.php"; ?>