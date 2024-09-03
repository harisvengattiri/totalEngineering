<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php	
error_reporting(E_ERROR | E_PARSE);										
		$sql = "SELECT count(*) as project_total from projects";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$project_total=$row['project_total'];
		}
		}									
		$sql = "SELECT count(*) as maintenance_total from maintenances";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$maintenance_total=$row['maintenance_total'];
		}
		}									
		$sql = "SELECT count(*) as contacts_total from customers";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$contacts_total=$row['contacts_total'];
		}
		}									
		$sql = "SELECT count(*) as staff_total from customers where type='staff'";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$staff_total=$row['staff_total'];
		}
		}									
		$sql = "SELECT count(*) as works_total from works";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$works_total=$row['works_total'];
		}
		}								
		$sql = "SELECT count(*) as vehicles_total from vehicles";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$vehicles_total=$row['vehicles_total'];
		}
		}								
		$sql = "SELECT count(*) as recent_contacts from customers WHERE (`current_timestamp` > DATE_SUB(now(), INTERVAL 30 DAY))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$recent_contacts=0+$row['recent_contacts'];
		}
		}								
		$sql = "SELECT name,id,type from customers ORDER BY id DESC LIMIT 6";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$customer1=$customer2;
			$customer2=$customer3;
			$customer3=$customer4;
			$customer4=$customer5;
			$customer5=$customer6;
			$customer6=$row['name'];
			$customer1id=$customer2id;
			$customer2id=$customer3id;
			$customer3id=$customer4id;
			$customer4id=$customer5id;
			$customer5id=$customer6id;
			$customer6id=$row['id'];
			$customer1type=$customer2type;
			$customer2type=$customer3type;
			$customer3type=$customer4type;
			$customer4type=$customer5type;
			$customer5type=$customer6type;
			$customer6type=$row['type'];
		}
		}									
$sql = "SELECT count(*) as shops from customers WHERE type='Shop'";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$totalshops=$row['shops'];
		}
		}								
		$sql = "SELECT shop, sum(amount) as sum FROM (SELECT shop, amount FROM purchases UNION ALL SELECT shop, amount FROM office_expenses UNION ALL SELECT shop, amount FROM vehicle_expenses) AS top5shops GROUP BY shop ORDER BY sum(amount) DESC LIMIT 0,6";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{	
		$id=$row['shop'];								
		$sql2 = "SELECT name from customers where id='$id'";
                $result2 = mysqli_query($conn, $sql2);
		if (mysqli_num_rows($result2) > 0) 
		{
		while($row2 = mysqli_fetch_assoc($result2)) 
		{
			$shopname=$row2['name'];
		}
		}
		else
		{
			$shopname="Unknown";
		}
			$shop1=$shop2;
			$shop2=$shop3;
			$shop3=$shop4;
			$shop4=$shop5;
			$shop5=$shop6;
			$shop6=$shopname;
			$shop1id=$shop2id;
			$shop2id=$shop3id;
			$shop3id=$shop4id;
			$shop4id=$shop5id;
			$shop5id=$shop6id;
			$shop6id=$row['shop']; 
			$shop1amount=$shop2amount;
			$shop2amount=$shop3amount;
			$shop3amount=$shop4amount;
			$shop4amount=$shop5amount;
			$shop5amount=$shop6amount;
			$shop6amount=number_format("%!i",$row['sum']); 
		}
		}
				$monthstarter=date('Y-m-01');
				$month7 = date('F');
				$month6 = date("F", strtotime ( '-1 month' , strtotime ( $monthstarter ) )) ;
				$month5 = date("F", strtotime ( '-2 month' , strtotime ( $monthstarter ) )) ;
				$month4 = date("F", strtotime ( '-3 month' , strtotime ( $monthstarter ) )) ;
				$month3 = date("F", strtotime ( '-4 month' , strtotime ( $monthstarter ) )) ;
				$month2 = date("F", strtotime ( '-5 month' , strtotime ( $monthstarter ) )) ;
				$month1 = date("F", strtotime ( '-6 month' , strtotime ( $monthstarter ) )) ;									
