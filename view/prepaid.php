<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<?php $id = $_GET['id'];?>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Prepaid Expense View</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/prepaid" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
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
                  Prepaid
              </th>
              <th>
                  Date
              </th>
              <th>
                  Particular
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
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM prepaid_expense WHERE prepaid='$id' ORDER BY id";
                }
                else
		{
		$sql = "SELECT * FROM prepaid_expense WHERE prepaid='$id' ORDER BY id LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>PRE<?php echo sprintf("%04d", $id);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["particulars"];?></td>
              <td><?php echo $row["amt"];?></td>
              <td><?php echo $row["vat"];?></td>
              <td><?php echo $row["amount"];?></td>
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

<?php include "../includes/footer.php";?>
