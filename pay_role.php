<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Payroll</h2></span> 
    <span style="float: right;"><a href="<?php echo $baseurl; ?>/add/pay_role" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/pay_role" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/pay_role?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Staff Id
              </th>
              <th>
                  Date
              </th>
              <th>
                  Employee
              </th>
              <th>
                  Total Amount
              </th>
              <th>
                  Balance
              </th>
              <!--<th>-->
              <!--    Actions-->
              <!--</th>-->
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT name,id FROM customers WHERE type='SalesRep' ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT name,id FROM customers WHERE type='SalesRep' ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
               $sqlcust="SELECT sum(total) AS total from pay_role where employee='$id'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $total=$fetchcust['total'];
               $total = ($total != NULL) ? $total : 0;
               
               $sqladv="SELECT sum(amount) AS adv from pay_role_item where employee='$id' AND component='2'";
               $queryadv=mysqli_query($conn,$sqladv);
               $fetchadv=mysqli_fetch_array($queryadv);
               $adv=$fetchadv['adv'];
               
               $sqlpetty="SELECT sum(total) AS total from petty_cash where staff='$id'";
               $querypetty=mysqli_query($conn,$sqlpetty);
               $fetchpetty=mysqli_fetch_array($querypetty);
               $petty=$fetchpetty['total'];
               
               $balance = $adv - $petty;
        ?>
          <tr>
              <td>STF<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo date('d/m/Y');?></td>
              <td><?php echo $row["name"];?></td>
              <td><?php echo number_format($total, 2, '.', '');?></td>
              <td><?php echo number_format($balance, 2, '.', '');?></td>
              <!--<td><a href="<?php echo $baseurl; ?>/production_cash_flow?prj=<?php echo $id; ?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <!--<td>-->
              <!--<a href="<?php echo $baseurl; ?>/edit/customer_site?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
              <!--<a href="<?php echo $baseurl; ?>/delete/pay_role?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
              <!--</td>-->
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
