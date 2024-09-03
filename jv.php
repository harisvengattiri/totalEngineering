<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Journal Voucher</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/jv" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/jv" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/jv?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Journal
              </th>
              
              <!--<th>-->
              <!--    Creditor Account-->
              <!--</th>-->
              <!--<th>-->
              <!--    Debitor Account-->
              <!--</th>-->

              <th>
                  Date
              </th>
              <th>
                  Debit Amount
              </th>
              <th data-hide="all">
                  Debit VAT
              </th>
              <th>
                  Credit Amount
              </th>
              <th data-hide="all">
                  Credit VAT
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
		$sql = "SELECT * FROM jv ORDER BY id DESC";
        }
        else
		{
        // $sql = "SELECT *,sum(total) AS debit_total FROM jv INNER JOIN jv_items ON jv.id = jv_items.jv GROUP BY jv.id ORDER BY jv.id DESC LIMIT 0,100";
        $sql = "SELECT * FROM jv ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            
            $jv = $row['id'];
            
            $sql1 = "SELECT SUM(amount) AS debt_amount,SUM(vat) AS debt_vat FROM `jv_items` WHERE `jv`='$jv' AND `type`='debit'";
            $result1 = mysqli_query($conn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            $debt_amount = $row1["debt_amount"];
                $debt_amount = ($debt_amount != NULL) ? $debt_amount : 0;
            $debt_vat = $row1["debt_vat"];
                $debt_vat = ($debt_vat != NULL) ? $debt_vat : 0;

            $sql2 = "SELECT SUM(amount) AS crdt_amount,SUM(vat) AS crdt_vat FROM `jv_items` WHERE `jv`='$jv' AND `type`='credit'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $crdt_amount = $row2["crdt_amount"];
                $crdt_amount = ($crdt_amount != NULL) ? $crdt_amount : 0;
            $crdt_vat = $row2["crdt_vat"];
                $crdt_vat = ($crdt_vat != NULL) ? $crdt_vat : 0;
                
            $voucher = $row['voucher'];
        ?>
          <tr>
              <td>JV|<?php echo $row["year"];?>|<?php echo sprintf("%06d", $voucher);?></td>
              <td><?php echo $row["date"];?></td>
              
              <td><?php echo custom_money_format('%!i', $debt_amount);?></td>
              <td><?php echo custom_money_format('%!i', $debt_vat);?></td>
              <td><?php echo custom_money_format('%!i', $crdt_amount);?></td>
              <td><?php echo custom_money_format('%!i', $crdt_vat);?></td>
              
              <?php $company = $_SESSION["username"];?>
              <td>
              <a href="<?php echo $baseurl; ?>/edit/jv?jv=<?php echo $jv;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-pencil"></i></button></a>
              <!--<a href="<?php // echo $cdn_url;?>/prints/journal?jv=<?php // echo $row["id"];?>&open=<?php // echo $company;?>" target="_blank"><button class="btn btn-xs btn-icon info"><i class="fa fa-print"></i></button></a>-->
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
