<?php include "config.php";?>
<?php include "includes/menu.php";?>

<?php
  if(isset($_POST['submit']))
  {
    $cusomer = $_POST['customer'];   
  }
  else
  {
     $cusomer = $_GET['cst']; 
  }
 ?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Reciepts</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/receipt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/receipt_customer_search?cst=<?php echo $cusomer;?>" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/receipt_customer_search?cst=<?php echo $cusomer;?>&view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
<!--              <th data-toggle="true">
                  Code
              </th>-->    
              <th data-toggle="true">
                 Reciept No
              </th>
	      <th>
                  Customer
              </th>
<!--              <th>
                  Customer Site
              </th>-->
	      <th>
                  Date
              </th>
              <th>
                  Due Date
              </th>
              <th width="13%">
                  Clearance Date
              </th>
              <th data-hide="all">
                  Invoice
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Payment
              </th>
              <th>
                  View
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM reciept WHERE `customer`='$cusomer' AND type='' ORDER BY id DESC";
    }
    else
		{
		$sql = "SELECT * FROM reciept WHERE `customer`='$cusomer' AND type='' ORDER BY id DESC LIMIT 0,100";
    }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $rcp=$row["id"];
              $name1=$row["customer"];
              // $site=$row['site'];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
              //  $sqlsite="SELECT p_name from customer_site where id='$site'";
              //  $querysite=mysqli_query($conn,$sqlsite);
              //  $fetchsite=mysqli_fetch_array($querysite);
              //  $site1=$fetchsite['p_name'];
               
              $pdc=$row['post_dated'];
              $amount = $row["amount"];
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$rcp);?></td>
              <td><?php echo $cust;?></td>
              <!--<td><?php // echo $site1;?></td>-->
              <td><?php echo $row["pdate"];?></td>
              
              <td><?php echo $row["duedate"];?></td>
              <td><?php echo $row["clearance_date"];?></td>

              <td><?php
                $sqlinv="SELECT invoice FROM reciept_invoice WHERE reciept_id='$rcp'";
                $queryinv=mysqli_query($conn,$sqlinv);
                while($fetchinv=mysqli_fetch_array($queryinv))
                {
                    echo $fetchinv['invoice'].'<br>';
                }
              ?></td>
              
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <td><?php echo $row["pmethod"];?></td>
	      
             <?php 
              // session_start();
              $company=$_SESSION["username"];
             ?> 
              <td>
              <!--<a href="<?php // echo $baseurl; ?>/prints/reciept?rcp=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>-->
              <a href="<?php echo $baseurl; ?>/view/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/receipt_full?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'gm' || $_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/receipt?id=<?php echo $row["id"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
