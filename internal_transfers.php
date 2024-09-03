<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php  
$status=$_GET["status"]; 
?>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="deleted") {?>
	<p><a class="list-group-item b-l-warning">
          <span class="pull-right text-warning"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label warning pos-rlt m-r-xs">
		  <b class="arrow right b-warning pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-warning">Your Deletion was Successfull!</span>
    </a></p>
	<?php } else if($status=="failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-close"></i></span>
		  <span class="text-danger">Your Submission was Failed!<?php echo $sql; ?></span>
    </a></p>
	<?php }?>
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Internal Transfers</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/internal_transfer" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/internal_transfers" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/internal_transfers?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  From
              </th>
              <th>
                  To
              </th>
              <th>
                  Date
              </th>
	      <th>
                  Amount
              </th>
              <th data-hide="all">
                  Notes
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
		$sql = "SELECT * FROM internal_transfers ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM internal_transfers ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>ITF<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php
                                $fromid=$row["fromid"];
                                $subsql2 = "SELECT name FROM customers where id=$fromid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php
                                $toid=$row["toid"];
                                $subsql2 = "SELECT name FROM customers where id=$toid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["notes"];?></td>
              <td><a href="<?php echo $baseurl; ?>/edit/internal_transfer?itf=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href="<?php echo $baseurl; ?>/delete/internal_transfer?itf=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"> 
              <button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
