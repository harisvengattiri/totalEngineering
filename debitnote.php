<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Debit Notes</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/debitnote_new" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/debitnote" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/debitnote?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Category
              </th>
              <th>
                  Sub Category
              </th>
              <th>
                  Date
              </th>
              <th style="text-align: right;">
                  Amount
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
		$sql = "SELECT * FROM debitnote ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM debitnote ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $shop = $row['shop'];
                $sql_shop = "SELECT * FROM `customers` WHERE id='$shop'";
                $query_shop = mysqli_query($conn,$sql_shop);
                $result_shop = mysqli_fetch_array($query_shop);
                $supplier = $result_shop['name'];
            $dbt_amt = $row["dbt_amt"];
                $dbt_amt = ($dbt_amt != NULL) ? $dbt_amt :0;
                
            $cat = $row['cat'];
                $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $cat_name = $result_cat['tag'];
            $sub_cat = $row['sub'];
                $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub_cat'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
        ?>
          <tr>
              <td>DN|<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $cat_name;?></td>
              <td><?php echo $sub_cat_name;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $dbt_amt);?></td>
              <?php $company = $_SESSION["username"];?>
              <td>
              <a href="<?php echo $baseurl; ?>/view/debitnote?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $cdn_url;?>/prints/debitnote?dn=<?php echo $row["id"];?>&open=<?php echo $company;?>" target="_blank"><button class="btn btn-xs btn-icon info"><i class="fa fa-print"></i></button></a>
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
