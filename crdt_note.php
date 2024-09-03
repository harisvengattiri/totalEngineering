<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Credit Notes </h2></span> 
    <span style="float: right;">
        <a href="<?php echo $baseurl; ?>/add/crdt_note_amount" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Direct Amount</a></button>&nbsp;
        <!--<a href="<?php // echo $baseurl; ?>/add/crdt_note" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Adjust Price</a></button>&nbsp;-->
        <!--<a href="<?php // echo $baseurl; ?>/add/crdt_note_qnt" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Return Qnt</a></button>&nbsp;-->
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/crdt_note" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/crdt_note?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
              <th>
                 Credit Note
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
                  Invoice
              </th>
              <th>
                  Amount
              </th>
               <th>
                 Total
              </th>
              
              <th>
                  Print
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql = "SELECT * FROM credit_note ORDER BY id DESC";
    }
  else
		{
		$sql = "SELECT * FROM credit_note ORDER BY id DESC LIMIT 0,100";
    }
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
              $cdt=$row["id"];
              $name1=$row["customer"];
                    $sqlcust="SELECT name from customers where id='$name1'";
                    $querycust=mysqli_query($conn,$sqlcust);
                    $fetchcust=mysqli_fetch_array($querycust);
                    $cust=$fetchcust['name'];
              $amount = $row["amount"];
              $amount = ($amount != NULL) ? $amount : 0;
              $total = $row["total"];
              $total = ($total != NULL) ? $total : 0;
        ?>
          <tr>
              <td>CDT|<?php echo sprintf("%06d",$cdt);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row['date'];?></td>
              <td><?php echo $row['invoice'];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $total);?></td>
              
	      
               <?php 
        	     //  session_start();
        	     $company = $_SESSION["username"];
               ?>
              
              <td>
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/crdt_note?cdt=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <!--<a href="<?php // echo $baseurl; ?>/view/receipt?id=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>-->
              <a href="<?php echo $baseurl; ?>/edit/crdt_note?id=<?php echo $row["id"];?>" title="Edit"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <?php if($_SESSION['role'] == 'admin') { ?>
              <a href="<?php echo $baseurl; ?>/delete/crdt_note?id=<?php echo $row["id"];?>"onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>
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
