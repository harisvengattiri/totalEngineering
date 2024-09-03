<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
        <?php
//         $cat=$_GET['cat'];
        
//         $sql = "SELECT tag FROM liability_categories where id='$cat'";
//         $result = mysqli_query($conn, $sql);
// 		if (mysqli_num_rows($result) > 0) 
// 		{
// 		while($row = mysqli_fetch_assoc($result)) 
// 		{
// 			$head=$row['tag'];
// 		}
// 		}
// 		else
// 		{
// 		      $head="All Liabilities";
// 		}
		?>
	<span style="float: left;"><h2>Payment Vouchers</h2></span> 
    <span style="float: right;">
         <!--<a href="<?php echo $baseurl; ?>/add/liability" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Liability</a></button>&nbsp;-->
         <a href="<?php echo $baseurl; ?>/add/pv" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Payment Voucher</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/pv" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/pv?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Voucher
              </th>
              <th>
                  Category
              </th>
              <th>
                  Sub Category
              </th>
              <th>
                  Date
              </th>
              <th>
                  Cheque Date
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
// 		if (isset($_GET['cat']))
// 		{
// 		$cat=$_GET['cat'];
// 		$sql = "SELECT * FROM voucher where category='$cat' ORDER BY id DESC";
//      }
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM pv ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM pv ORDER BY id DESC LIMIT 0,100";
        }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>PV|<?php echo $row["year"];?>|<?php echo sprintf("%04d", $row["voucher"]);?></td>
              <td><?php echo $row["voucher"];?></td>
              <td><?php
                $cat = $row['category'];
                $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                echo $cat_name = $result_cat['tag'];
              ?></td>
              <td><?php
                $sub_cat = $row['subcategory'];
                $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub_cat'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                echo $sub_cat_name = $result_sub_cat['category'];
              ?></td>

              
              
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["duedate"];?></td>
              <td style="text-align:right;"><?php echo custom_money_format('%!i', $row["amount"]);?> Dhs</td>
              <td>
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/pv?pv=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <!--<a href="<?php echo $baseurl; ?>/view/pv?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>     -->
              <a href="<?php echo $baseurl; ?>/edit/pv?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <!--<a href="<?php echo $baseurl; ?>/delete/voucher/?lbt=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> -->
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
