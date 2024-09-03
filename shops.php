<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Shops</h2></span> 
    
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="10">
        <thead>
          <tr>
              <th data-toggle="true">
                  Code
              </th>
              <th>
                  Shop
              </th>
              <th>
                  Contact Person
              </th>
              <th>
                  Phone
              </th>
              <th>
                  Total
              </th>
	      <th data-hide="phone,tablet">
                  Paid
              </th>
	      <th data-hide="phone,tablet">
                  Balance
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
	<?php
	$sql = "SELECT * FROM customers where type='shop'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td>CID<?php echo sprintf("%04d", $row["id"]);?></td>
	      <td><?php echo $row["name"];?></td>
	      <td><?php echo $row["person"];?></td>
	      <td><?php echo $row["phone"];?></td>
              <td align="right"><?php
                                $reciever=$row["id"];
                                $subsql2 = "select sum(amount) as sum from (SELECT amount FROM purchases where shop=$reciever union all SELECT amount FROM office_expenses where shop=$reciever union all SELECT amount FROM vehicle_expenses where shop=$reciever) as t";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo custom_money_format("%!i",$subrow2["sum"])." Dhs"; 
                                $total=$subrow2["sum"];
				}} 
              ?></td>
              
              <td align="right"><?php
                                $reciever=$row["id"];
                                $subsql2 = "select sum(amount) as sum from (SELECT amount FROM purchases where shop=$reciever AND status='debit' union all SELECT amount FROM office_expenses where shop=$reciever union all SELECT amount FROM vehicle_expenses where shop=$reciever union all SELECT amount FROM credit_settlements where toid=$reciever) as t";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo custom_money_format("%!i",$subrow2["sum"])." Dhs"; 
                                $paid=$subrow2["sum"];
				}} 
              ?></td>
              <td><?php echo custom_money_format("%!i",$total-$paid)." Dhs";?></td>
              <td>
              <a href="<?php echo $baseurl; ?>/shop_cash_flow?cid=<?php echo $row["id"];?>" class="btn btn-sm btn-outline rounded b-danger">Cash Flow</a></a> 
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