$sql = "SELECT sum(amount) as sum FROM (SELECT amount,date FROM payments UNION ALL SELECT paid,duedate FROM work_invoices where paid>0) AS income group by month(STR_TO_DATE(date, '%d/%m/%Y'))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$incmonth1=0+$incmonth2;
			$incmonth2=0+$incmonth3;
			$incmonth3=0+$incmonth4;
			$incmonth4=0+$incmonth5;
			$incmonth5=0+$incmonth6;
			$incmonth6=0+$incmonth7;
			$incmonth7=0+$row['sum'];
		}
			$incgrowth=round((($incmonth7-$incmonth6)/$incmonth6)*100, 2);
		}				
$sql = "SELECT sum(amount) as sum FROM (SELECT amount,date FROM purchases UNION ALL SELECT amount,date FROM office_expenses UNION ALL SELECT amount,date FROM vehicle_expenses) AS expenses group by month(STR_TO_DATE(date, '%d/%m/%Y'))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$expmonth1=0+$expmonth2;
			$expmonth2=0+$expmonth3;
			$expmonth3=0+$expmonth4;
			$expmonth4=0+$expmonth5;
			$expmonth5=0+$expmonth6;
			$expmonth6=0+$expmonth7;
			$expmonth7=0+$row['sum'];
		}
			$expgrowth=round((($expmonth7-$expmonth6)/$expmonth6)*100, 2);
		}
			$promonth1=$incmonth1-$expmonth1;
			$promonth2=$incmonth2-$expmonth2;
			$promonth3=$incmonth3-$expmonth3;
			$promonth4=$incmonth4-$expmonth4;
			$promonth5=$incmonth5-$expmonth5;
			$promonth6=$incmonth6-$expmonth6;
			$promonth7=$incmonth7-$expmonth7;
			$progrowth = $promonth6 === 0 ? 0 : (($promonth7-$promonth6)/$promonth6)*100;
			$progrowth=round($progrowth, 2);
			if ($promonth6<0)
			{
			$progrowth=$progrowth/-1;
			}								
$sql = "SELECT sum(amount) as sum FROM purchases group by month(STR_TO_DATE(date, '%d/%m/%Y'))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$prcmonth1=0+$prcmonth2;
			$prcmonth2=0+$prcmonth3;
			$prcmonth3=0+$prcmonth4;
			$prcmonth4=0+$prcmonth5;
			$prcmonth5=0+$prcmonth6;
			$prcmonth6=0+$prcmonth7;
			$prcmonth7=0+$row['sum'];
		}
		}									
$sql = "SELECT sum(amount) as sum FROM office_expenses group by month(STR_TO_DATE(date, '%d/%m/%Y'))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$oxpmonth1=0+$oxpmonth2;
			$oxpmonth2=0+$oxpmonth3;
			$oxpmonth3=0+$oxpmonth4;
			$oxpmonth4=0+$oxpmonth5;
			$oxpmonth5=0+$oxpmonth6;
			$oxpmonth6=0+$oxpmonth7;
			$oxpmonth7=0+$row['sum'];
		}
		}									
$sql = "SELECT sum(amount) as sum FROM vehicle_expenses group by month(STR_TO_DATE(date, '%d/%m/%Y'))";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$vxpmonth1=0+$vxpmonth2;
			$vxpmonth2=0+$vxpmonth3;
			$vxpmonth3=0+$vxpmonth4;
			$vxpmonth4=0+$vxpmonth5;
			$vxpmonth5=0+$vxpmonth6;
			$vxpmonth6=0+$vxpmonth7;
			$vxpmonth7=0+$row['sum'];
		}
		}									
$sql = "SELECT sum(amount) as sum FROM office_expenses";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$oxptotal=0+$row['sum'];
		}
		}									
$sql = "SELECT sum(amount) as sum FROM vehicle_expenses";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$vxptotal=0+$row['sum'];
		}
		}										
$sql = "SELECT sum(amount) as sum FROM purchases";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$prctotal=0+$row['sum'];
		}
		}
			$exptotal=$vxptotal+$oxptotal+$prctotal;
			$vxpshare = $exptotal === 0 ? 0 : (($vxptotal)/$exptotal)*100;
			$oxpshare = $exptotal === 0 ? 0 : (($oxptotal)/$exptotal)*100;
			$prcshare = $exptotal === 0 ? 0 : (($prctotal)/$exptotal)*100;
			$vxpshare=round($vxpshare, 2);
			$oxpshare=round($oxpshare, 2);
			$prcshare=round($prcshare, 2);									
