<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php $getdate=$_GET['prj']; ?>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Production during the date <?php echo $getdate;?></h2></span> 
    
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <!--<span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>-->
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
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM prod_items where date='$getdate' GROUP BY item ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM prod_items where date='$getdate' GROUP BY item ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) 
                {
                    $item=$row["item"];
                    $item1 = mysqli_real_escape_string($conn, $item);
                   $sql1="SELECT quantity FROM prod_items where item='$item1' AND date='$getdate' AND shift='Day'";
                   $result1 = mysqli_query($conn, $sql1);
                    if (mysqli_num_rows($result1) > 0) 
                    {
                    $dquantity=0;
                    while($row1 = mysqli_fetch_assoc($result1)) 
                    {
                    $dquantity=$dquantity+$row1["quantity"];
                    }} else { $dquantity=0; }
                    
                    $sql2="SELECT quantity FROM prod_items where item='$item1' AND date='$getdate' AND shift='Night'";
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                         $nquantity=0;
                    while($row2 = mysqli_fetch_assoc($result2)) 
                    {
                         $nquantity=$nquantity+$row2["quantity"];
                    }} else { $nquantity=0; }
                    
                    $tquantity=$dquantity+$nquantity;
                    
        ?>
          <tr>
              <!--<td>PRD<?php echo sprintf("%04d", $row["id"]);?></td>-->
              <td><?php echo $item;?></td>
              <td><?php echo $dquantity;?></td>
              <td><?php echo $nquantity;?></td>
              <td><?php echo $tquantity;?></td>
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