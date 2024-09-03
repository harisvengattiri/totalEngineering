<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Work Invoices</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/work_invoice" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/work_invoices" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/work_invoices?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Work 
              </th>
              <th data-hide="all">
                  Subdivision 
              </th>
              <th>
                  Due Date
              </th>
	      <th>
                  Collector
              </th>
              <th>
                  Status
              </th>
	      <th>
                  Due
              </th>
	      <th>
                  Paid
              </th>
	      <th data-hide="all">
                  Invoice
              </th>
              <th data-hide="all">
                  Issue Date
              </th>
	      <th data-hide="all">
                  Method
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
		$sql = "SELECT * FROM work_invoices ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM work_invoices ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>WRI<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php
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
				?></td>
              <td><?php echo $row["division"];?></td>
              <td><?php echo $row["duedate"];?></td>

              <td><?php
                                $collector=$row["collector"];
                                $subsql2 = "SELECT name FROM customers where id=$collector";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>




<?php 
if($row["status"]=="Unpaid")
{
$color="danger";
}
elseif($row["status"]=="Paid")
{
$color="success";
}
elseif($row["status"]=="Invoiced")
{
$color="info";
}
elseif($row["status"]=="Pending")
{
$color="warn";
}
elseif($row["status"]=="Partial")
{
$color="warning";
}
else
{
$color="white";
}
if($i++%10<5) { ?>
<td>
    <div class="btn-group dropdown dropdown">
      <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $row["status"];?></button>
      <div class="dropdown-menu dropdown-menu-scale">
        <a class="dropdown-item">Change Status</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Unpaid">Unpaid</a>                                       		                          
        <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Paid">Paid</a>
        <a class="dropdown-item info" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Invoiced">Invoiced</a>
        <a class="dropdown-item warn" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Pending">Pending</a>
        <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Partial">Partial</a>
      </div>
</td>
<?php} else { ?>

<td>
    <div class="btn-group dropdown dropup">
      <button class="btn btn-sm <?php echo $color;?> dropdown-toggle" data-toggle="dropdown"><?php echo $row["status"];?></button>
      <div class="dropdown-menu dropdown-menu-scale">  
        <a class="dropdown-item warning" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Partial">Partial</a>  
        <a class="dropdown-item warn" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Pending">Pending</a>
        <a class="dropdown-item info" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Invoiced">Invoiced</a>                                       		                          
        <a class="dropdown-item success" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Paid">Paid</a>
        <a class="dropdown-item danger" href="<?php echo $baseurl;?>/edit/change_status?wri=<?php echo $row["id"];?>&status=Unpaid">Unpaid</a> 
        <div class="dropdown-divider"></div>
        <a class="dropdown-item">Change Status</a>
      </div>
</td>
<?php } ?>



              <td><?php echo 0+$row["due"];?> Dhs</td>
              <td><?php echo 0+$row["paid"];?> Dhs</td>
              <td><?php echo $row["invoice"];?></td>
              <td><?php echo $row["issuedate"];?></td>
              <td><?php echo $row["method"];?></td>

              <td><?php echo $row["notes"];?></td>
              <td><a href="<?php echo $baseurl; ?>/edit/work_invoice?wri=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href="<?php echo $baseurl; ?>/delete/work_invoice?wri=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
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