$sql = "SELECT COUNT(*) AS cnt FROM customers WHERE `current_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 day) group by date(`current_timestamp`);";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$custcnt="1";
		while($row = mysqli_fetch_assoc($result)) 
		{
			$custcnt=$custcnt.", ".$row['cnt'];
			$custcnt6=$custcnt7;
			$custcnt7=$row['cnt'];
		}
		}
		if ($custcnt6 > $custcnt7) 
		{
			$custcntarrow="down";
		}
		else 
		{
			$custcntarrow="up";
		}
											
$sql = "SELECT COUNT(*) AS cnt FROM payments WHERE wtype='project' AND `current_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 day) group by date(`current_timestamp`)";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$prjcnt="1";
		while($row = mysqli_fetch_assoc($result)) 
		{
			$prjcnt=$prjcnt.", ".$row['cnt'];
			$prjcnt6=$prjcnt7;
			$prjcnt7=$row['cnt'];
		}
		}
		if ($prjcnt6 > $prjcnt7) 
		{
			$prjcntarrow="down";
		}
		else 
		{
			$prjcntarrow="up";
		}											
$sql = "SELECT COUNT(*) AS cnt FROM payments WHERE wtype='maintenance' AND `current_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 day) group by date(`current_timestamp`)";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$mntcnt="1";
		while($row = mysqli_fetch_assoc($result)) 
		{
			$mntcnt=$mntcnt.", ".$row['cnt'];
			$mntcnt6=$mntcnt7;
			$mntcnt7=$row['cnt'];
		}
		}
		if ($mntcnt6 > $mntcnt7) 
		{
			$mntcntarrow="down";
		}
		else 
		{
			$mntcntarrow="up";
		}										
