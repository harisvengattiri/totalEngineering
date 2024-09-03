<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php
if(isset($_POST['submit']))
{

$operators=$_POST["operators"];

$sql = "INSERT INTO `operators` (`operators`) 
VALUES ('$operators')";
if ($conn->query($sql) === TRUE) {
    $status="success";
       $last_id = $conn->insert_id;
       $date1=date("d/m/Y h:i:s a");
       $username=$_SESSION['username'];
       $code="prj".$last_id;
       $query=mysqli_real_escape_string($conn, $sql);
       $sql = "INSERT INTO activity_log (time, process, code, user, query) 
                  values ('$date1', 'add', '$code', '$username', '$query')";
       $result = mysqli_query($conn, $sql);
} else {
    $status="failed";
}}
?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Operators</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/operators" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/operators" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/operators?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
<?php 
} ?>
</span>
    </div><br/>
    <div class="box-body">
         <form role="form" action="<?php echo $baseurl;?>/operators" method="post">
            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Operator</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="operators" id="value" placeholder="Operator">
              </div>
            
              <div class="col-sm-2">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
              </div>
              </div>
          </form>
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
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">        <thead>
          <tr>




              <th data-toggle="true">
                  Code
              </th>
              <th>
                  Operators
              </th>
              
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM operators ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM operators ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>OPR<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["operators"];?></td>
             
              
<!--			    <td><?php 
				$customer=$row["customer"];
				$subsql = "SELECT name FROM customers where id=$customer";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				echo $subrow["name"];
				}} 
				?></td>-->
              
              
              
<!--               <td><?php 
				$id=$row["id"];
                                $inccost=0;
                                $totalinc=0;
				$subsql = "SELECT amount FROM payments where work=$id AND wtype='project'";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				$incpay=$subrow["amount"];
                                $totalinc=$totalinc+$incpay;
				}}
				$subsql = "SELECT paid FROM work_invoices where work=$id AND wtype='project' and paid>0";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				$incinv=$subrow["paid"];
                                $totalinc=$totalinc+$incinv;
				}}
                                echo $totalinc;
				?> Dhs</td>
              <td><?php
				$id=$row["id"];
                                $expcost=0;
                                $totalexp=0;
				$subsql = "SELECT amount FROM purchases where forid=$id AND work='project'";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				$expcost=$subrow["amount"];
                                $totalexp=$totalexp+$expcost;
				}}
                                echo $totalexp;
				?> Dhs</td>
              <td><?php 
				$value=$row["value"];
				$estimate=$row["estimate"];
				$profit=$totalinc-$totalexp;
if ($profit<0) 
{ ?><i style="color:red" class="fa fa-arrow-down"></i> <?php }
else
{ ?><i style="color:green" class="fa fa-arrow-up"></i></span> <?php }
				echo abs($profit); ?> Dhs</td>
              <td><?php echo $value-$estimate;?> Dhs</td>
              <td><?php 
				$balance=$value-$totalinc;
if ($balance>0) 
{ ?><i style="color:red" class="fa fa-arrow-down"></i> <?php }
else
{ ?><i style="color:green" class="fa fa-arrow-up"></i></span> <?php }
				echo abs($balance); ?> Dhs</td>
              <td><?php
			$tags=$row["tags"];
			$tags = json_decode($tags);
			$arrlength=count($tags);
			for($x=0;$x<$arrlength;$x++)
  			{
  			echo '<a href="#'.$tags[$x].'"class="btn btn-xs success rounded">'.$tags[$x].'</a>&nbsp;';
  			}
              ?></td>
              <td><?php echo $row["notes"];?></td>-->
              <!--<td><a href="<?php echo $baseurl; ?>/production_cash_flow?prj=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <!--<a href="<?php echo $baseurl; ?>/edit/production?prj=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
             <td><a href="<?php echo $baseurl; ?>/delete/operators?prj=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
