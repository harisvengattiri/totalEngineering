<?php include "config.php";?>
<?php include "includes/menu.php";?>

<style>
     #im{padding-left: 5px;padding-right: 5px;}
</style>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
       
    <div class="col-md-12">
	<?php if($_GET['status'] == "failed") {?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed! Since you had already made an invoice using this delivery note. So please delete that first.</span>
    </a></p>
        <?php } ?>
    </div>     
       
    <div class="box-header">   
	<span style="float: left;"><h2>Delivery Note</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/new_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
         <!--<a href="<?php echo $baseurl; ?>/add/old_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Old</a></button>&nbsp;-->
         <?php 
if (isset($_GET['view']))
{ 
?>
<a href="<?php echo $baseurl; ?>/delivery_note" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/delivery_note?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
      <table width=100%"  class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
               <th>
                  Date
              </th>
              <th>
                 DNo
              </th>
              <th>
                 P.O
              </th>
	      <th>
                  Customer
              </th>
              <th>
                 Site
              </th>
              <th>
                  LPO
              </th>
	      <th>
                  Vehicle
              </th>
              <th>
                  Driver
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
		$sql = "SELECT * FROM delivery_note WHERE date='' ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM delivery_note WHERE date='' ORDER BY id DESC LIMIT 0,100";
                }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
             $customer=$row["customer"];
                    $sqlcust="SELECT name from customers where id='$customer'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
                    
              $customersite=$row["customersite"];
                    $sqlsite="SELECT p_name from customer_site where id='$customersite'";
                    $querysite=mysqli_query($conn,$sqlsite);
                    $fetchsite=mysqli_fetch_array($querysite);
                    $custsite=$fetchsite['p_name'];
              $vehicle=$row["vehicle"];
                    $sqlveh="SELECT vehicle from vehicle where id='$vehicle'";
                    $queryveh=mysqli_query($conn,$sqlveh);
                    $fetchveh=mysqli_fetch_array($queryveh);
                    $veh=$fetchveh['vehicle'];
              $driver=$row["driver"];
                    $sqldri="SELECT name from customers where id='$driver'";
                    $querydri=mysqli_query($conn,$sqldri);
                    $fetchdri=mysqli_fetch_array($querydri);
                    $dri=$fetchdri['name'];
              $lpo=$row["lpo"];
             
        ?>
          <tr>
              <td width="3%" id="im"><?php echo $row["date"];?></td>
              <td width="8%" id="im">DN<?php echo sprintf("%06d",$row["id"]);?></td>
              <td width="8%" id="im">PO <?php echo $row["order_referance"];?></td>
              <?php
//              $cust1 = substr($cust, 0, 21);
//              $custsite1 = substr($custsite, 0, 21);
//              $lpo1 = substr($lpo, 0, 11);
              ?>
              <td width="14%" id="im"><?php echo $cust?></td>
              <td width="14%" id="im"><?php echo $custsite;?></td>
              <td width="7%" id="im"><?php echo $lpo;?></td>
              <td width="10%" id="im"><?php echo $veh;?></td>
              <td width="10%" id="im"><?php echo $dri;?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $row["total"]);?></td>
              
	      
	     <?php 
	     session_start();
	     $company=$_SESSION["username"];
	     ?> 
              
              <td width="11%"><a href="<?php echo $baseurl; ?>/prints/delivery_note_deleted?dno=<?php echo $row["id"];?>&open=<?php echo $company;?>" title="Print"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"><?php echo $row["prints"];?></i></button></a>
              <!--<a href="<?php echo $baseurl; ?>/edit/delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" title="Edit"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>-->
              
              <?php if($_SESSION['role'] == 'gm' || $_SESSION['role'] == 'admin') { ?>
              <!--<a href="<?php echo $baseurl; ?>/delete/new_delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" title="Cancel" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-times"></i></button></a>-->
              <!--<a href="<?php echo $baseurl; ?>/delete/delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" title="Delete" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
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
