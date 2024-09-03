<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Cheque Voucher</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/liability" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Liability</a></button>&nbsp;
         <a href="<?php echo $baseurl; ?>/add/voucher" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Voucher</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/voucher_cheque" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/voucher_cheque?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
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
                else
		{
		$list_count = 10;
                }
                ?>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                  Code
              </th>
              <th>
                  Voucher
              </th>
              <th>
                  Supplier
              </th>
              <th>
                  Date
              </th>
	          <th>
                  Value
              </th>
              <th>
                  Status
              </th>
              <th>
                  Date
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
// 		if (isset($_GET['cat']))
// 		{
// 		$cat=$_GET['cat'];
// 		$sql = "SELECT * FROM voucher where category='$cat' ORDER BY id DESC";
//         }
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM voucher WHERE pmethod='cheque' ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM voucher WHERE pmethod='cheque' ORDER BY id DESC LIMIT 0,100";
                }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            
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
        ?>
          <tr>
              <td>PVR|<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["voucher"];?></td>
              <td><?php
                $name=$row["name"];
                
                $sql1 = "SELECT name FROM customers WHERE id='$name'";
                $query1=mysqli_query($conn,$sql1);
                $fetch1=mysqli_fetch_array($query1);
                echo $name1=$fetch1['name'];
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td>
                   <div class="btn-group dropdown dropdown">
                     <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                     <div class="dropdown-menu dropdown-menu-scale">
                       <!--<a class="dropdown-item">Change Status</a>-->
                       <div class="dropdown-divider"></div>                                       		                          
                       <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status_voucher?id=<?php echo $row["id"];?>&status=Uncleared">Uncleared</a>
                       <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status_voucher?id=<?php echo $row["id"];?>&status=Cleared">Cleared</a>
                       <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status_voucher?id=<?php echo $row["id"];?>&status=Bounce">Bounce</a>
                     </div>
              </td>
              <td>
              <a href="<?php echo $baseurl; ?>/edit/voucher_clearance?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info">C</button></a>
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
