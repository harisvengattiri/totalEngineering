<?php include "config.php";?>
<?php include "includes/menu.php";?>

<style>
     #im{padding-left: 5px;padding-right: 5px;}
</style>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">   
	<span style="float: left;"><h2>Delivery Note</h2></span> 
    <span style="float: right;">
         <a href="<?php echo $baseurl; ?>/add/new_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
         <a href="<?php echo $baseurl; ?>/add/old_delivery_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add Old</a></button>&nbsp;
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
<!--              <th data-toggle="true">
                  Code
              </th>-->
<!--              <th data-toggle="true">
                  Date
              </th>
              <th data-hide="all">
                  Start Date
              </th>
              <th data-hide="all">
                  End Date
              </th>
              <th data-hide="all">
                  Customer
              </th>
              <th data-hide="all">
                  Tags
              </th>-->
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
                  Driver
              </th>
<!--              <th>
                  Vehicle
              </th>-->
              <th>
                  Item
              </th>
              <th>
                  Price
              </th>
              <th>
                  Batch
              </th>
              <th>
                  COC
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
		$sql = "SELECT * FROM delivery_note ORDER BY id DESC";
                }
                else
		{
		$sql = "SELECT * FROM delivery_note ORDER BY id DESC LIMIT 0,100";
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
              $id=$row["id"];
              
              $sql1="SELECT * FROM delivery_item where delivery_id='$id' AND thisquantity!='' AND batch!=''";
              $result1 = mysqli_query($conn, $sql1);
              $item1='';
              $price='';
              $batch='';
              $coc='';
              while($row1 = mysqli_fetch_assoc($result1)) 
              {
                  $item=$row1["item"];
                              $sqlitem="SELECT description from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1[]=$fetchitem['description'];
                  $price[]=$row1["price"]; 
                  $batch[]=$row1["batch"]; 
                  $coc[]=$row1["coc"]; 
              }
              
             
        ?>
          <tr>
              <td width="3%" id="im"><?php echo $row["date"];?></td>
              <td width="8%" id="im">DN<?php echo sprintf("%06d",$id);?></td>
              <td width="8%" id="im">PO <?php echo $row["order_referance"];?></td>
              <?php
              $cust1 = substr($cust, 0, 21);
              $custsite1 = substr($custsite, 0, 21);
              $lpo1 = substr($lpo, 0, 11);
              ?>
              <td width="14%" id="im"><?php echo $cust1?></td>
              <td width="14%" id="im"><?php echo $custsite1;?></td>
              <td width="7%" id="im"><?php echo $lpo1;?></td>
              <td width="7%" id="im"><?php echo $dri;?></td>
              <!--<td width="7%" id="im"><?php echo $veh;?></td>-->
	      <td width="7%" id="im"><?php
                   foreach ( $item1 as $item1 ) 
                     {
                        echo $item1.'<br/>';
                     }
              ?></td>
              <td width="4%" id="im"><?php
                   foreach ( $price as $price ) 
                     {
                        echo $price.'<br/>';
                     }
              ?></td>
              <td width="7%" id="im"><?php
                   foreach ( $batch as $batch ) 
                     {
                        echo $batch.'<br/>';
                     }
              ?></td>
              <td width="10%" id="im"><?php
                   foreach ( $coc as $coc ) 
                     {
                        echo $coc.'<br/>';
                     }
              ?></td>
	      
	     <?php 
	     session_start();
	     $company=$_SESSION["username"];
	     ?> 
              
              <td width="11%"><a href="<?php echo $baseurl; ?>/prints/delivery_note?dno=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"><?php echo $row["prints"];?></i></button></a>
              <a href="<?php echo $baseurl; ?>/edit/delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <a href="<?php echo $baseurl; ?>/delete/new_delivery_note?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-times"></i></button></a>
              <!--<a href="<?php echo $baseurl; ?>/delete/delivery_note?mnt=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
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
