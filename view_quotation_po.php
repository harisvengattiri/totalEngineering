<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<?php
$qtn=$_GET["qtn"];
$sql = "SELECT * FROM qtn_test where id=$qtn";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result)) {
        $id=$row["id"];
            $name1=$row["customer"];
               $sqlcust="SELECT name,address,phone,fax from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $addr=$fetchcust['address'];
               
               $phone=$fetchcust['phone'];
               $fax=$fetchcust['fax'];
               
            // $site1=$row["site"];
            //   $sqlsite="SELECT p_name from customer_site where id='$site1'";
            //   $querysite=mysqli_query($conn,$sqlsite);
            //   $fetchsite=mysqli_fetch_array($querysite);
            //   $site=$fetchsite['p_name'];
               
            $rep1=$row["salesrep"];
               $sqlrep="SELECT name from customers where id='$rep1'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
               
            $status=$row["status"];
            
            $project=$row["site"];
            
            $date=$row["date"];
            $ord=$row["order_referance"];
            $lpo=$row["lpo"];
            
            $stotal=$row["subtotal"];
}
}
?>
<?php include "functions/to_words.php"; ?>
<!-- ############ PAGE START-->
<div class="padding">
    <div class="row">
      <div class="col-xs-6">
        <h4 class="text-md">Quotation #<?php echo $qtn;?> </h4>
      </div>
      <div class="col-xs-6 text-right">  
      <a href="javascript:window.open('<?php echo $cdn_url;?>/prints/quotation?qno=<?php echo $qtn;?>&open=<?php echo ucwords($_SESSION['username']);?>','mywindowtitle','width=750,height=600')" class="btn btn-info pull-right hidden-print"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
      </div>
    </div>
    <p> Date: <strong><?php echo $date;?></strong><br>
        Quotation Status: <span class="label success"><?php echo $status;?></span><br>
    </p>
    <div class="row">
      <div class="col-sm-6">
      <div class="box p-a">
          <h6><b>Customer No : </b><?php echo $name1;?></h6>
          <h6><b>Customer : </b><?php echo $cust;?></h6>
          <h6><b>Project : </b><?php echo $project;?></h6>
        </div>
        </div>
      <div class="col-sm-6">
      <div class="box p-a">
          <h6><b>Phone : </b><?php echo $phone;?></h6>
          <h6><b>Fax : </b><?php echo $fax;?></h6>
          <h6><b>Salesman : </b><?php echo $rep;?></h6>
      </div>
        </div>
      <div class="col-sm-12">
      <div class="box p-a">
          <h6><b>Address: </b><?php echo $addr;?></h6>
      </div>
    </div>
    </div>
    <div class="row">
      <div class="col-sm-12 text-right">  
    <a href="<?php echo $baseurl;?>/add/quotation_po_item?qtn=<?php echo $qtn;?>" class="btn btn-sm btn-warning pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add New Item</a>
<br/><br/>
    </div>
    </div>
    <div class="row">
    <div class="table-responsive">
      <table class="table table-striped white b-a">
        <thead>
          <tr>
            <th style="width: 10%;">Item</th>
            <th style="width: 50%;">Description</th>
            <th style="width: 10%;">Qty.</th>
            <th style="width: 10%;">U. Price</th>
            <th style="width: 10%;">Total AED</th>
            <th style="width: 10%;">Actions</th>
          </tr>
        </thead>
        <tbody>
<?php
$sql = "SELECT * FROM qtn_item_test WHERE quotation_id=$qtn ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
$slno=0;
$total=0;
if (mysqli_num_rows($result) > 0) 
{
while($row = mysqli_fetch_assoc($result)) {
$id=$row["id"];
$item=$row["item"];
    $sql_item = "SELECT items FROM items WHERE id='$item'";
    $query_item = mysqli_query($conn,$sql_item);
    $fetch_item = mysqli_fetch_array($query_item);
    $item_name = $fetch_item['items'];
$qnt=$row["quantity"];
$unit=$row["price"];
$total=$row["total"];
$slno=$slno+1;
?>
          <tr>
            <td><?php echo $slno;?></td>
            <td><?php echo $item_name;?></td>
            <td><?php echo $qnt;?></td>
            <td align="right"><?php echo custom_money_format("%!i", $unit);?></td>
            <td align="right"><?php echo custom_money_format("%!i", $total);?></td>
              <td>
              <a href="<?php echo $baseurl;?>/edit/quotation_item_po?id=<?php echo $row["id"];?>&qtn=<?php echo $qtn;?>">
              <button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
              <a href="<?php echo $baseurl;?>/delete/quotation_item_po?id=<?php echo $row["id"];?>&qtn=<?php echo $qtn;?>&amt=<?php echo $total;?>">
              <button class="btn btn-xs btn-icon danger"><i class="fa fa-trash"></i></button></a> 
              </td>
          </tr>
<?php
}
}
?> 
          
          <tr>
            <td colspan="4" class="text-right no-border"><strong>Subtotal</strong></td>
            <td align="right"><strong><?php echo custom_money_format("%!i", $stotal);?></strong></td>
            <td align="left"><strong>AED</strong></td>
          </tr>
         

 
        </tbody>
      </table>
    </div>          
  </div>
  </div>
<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
