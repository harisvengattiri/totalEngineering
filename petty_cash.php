<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Petty Cash Flow</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/petty_cash" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/petty_cash" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/petty_cash?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Amount
              </th>
              <th>
                  VAT
              </th>
              <th>
                  Total
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
		$sql = "SELECT * FROM petty_cash ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM petty_cash ORDER BY id DESC LIMIT 0,100";
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
        ?>
          <tr>
              <td>PTC<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $staff;?></td>
              <td><?php echo $row["amount"];?></td>
              <td><?php echo $row["vat"];?></td>
              <td><?php echo $row["total"];?></td>
              <td>
              <a href="<?php echo $baseurl; ?>/view/petty_list?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/petty_cash?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/petty_cash?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