$sql = "SELECT COUNT(*) AS cnt FROM vehicle_expenses WHERE `current_timestamp` >= DATE_SUB(NOW(), INTERVAL 7 day) group by date(`current_timestamp`)";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$vhlcnt="1";
		while($row = mysqli_fetch_assoc($result)) 
		{
			$vhlcnt=$vhlcnt.", ".$row['cnt'];
			$vhlcnt6=$vhlcnt7;
			$vhlcnt7=$row['cnt'];
		}
		}
		if ($vhlcnt6 > $vhlcnt7) 
		{
			$vhlcntarrow="down";
		}
		else 
		{
			$vhlcntarrow="up";
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
							<span data-ui-jp="sparkline" data-ui-options="[<?php echo $custcnt;?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
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
							<span data-ui-jp="sparkline" data-ui-options="[<?php echo $prjcnt;?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
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
							<span data-ui-jp="sparkline" data-ui-options="[<?php echo $mntcnt;?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
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
							<span data-ui-jp="sparkline" data-ui-options="[<?php echo $vhlcnt;?>], {type:'line', height:20, width: '60', lineWidth:1, valueSpots:{'0:':'#818a91'}, lineColor:'#818a91', spotColor:'#818a91', fillColor:'', highlightLineColor:'rgba(120,130,140,0.3)', spotRadius:0}" class="sparkline inline"></span>
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
			              <span class="label success pull-right"><?php echo $recent_contacts;?></span>
			              <h3>New Contacts</h3>
			            </div>
			            <div class="p-b-sm">
				            <ul class="list no-border m-a-0">
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar danger">
					                  <span><?php echo ucfirst(substr("$customer1",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer1id;?>"><?php echo $customer1;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer1type;?></small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar purple">
					                  <span><?php echo ucfirst(substr("$customer2",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer2id;?>"><?php echo $customer2;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer2type;?></small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar info">
					                  <span><?php echo ucfirst(substr("$customer3",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer3id;?>"><?php echo $customer3;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer3type;?></small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar warning">
					                  <span><?php echo ucfirst(substr("$customer4",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer4id;?>"><?php echo $customer4;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer4type;?></small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar success">
					                  <span><?php echo ucfirst(substr("$customer5",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer5id;?>"><?php echo $customer5;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer5type;?></small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar grey">
					                  <span><?php echo ucfirst(substr("$customer6",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $customer6id;?>"><?php echo $customer6;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $customer6type;?></small>
				                </div>
				              </li>
				            </ul>
			            </div>
			        </div>
			        
                    <div class="box">
			            <div class="box-header">
			              <span class="label white text-success pull-right">Total Sellers</span>
			              <span class="label success pull-right"><?php echo $totalshops;?></span>
			              <h3>Top Sellers</h3>
			            </div>
			            <div class="p-b-sm">
				            <ul class="list no-border m-a-0">
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar success">
					                  <span><?php echo ucfirst(substr("$shop1",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop1id;?>"><?php echo $shop1;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop1amount;?> AED</small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar warning">
					                  <span><?php echo ucfirst(substr("$shop2",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop2id;?>"><?php echo $shop2;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop2amount;?> AED</small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar primary">
					                  <span><?php echo ucfirst(substr("$shop3",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop3id;?>"><?php echo $shop3;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop3amount;?> AED</small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                  <span class="w-40 avatar warn">
					                  <span><?php echo ucfirst(substr("$shop4",0,1));?></span>
					              </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop4id;?>"><?php echo $shop4;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop4amount;?> AED</small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar brown">
					                  <span><?php echo ucfirst(substr("$shop5",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop5id;?>"><?php echo $shop5;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop5amount;?> AED</small>
				                </div>
				              </li>
				              <li class="list-item">
				                <a href="#" class="list-left">
				                	<span class="w-40 avatar danger">
					                  <span><?php echo ucfirst(substr("$shop6",0,1));?></span>
					                </span>
				                </a>
				                <div class="list-body">
				                  <div><a href="<?php echo $baseurl; ?>/customers?id=<?php echo $shop6id;?>"><?php echo $shop6;?></a></div>
				                  <small class="text-muted text-ellipsis"><?php echo $shop6amount;?> AED</small>
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
				            <!--<div class="list-body"><a href="<?php echo $baseurl;?>/edit/approve_po?id=<?php echo $fetch_po_appr['id'];?>">PO|<?php echo $fetch_po_appr['order_referance'];?><span class="pull-right text-danger">Approve</span></a></div>-->
				            <!--</li>-->
				            <!--</ul>-->
				            <?php // } ?>
				              
				            
    						
    						<form style="padding-bottom:10px;" role="form" action="<?php echo $baseurl;?>/edit/po_approval" method="post">
    						<div class="form-group row">
                               <label for="type" align="right" class="col-sm-3 form-control-label">Sales Order</label>
                               <div class="col-sm-5">
                                 <select name="order_refrence" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}" required>
                                 <option value="">Select Sales Order</option>
                                  <?php
        						    $sql_po_appr = "SELECT * FROM `sales_order` WHERE approve != 1 ORDER BY id DESC";
        						    $query_po_appr = mysqli_query($conn,$sql_po_appr);
        						    while($fetch_po_appr = mysqli_fetch_array($query_po_appr)){
        						  ?>
                                    <option value="<?php echo $fetch_po_appr['order_referance'];?>">PO|<?php echo $fetch_po_appr['order_referance'];?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            <button name="submit_appr" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Approve</button>
                            </div>
                            </form>
    						
    						
    					</div>
			        <?php } ?>
			        
					<div class="box">
						<div class="box-header">
							<h3>Daily Works</h3>
							<small><?php echo $works_total;?> Works, <?php echo $staff_total;?> Employees</small>
						</div>
						<div class="box-tool">
					        <ul class="nav">
					          <li class="nav-item inline dropdown">
					            <a class="nav-link text-muted p-x-xs" data-toggle="dropdown">
					              <i class="fa fa-ellipsis-v"></i>
					            </a>
					            <div class="dropdown-menu dropdown-menu-scale pull-right">
					              <a class="dropdown-item" href="<?php echo $baseurl;?>/works">Daily Works</a>
					              <a class="dropdown-item" href="<?php echo $baseurl;?>/work_invoices">Work Invoices</a>
					              <a class="dropdown-item" href="<?php echo $baseurl;?>/staff">Staff</a>
					              <div class="dropdown-divider"></div>
					              <a class="dropdown-item" href="<?php echo $baseurl;?>/reports">Reports</a>
					            </div>
					          </li>
					        </ul>
					    </div>
						<div class="box-body">
						  	<div class="streamline">
        <?php
        $sql = "SELECT * FROM works order by id DESC LIMIT 9";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        while($row = mysqli_fetch_assoc($result)) 
	{
              if($row["wtype"]=="maintenance")
              {
              $workcode="MNT".sprintf("%04d", $row["work"]);
              }
              elseif($row["wtype"]=="project")
              {
              $workcode="PRJ".sprintf("%04d", $row["work"]);
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
				$workname=$subrow["name"];
				}} 
                                $staff=$row["staff"];
                                $subsql2 = "SELECT name FROM customers where id=$staff";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				$staff= $subrow2["name"];
				}} 
        ?>
		<div class="sl-item b-success">
		<div class="sl-content">
		<div class="sl-date text-muted"><?php echo $row["date"];?> - DWR<?php echo sprintf("%04d", $row["id"]);?></div>
		<div><?php echo $row["wname"];?> by <a class="text-info"><?php echo $staff;?></a> for <a class="text-info"><?php echo $workcode;?></a> [<?php echo $workname;?>]</div>
		</div>
		</div>
		<?php
		}
		}
		?>
						    </div>
						</div>
					  	<div class="box-footer">
					  		<a href="<?php echo $baseurl;?>/works" class="btn btn-xs white rounded">More</a>
					  	</div>
				  	</div>
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
                   $username=$_SESSION['username'];
                   $sql = "select * from login_log where username='$username' ORDER BY `id` DESC LIMIT 1,1";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["status"]=='success') 
		   {
		   ?>
	           <a class="list-item" data-toggle="modal" data-target="#login-log" data-dismiss="modal">
	           <div class="sl-date text-muted"><b class="label label-xs rounded success"></b> &nbsp;
                   <?php echo "<b>".strtoupper($row["time"])."</b><br/>from <b>".$row["location"]."</b><br/>with IP <b>".
                   $row["ip"]."</b> was successfull!";?></div>
                   </a>
		   <?php
		   }
		   else 
		   {
		   ?>
	           <a class="list-item" data-toggle="modal" data-target="#login-log" data-dismiss="modal">
	           <div class="sl-date text-muted"><b class="label label-xs rounded danger"></b> &nbsp;
                   <?php echo "<b>".strtoupper($row["time"])."</b><br/>from <b>".$row["location"]."</b><br/>with IP <b>".
                   $row["ip"]."</b> was failed!";?></div>
                   </a>
		   <?php
		   }}}
		   ?>
	            </a>
	        </div>
	        <div class="p-a">
	        	<h6 class="text-muted m-a-0">Activities</h6>
	        </div>
	        <div class="streamline streamline-theme m-b">
                   <?php
                   $sql = "select * from last_activities ORDER BY `id` DESC LIMIT 28";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }}}
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
                   $username=$_SESSION['username'];
                   $sql = "select * from login_log where username='$username' ORDER BY `id` DESC LIMIT 25";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
                   ?>

  	            <div class="m-b">

                   <?php
		   if ($row["status"]=='success') 
		   {
		   ?>
  	              <a class="pull-left w-50 m-r-sm">
  	              <button class="btn btn-sm btn-icon success rounded"><i class="ion-thumbsup"></i></button>
  	              </a>
  	              <div class="clear">
  	                <div class="p-a p-y-sm dark-white inline r">
  	                  <?php echo $row["location"]." [".$row["ip"]."] - Successfull";?>
  	                </div>
                   <?php
		   }
		   else
		   {
		   ?>
  	              <a class="pull-left w-50 m-r-sm">
  	               <button class="btn btn-sm btn-icon danger rounded"><i class="ion-thumbsdown"></i></button>
  	              </a>
  	              <div class="clear">
  	                <div class="p-a p-y-sm dark-white inline r">
  	                  <?php echo $row["location"]." [".$row["ip"]."] - Failed";?>
  	                </div>
                   <?php
		   }
		   ?>
  	                <div class="text-muted text-xs m-t-xs"><i class="fa fa-ok text-success"></i><?php echo strtoupper($row["time"]);?></div>
  	              </div>
  	            </div>
  	          
                   <?php
                   }}
                   ?>  
  	            
  	          </div>
  	        </div>
  	      </div>
  	    </div>
  	    <div class="p-a b-t">
Listed above are logins with username (<?php echo $username;?>)
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
			<img align="center" src="<?php echo $baseurl;?>/images/logo.png">
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
  
  
<?php include "includes/footer.php";?>
