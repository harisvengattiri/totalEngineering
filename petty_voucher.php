<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Petty Cash Voucher</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/petty_voucher" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/petty_voucher" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/petty_voucher?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Petty
              </th>
              <th>
                  Date
              </th>
              <th>
                  Staff
              </th>
              <th>
                  Payment
              </th>
              <th>
                  Cheque No
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
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM petty_voucher ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM petty_voucher ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $stf = $row['staff'];
             $sql_cust = "SELECT name FROM customers WHERE id='$stf'";
             $query_cust = mysqli_query($conn,$sql_cust);
             $fetch_cst = mysqli_fetch_array($query_cust);
             $staff = $fetch_cst['name'];
             
             $status=$row["status"];
             if($status=="Cleared")
                    {
                    $color="success";
                    }
                    else
                    {
                    $color="warning";
                    }
             
        ?>
          <tr>
              <td>PTC<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $staff;?></td>
              <td><?php echo $row["pmethod"];?></td>
              <td><?php echo $row["cheque_no"];?></td>
              <td><?php echo $row["amount"];?></td>
              <td>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/petty_voucher?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
