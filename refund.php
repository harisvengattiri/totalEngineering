<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Refunds</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/refund" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/refund" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/refund?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                 Refund Id
              </th>
	        <th>
                  Customer
              </th>
	          <th>
                  Date
              </th>
              <th data-hide="all">
                  Bank
              </th>
              <th>
                  Receipt
              </th>
              <th>
                  Payment
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
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM refund ORDER BY id DESC";
        }
        else
		{
		$sql = "SELECT * FROM refund ORDER BY id DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $rfd=$row["id"];
            $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
            $bank = $row["bank"];
               $sqlbank="SELECT name from customers where id='$bank'";
               $querybank=mysqli_query($conn,$sqlbank);
               $fetchbank=mysqli_fetch_array($querybank);
               $bankname=$fetchbank['name'];
            $amount = $row["amount"];
            $amount = ($amount != NULL) ? $amount : 0;
            
        ?>
          <tr>
              <td>RFD|<?php echo sprintf("%04d",$rfd);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $bankname;?></td>
              <td>RPT|<?php echo sprintf("%06d",$row["rcp"]);?></td>
              <td><?php echo $row["pmethod"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <td>
                <a href="<?php echo $baseurl; ?>/edit/refund?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
                <a href="<?php echo $baseurl; ?>/delete/refund?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
