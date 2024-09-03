<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Miscellaneous Income</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/miscellaneous" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/miscellaneous" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/miscellaneous?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Miscellaneous
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Particulars
              </th>
              <th>
                  Date
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
                  Status
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
		$sql = "SELECT * FROM miscellaneous ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM miscellaneous ORDER BY id DESC LIMIT 0,100";
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
              <td>MSC<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["particulars"];?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?></td>
              <td><?php echo $row["vat"];?></td>
              <td><?php echo $row["total"];?></td>
              <td>
                   <div class="btn-group dropdown dropdown">
                     <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $status;?></button>
                     <div class="dropdown-menu dropdown-menu-scale">
                       <div class="dropdown-divider"></div>                                       		                          
                       <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status_misc?id=<?php echo $row["id"];?>&status=Uncleared">Uncleared</a>
                       <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status_misc?id=<?php echo $row["id"];?>&status=Cleared">Cleared</a>
                    </div>
              </td>
              <td>
              <a href="<?php echo $baseurl; ?>/edit/miscellaneous?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>    
              <a href="<?php echo $baseurl; ?>/edit/miscelanious_clearance?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info">C</button></a>    
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/miscellaneous?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
