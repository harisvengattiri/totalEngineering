<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <?php
    if(isset($_GET['status'])) {
        $sts = $_GET['status'];
    } else { $sts = ''; }
    ?>
    <div class="col-md-12">
	<?php if($sts == "failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
        <?php } if($sts == "failed1") {?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger"> Unable to update from Quotation</span>
    </a></p>
        <?php } ?>
    </div>     
       
    <div class="box-header">
	<span style="float: left;"><h2>Un Approved Sales Order</h2></span> 
    <span style="float: right;">
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/un_po" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/un_po?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                  Date
              </th>
              <th>
                   Order Ref.
              </th> 
              <th>
                  Customer
              </th>
              <th data-hide="all">
                  Site
              </th>
              <th>
                  Sales Rep
              </th>
        
              <th data-hide="all">
                   LPO No
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
		$sql = "SELECT * FROM sales_order WHERE approve != 1 ORDER BY order_referance DESC LIMIT 0,1000";
        }
        else
		{
		$sql = "SELECT * FROM sales_order WHERE approve != 1 ORDER BY order_referance DESC LIMIT 0,100";
        }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
             $site1=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site1'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site=$fetchsite['p_name'];
               
            $rep1=$row["salesrep"];
               $sqlrep="SELECT name from customers where id='$rep1'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
            $grand_total = $row["grand_total"];
            $grand_total = ($grand_total != NULL) ? $grand_total : 0;
        ?>
          <tr>
              
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["order_referance"];?> </td>
              <td><?php echo $cust;?></td>
              <td><?php echo $site;?></td>
	          <td><?php echo $rep;?></td> 
              <td><?php echo $row["lpo"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $grand_total);?></td>
              
              
              <td>
                  <?php if($_SESSION['role'] == 'admin' || $_SESSION['username'] == 'noushad') { ?>
                  <a href="<?php echo $baseurl; ?>/edit/view_sales_order?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-search"></i></button></a>
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
