<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Production</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/production" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/production" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/production?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
       <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">        <thead>
          <tr>
              <th>
                  Production
              </th>
              <th>
                  Date
              </th>
              <th>
                  Item
              </th>
              <th>
                  Day
              </th>
              <th>
                  Night
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
		$sql = "SELECT * FROM prod_items where quantity !=0 GROUP BY item,date ORDER BY id DESC";
    }
    else
		{
		$sql = "SELECT * FROM prod_items where quantity !=0 GROUP BY item,date ORDER BY id DESC LIMIT 0,100";
    }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
    while($row = mysqli_fetch_assoc($result)) 
    {
                    $item1=$row["item"];
                    $sqlitem="SELECT items from items where id='$item1'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item=$fetchitem['items'];
                    $date=$row["date"];
                   
                   $sql1="SELECT quantity FROM prod_items where item='$item1' AND date='$date' AND shift='Day'";
                   $result1 = mysqli_query($conn, $sql1);
                    if (mysqli_num_rows($result1) > 0) 
                    {
                    $dquantity=0;
                    while($row1 = mysqli_fetch_assoc($result1)) 
                    {
                      if($row1["quantity"] != NULL) {
                      $dquantity=$dquantity+$row1["quantity"];
                      }
                    }} else { $dquantity=0; }
                    
                    $sql2="SELECT quantity FROM prod_items where item='$item1' AND date='$date' AND shift='Night'";
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $nquantity=0;
                    while($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        if($row2["quantity"] != NULL) {
                         $nquantity=$nquantity+$row2["quantity"];
                        }
                    }} else { $nquantity=0; }
                    
                    $tquantity=$dquantity+$nquantity;
                    
        ?>
          <tr>
              <td>PRD<?php echo sprintf("%04d", $row["seriel"]);?></td>
              <td><?php echo $date;?></td>
              <td><?php echo $item;?></td>
              <td><?php echo $dquantity;?></td>
              <td><?php echo $nquantity;?></td>
              <td><?php echo $tquantity;?></td>
              
            <td><a href="<?php echo $baseurl; ?>/detail?date=<?php echo $date;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/production?srl=<?php echo $row["seriel"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>              
              <a href="<?php echo $baseurl; ?>/delete/production?srl=<?php echo $row["seriel"];?>" onclick="return confirm('Are you sure? Delete the whole items of this date? else please edit')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
