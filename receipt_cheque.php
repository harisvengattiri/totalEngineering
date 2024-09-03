<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Receivable I</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/receipt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/receipt_cheque" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/receipt_cheque?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    
    <style>
    .list-active{
    color: rgba(255, 255, 255, 0.87) !important;
    background-color: #2196f3;
    }
</style>
    <?php
    $sql="SELECT COUNT(id) as receipt FROM reciept WHERE pmethod='cheque'";
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
    <a href="<?php echo $baseurl; ?>/receipt_cheque?list=<?php echo $i;?>"><button style="margin:2px 0;padding:5px 8px;" class="btn btn-outline btn-sm rounded b-info text-info <?php if($i==$list){?>list-active<?php } ?>">
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
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
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
        }
        else
		{
		$list_count = 10;
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
                  Status
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
		$sql = "SELECT * FROM reciept WHERE pmethod='cheque' ORDER BY id DESC LIMIT 0,1000";
        }
        else if (isset($_GET['list']))
		{
		$list = $_GET['list'];
		    if($list==1){$start=1;}
		    else { $start = ($list-1)*1000;}
		$sql = "SELECT * FROM reciept WHERE pmethod='cheque' ORDER BY id ASC LIMIT $start,1000";
        }
        else
		{
		$sql = "SELECT * FROM reciept WHERE pmethod='cheque' ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $rcp=$row["id"];
              $name1=$row["customer"];
              $site=$row['site'];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
            //    $sqlsite="SELECT p_name from customer_site where id='$site'";
            //    $querysite=mysqli_query($conn,$sqlsite);
            //    $fetchsite=mysqli_fetch_array($querysite);
            //    $site1=$fetchsite['p_name'];
               
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
	      
              
              <td>
                   <div class="btn-group dropdown dropdown">
                     <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                     <div class="dropdown-menu dropdown-menu-scale">
                       <!--<a class="dropdown-item">Change Status</a>-->
                       <div class="dropdown-divider"></div>                                       		                          
                       <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status_pmt?id=<?php echo $row["id"];?>&status=Uncleared">Uncleared</a>
                       <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status_pmt?id=<?php echo $row["id"];?>&status=Cleared">Cleared</a>
                       <a class="dropdown-item indigo" href="<?php echo $baseurl;?>/edit/change_status_pmt?id=<?php echo $row["id"];?>&status=Hold">Hold</a>
                       <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status_pmt?id=<?php echo $row["id"];?>&status=Bounce">Bounce</a>
                     </div>
                    </div>
              </td>
              
              
              <td>
              <!--<a href="<?php // echo $baseurl; ?>/prints/reciept?rcp=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <a href="<?php echo $baseurl; ?>/view/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <!--<a href="<?php // echo $baseurl; ?>/delete/quotation?id=<?php // echo $row["id"];?>&or=<?php // echo $row["order_referance"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
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
