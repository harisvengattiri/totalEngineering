<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2> Stock Return </h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/stock_return" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/stock_return" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/stock_return?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
<!--              <th data-toggle="true">
                  Code
              </th>-->
<!--              <th data-toggle="true">
                  Date
              </th>
              <th data-hide="all">
                  Start Date
              </th>
              <th data-hide="all">
                  End Date
              </th>
              <th data-hide="all">
                  Customer
              </th>
              <th data-hide="all">
                  Tags
              </th>-->
              <th>
                 RETURN No
              </th>
              
	      <th>
                  Customer
              </th>
              <th>
                  Delivery No
              </th>
<!--              <th data-hide="all">
                  Notes
              </th>-->
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM stock_return GROUP BY dn ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM stock_return GROUP BY dn ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
             
        ?>
          <tr>
              <td>RTN<?php echo sprintf("%05d",$row["id"]);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo sprintf("%05d",$row["dn"]);?></td>
	      
         <?php 
	    //  session_start();
	     $company=$_SESSION["username"];
	     ?>
              <td>
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/stock_return?id=<?php echo $row["id"];?>&open=<?php echo $company;?>" title="Print"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/stock_return?id=<?php echo $row["dn"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/stock_return?id=<?php echo $row["dn"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
