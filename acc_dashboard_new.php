<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php
if(isset($_POST['submit'])) {
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];
    $loading_type = 'Selected';
    $list_count = 100;
    $mode = 'Search Mode';
        $show_date = "[$fdate - $tdate]";
} else {
    $loading_type = 'Recent';
    $list_count = 10;
    $mode = 'Recent View';
        $show_date = "";
}
?>

<script>
$(document).ready(function(){
    $("#invoice").show();
    $("#goInvoice").click(function(){
        $("#invoice").toggle();$("#receipt,#creditNote,#debitNote,#journal,#pVoucher").hide();
    });
    $("#goReceipt").click(function(){
        $("#receipt").toggle();$("#invoice,#creditNote,#debitNote,#journal,#pVoucher").hide();
    });
    $("#goCreditnote").click(function(){
        $("#creditNote").toggle();$("#invoice,#receipt,#debitNote,#journal,#pVoucher").hide();
    });
    $("#goDebitnote").click(function(){
        $("#debitNote").toggle();$("#invoice,#receipt,#creditNote,#journal,#pVoucher").hide();
    });
    $("#goJournal").click(function(){
        $("#journal").toggle();$("#invoice,#receipt,#creditNote,#debitNote,#pVoucher").hide();
    });
    $("#gopVoucher").click(function(){
        $("#pVoucher").toggle();$("#invoice,#receipt,#creditNote,#debitNote,#journal").hide();
    });
});
</script>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
    
    <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Overview of Accounts</h2>
          </div>
        <div class="box-divider m-a-0"></div>
     <div class="box-body">
         
        <form role="form" action="<?php echo $baseurl;?>/acc_dashboard_new" method="POST">
            <div class="form-group row">
                <label for="Quantity" class="col-sm-1 form-control-label">From</label>
                <div class="col-sm-3">
                <input type="text" name="fdate" id="date" placeholder="From Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
                    format: 'DD/MM/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
                  }" required>
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-3">
                <input type="text" name="tdate" id="date" placeholder="To Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
                    format: 'DD/MM/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
                  }" required>
              </div>
              
              <div class="col-sm-2">
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
              </div>
            </div>
        </form>
        
        <h4 style="padding: 15px 0;color: green;"><span style="font-weight:600;">Mode:</span> <?php echo $mode.$show_date;?></h4>
         
     <style>
         .tab{width:150px;height:30px;padding:5px;}
     </style>
     <button id="goInvoice" type="button" class="btn btn-primary tab">Invoice</button>
     <button id="goReceipt" type="button" class="btn btn-primary tab">Receipt</button>
     <button id="goCreditnote" type="button" class="btn btn-primary tab">Credit Note</button>
     
     <button id="goJournal" type="button" class="btn btn-primary tab">Journal</button>
     <button id="gopVoucher" type="button" class="btn btn-primary tab">Payment Voucher</button>
     <button id="goDebitnote" type="button" class="btn btn-primary tab">Debit Note</button>
     </div></div></div></div> 
    

  <div class="row" style="display: none;" id="invoice">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Invoices</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Customer
              </th>
	          <th>
                  Date
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
		if($loading_type == 'Selected') {
		    $sql = "SELECT * FROM invoice WHERE invoice.date != '' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql = "SELECT * FROM invoice WHERE invoice.date != '' ORDER BY id DESC LIMIT 50";
		}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $cust = $row["customer"];
                $sql_cust = "SELECT `name` FROM `customers` WHERE `id`='$cust'";
                $query_cust = mysqli_query($conn,$sql_cust);
                $result_cust = mysqli_fetch_array($query_cust);
                $customer = $result_cust['name'];
            $amount = $row["grand"];
        ?>
          <tr>
              <td>INV|<?php echo sprintf("%06d",$id);?></td>
              <td><?php echo $customer;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <?php $company = $_SESSION['username'];?>
              <td>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"><?php echo $prints;?></i></button></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_print?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-print"><?php echo $prints1;?></i></button></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_tax_split?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-outline-success btn-xs btn-icon "><i class="fa fa-folder-open"><?php echo $prints2;?></i></button></a>
                  <a target="_blank" href="<?php echo $cdn_url;?>/prints/invoice_tax_split_print?inv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-outline-info btn-xs btn-icon"><i class="fa fa-print"><?php echo $prints3;?></i></button></a>
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
  </div>
  
  
  <div class="row" style="display: none;" id="receipt">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Receipts</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Customer
              </th>
	          <th>
                  Date
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
		if($loading_type == 'Selected') {
		    $sql ="SELECT * FROM reciept WHERE status= 'Cleared' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql ="SELECT * FROM reciept WHERE status= 'Cleared' ORDER BY id DESC LIMIT 50";
		}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $cust = $row["customer"];
                $sql_cust = "SELECT `name` FROM `customers` WHERE `id`='$cust'";
                $query_cust = mysqli_query($conn,$sql_cust);
                $result_cust = mysqli_fetch_array($query_cust);
                $customer = $result_cust['name'];
            $amount = $row["grand"];
        ?>
          <tr>
              <td>RPT|<?php echo sprintf("%06d",$id);?></td>
              <td><?php echo $customer;?></td>
              <td><?php echo $row["clearance_date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              
              <td>
                <a href="<?php echo $cdn_url; ?>/prints/receipt?rv=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
                <a href="<?php echo $baseurl; ?>/view/receipt?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>
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
  </div>
  
  
  <div class="row" style="display: none;" id="creditNote">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Credit Notes</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Customer
              </th>
	          <th>
                  Date
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
		if($loading_type == 'Selected') {
		    $sql ="SELECT * FROM credit_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql ="SELECT * FROM credit_note ORDER BY id DESC LIMIT 50";
		}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $cust = $row["customer"];
                $sql_cust = "SELECT `name` FROM `customers` WHERE `id`='$cust'";
                $query_cust = mysqli_query($conn,$sql_cust);
                $result_cust = mysqli_fetch_array($query_cust);
                $customer = $result_cust['name'];
            $amount = $row["total"];
        ?>
          <tr>
              <td>CNT|<?php echo sprintf("%06d",$id);?></td>
              <td><?php echo $customer;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <?php $company = $_SESSION['username'];?>
              <td>
              <a target="_blank" href="<?php echo $cdn_url;?>/prints/crdt_note?cdt=<?php echo $row["id"];?>&open=<?php echo $company;?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>
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
  </div>
  
  <div class="row" style="display: none;" id="debitNote">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Debit Notes</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Category
              </th>
              <th>
                  Sub Category
              </th>
	          <th>
                  Date
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
		if($loading_type == 'Selected') {
		    $sql ="SELECT * FROM debitnote WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql ="SELECT * FROM debitnote ORDER BY id DESC LIMIT 50";
		}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $amount = $row["dbt_amt"];
            $amount = ($amount != NULL) ? $amount : 0;
            
            $cat = $row['cat'];
                $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $cat_name = $result_cat['tag'];
            $sub_cat = $row['sub'];
                $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub_cat'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
        ?>
          <tr>
              <td>DN|<?php echo sprintf("%06d",$id);?></td>
              <td><?php echo $cat_name;?></td>
              <td><?php echo $sub_cat_name;?></td>
              <td><?php echo $row["date"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              <?php $company = $_SESSION['username'];?>
              <td>
              <a href="<?php echo $baseurl; ?>/view/debitnote?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-search-plus"></i></button></a>
              <a href="<?php echo $cdn_url;?>/prints/debitnote?dn=<?php echo $row["id"];?>&open=<?php echo $company;?>" target="_blank"><button class="btn btn-xs btn-icon info"><i class="fa fa-print"></i></button></a>
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
  </div>
  
  <div class="row" style="display: none;" id="journal">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Journals</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Debit Amount
              </th>
              <th>
                  Debit VAT
              </th>
              <th>
                  Credit Amount
              </th>
              <th>
                  Credit VAT
              </th>
              <th>
                  Actions
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
		if($loading_type == 'Selected') {
		    $sql ="SELECT * FROM jv WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql ="SELECT * FROM jv ORDER BY id DESC LIMIT 50";
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
  </div>
  
  <div class="row" style="display: none;" id="pVoucher">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>Latest Payment Vouchers</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Category
              </th>
              <th>
                  Sub Category
              </th>
	          <th>
                  Date
              </th>
              <th>
                  Cheque Date
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
		if($loading_type == 'Selected') {
		    $sql ="SELECT * FROM pv WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY id DESC LIMIT 1000";
		} else {
		    $sql ="SELECT * FROM pv ORDER BY id DESC LIMIT 50";
		}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $cat = $row['category'];
                $sql_crdt_cat = "SELECT * FROM `expense_categories` WHERE id='$cat'";
                $query_crdt_cat = mysqli_query($conn,$sql_crdt_cat);
                $result_crdt_cat = mysqli_fetch_array($query_crdt_cat);
                $cat_name = $result_crdt_cat['tag'];
            $sub_cat = $row['subcategory'];
                $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub_cat'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
            $amount = $row["grand"];
        ?>
          <tr>
              <td>PV|<?php echo $row['year'];?>|<?php echo sprintf("%06d",$row["voucher"]);?></td>
              <td><?php echo $cat_name;?></td>
              <td><?php echo $sub_cat_name;?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["duedate"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
              
              <td>
                <a target="_blank" href="<?php echo $cdn_url;?>/prints/pv?pv=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon success"><i class="fa fa-folder-open"></i></button></a>    
                <!--<a href="<?php echo $baseurl;?>/view/pv?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-search-plus"></i></button></a>-->
                <a href="<?php echo $baseurl; ?>/edit/pv?id=<?php echo $row["id"];?>"><button class="btn btn-xs btn-icon info"><i class="fa fa-pencil"></i></button></a> 
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
  </div>
  
  
  
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
<?php include "includes/footer.php";?>