<?php include "config.php";?>
<?php include "includes/menu.php";?>

<?php 
if (isset($_GET['cat'])) 
{
  $cat = $_GET['cat'];
  if(!empty($cat))
              {
              $cat_sql = "WHERE cat ='".$cat."' ";
              }
}
?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">


    <div class="box-header">
	<span style="float: left;"><h2>Manage Accounts</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/cmp_account" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;

<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/cmp_accounts" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/cmp_accounts?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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




              <th data-toggle="true">
                  #
              </th>
              <th>
                 Particulars
              </th>
              <th>
                  Category
              </th>
              <th>
                 Date
              </th>
              <th>
                 Value
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
		$sql = "SELECT * FROM cmp_account ".$cat_sql." ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM cmp_account ".$cat_sql." ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <!--<td>PRD <?php // echo sprintf("%04d", $row["id"]);?></td>-->
              
              <td><?php echo $row["id"];?></td>
              <td><?php echo $row["particulars"];?></td>
              <td><?php
               $cat=$row["cat"];
               $sqlrep="SELECT tag from account_categories where id='$cat'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               echo $tag=$fetchrep['tag'];
              ?>
              </td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?></td>
             
              <!--<td><a href="<?php echo $baseurl; ?>/production_cash_flow?prj=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <td>
             <!--<a href="<?php echo $baseurl; ?>/edit/cmp_account?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
             <a href="<?php echo $baseurl; ?>/delete/cmp_account?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
