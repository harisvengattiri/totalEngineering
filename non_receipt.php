<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Pending Invoices</h2></span> 
    <span style="float: right;">
         <!--<a href="<?php echo $baseurl; ?>/add/invoice" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;-->
         <a href="<?php echo $baseurl; ?>/add/new_invoice" ><button class="btn btn-outline btn-sm rounded b-primary text-primary"><i class="fa fa-plus"></i> Add New</a></button>&nbsp;
<?php 
if (isset($_GET['view'])) 
{ 
?>
<a href="<?php echo $baseurl; ?>/non_receipt" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View Recent</a></button>
<?php 
}
else 
{ 
?>
<a href="<?php echo $baseurl; ?>/non_receipt?view=all" ><button class="btn btn-outline btn-sm rounded b-success text-success"><i class="fa fa-list"></i> View All</a></button>
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
                 Invoice No
              </th>
              <th>
                 Date
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Total
              </th>
              <th>
                  Paid
              </th>
              <th>
                  Balance
              </th>
<!--              <th data-hide="all">
                  Notes
              </th>-->
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if (isset($_GET['view']))
		{
		$sql="SELECT id,date,customer,ROUND(`total`,2) as total,ROUND(sum(`received`),2) as received FROM 
                         (SELECT id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, 0 as received FROM invoice WHERE id NOT IN (SELECT reciept_invoice.invoice FROM reciept_invoice GROUP BY reciept_invoice.invoice) 
                         UNION ALL
                         SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(reciept_invoice.total) as received
                         FROM invoice
                         LEFT JOIN reciept_invoice ON invoice.id = reciept_invoice.invoice GROUP BY reciept_invoice.invoice  
                         UNION ALL
                         SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(credit_note.total) as received
                         FROM invoice
                         LEFT JOIN credit_note ON invoice.id = credit_note.invoice GROUP BY credit_note.invoice)                  
                         results WHERE total > received GROUP BY id order by id";
                }
                else
		{
		$sql="SELECT id,date,customer,ROUND(`total`,2) as total,ROUND(sum(`received`),2) as received FROM 
                         (SELECT id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, 0 as received FROM invoice WHERE id NOT IN (SELECT reciept_invoice.invoice FROM reciept_invoice GROUP BY reciept_invoice.invoice) 
                         UNION ALL
                         SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(reciept_invoice.total) as received
                         FROM invoice
                         LEFT JOIN reciept_invoice ON invoice.id = reciept_invoice.invoice GROUP BY reciept_invoice.invoice  
                         UNION ALL
                         SELECT invoice.id as id, invoice.customer as customer, invoice.date as date, invoice.grand as total, sum(credit_note.total) as received
                         FROM invoice
                         LEFT JOIN credit_note ON invoice.id = credit_note.invoice GROUP BY credit_note.invoice)                  
                         results WHERE total > received GROUP BY id order by id
                         LIMIT 0,100";
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
               
               
             $balance=$row["total"]-$row["received"];
             if($balance > 0.01) {
        ?>
          <tr>
              <td>INV <?php echo sprintf("%05d",$row["id"]);?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $cust;?></td>
              <!--td align="right"><?php echo custom_money_format("%!i",$row["total"]);?></td>
              <td align="right"><?php echo custom_money_format("%!i",$row["received"]);?></td>-->
              <td style="text-align: right;"><?php echo $row["total"];?></td>
              <td style="text-align: right;"><?php echo $row["received"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format("%!i",$balance);?></td>
	      
	            <?php 
        	     session_start();
        	     $company=$_SESSION["username"];
        	    ?>
              
              <td><a href="<?php echo $baseurl; ?>/prints/invoice?inv=<?php echo $row["id"];?>&open=<?phpecho $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
              <!--<a href="<?php echo $baseurl; ?>/edit/invoice?id=<?php echo $row["id"];?>&or=<?php echo $row["order_referance"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a>
              <a href="<?php echo $baseurl; ?>/delete/invoice?id=<?php echo $row["id"];?>&dn=<?php echo $row["dn"];?>" onclick="return confirm('Are you sure?')"><button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a>-->
              </td>
          </tr>
		<?php
        }
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
