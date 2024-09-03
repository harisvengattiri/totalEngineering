<?php include "config.php";?>
<?php include "includes/menu.php";?>

<script>
function target_popup(form) {
    window.open('', 'formpopup', 'width=1350,height=650,resizeable,scrollbars');
    form.target = 'formpopup';
}
</script>

<script>
$(document).ready(function(){
    $("#goall").click(function(){
        $("#all").toggle();
        $("#company,#item,#itemrep,#complete").hide();
    });
});
$(document).ready(function(){
    $("#goitem").click(function(){
        $("#item").toggle();
        $("#company,#all,#itemrep,#complete").hide();
    });
});
$(document).ready(function(){
    $("#goitemrep").click(function(){
        $("#itemrep").toggle();
        $("#all,#item,#company,#complete").hide();
    });
});
$(document).ready(function(){
    $("#gocomplete").click(function(){
        $("#complete").toggle();
        $("#all,#item,#company,#itemrep").hide();
    });
});
$(document).ready(function(){
    $("#gocompany").click(function(){
        $("#company").toggle();
        $("#all,#item,#complete,#itemrep").hide();
    });
});
</script>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
     
    <div class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Select Your Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
     
     <button id="goall" type="button" class="btn btn-success">Order Report</button>
     <button id="goitem" type="button" class="btn btn-success">Delivery Report</button>
     <button id="goitemrep" type="button" class="btn btn-success">Transportation Report</button>
     <button id="gocomplete" type="button" class="btn btn-success">Item Sales Summary</button>
     <button id="gocompany" type="button" class="btn btn-success">Customer-Item Sales Summary</button>
        </div></div></div></div>
  
     
   <div style="display: none;" id="complete" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Item Sales Summary</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post" onsubmit="target_popup(this)">
           
            <div class="form-group row">

              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-5">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-5">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/convert_report_item_summery" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>  
     
 
  <div style="display: none;" id="company" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Customer - Item Sales Summary Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            <label for="staff" class="col-sm-1 form-control-label" >Customer</label>
              <div class="col-sm-3">
               <select name="cust" id="staff" class="form-control">
				<?php 
				$sql = "SELECT * FROM customers where type='Company'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>   
               
               
               
            <div class="form-group row">

              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-3">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit5" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     
     
     
     
  <div style="display: none;" id="all" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Order Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="salesrep" id="staff" class="form-control">
				<?php 
				$sql = "SELECT * FROM customers where type='Salesrep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>   
               
               
               
            <div class="form-group row">

              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-3">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit1" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

<!--<div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Bank Cash Flow</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/bank_cash_flow" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="date" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-4">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-2 form-control-label">End Date</label>
              <div class="col-sm-4">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>-->
  </div>



     <div style="display: none;" id="item" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Report of Delivery</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Sales Rep</label>
              <div class="col-sm-3">
               <select name="salesrep" id="staff" class="form-control">
				<?php 
				$sql = "SELECT * FROM customers where type='Salesrep'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-3">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
              <div class="col-sm-3">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit2" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  
<!--NEW MADE REPORT--> 
 <div style="display: none;" id="itemrep" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Report of Vehicle</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Vehicle</label>
              <div class="col-sm-3">
               <select name="vehicle" id="staff" class="form-control">
				<?php 
				$sql = "SELECT * FROM vehicle";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["vehicle"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit3" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Report of Driver</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Driver</label>
              <div class="col-sm-3">
               <select name="driver" id="staff" class="form-control">
				<?php 
				$sql = "SELECT * FROM customers where type='Driver'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
				</select>
              </div>
              <label for="date" align="right" class="col-sm-2 form-control-label">Start Date</label>
              <div class="col-sm-2">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>

              <label for="date" align="right" class="col-sm-1 form-control-label">End Date</label>
              <div class="col-sm-2">
                <input type="text" name="tdate" id="tdate" placeholder="End Date" Required="Required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
          }">
              </div>
            </div>
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button name="submit4" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>    
     

     <?php if(isset($_POST['submit1'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     $sr=$_POST['salesrep'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Order Summary</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                 $sql = "SELECT *,SUM(quantity) quantity FROM sales_order INNER JOIN order_item ON sales_order.id = order_item.item_id WHERE"
                         . " STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') "
                         . "AND salesrep='$sr' GROUP BY order_item.item ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
                 
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $quantity=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $name1=$row["customer"];
                    $date=$row["date"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
              <td><?php echo $quantity;?></td>
          </tr>
		<?php
                $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?>  
   
     <?php if(isset($_POST['submit2'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     $sr=$_POST['salesrep'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Summary of Delivery</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
//                 $sql = "SELECT *,SUM(thisquantity) quantity FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
//                         . " STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') "
//                         ."AND batch!='' GROUP BY delivery_item.item ORDER BY STR_TO_DATE(delivery_note.date, '%d/%m/%Y') ASC";
                 
                 $sql = "SELECT *,SUM(thisquantity) quantity FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id INNER JOIN sales_order ON sales_order.order_referance = delivery_note.order_referance WHERE"
                         . " STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') "
                         ."AND sales_order.salesrep='$sr' AND batch!='' GROUP BY delivery_item.item ORDER BY STR_TO_DATE(delivery_note.date, '%d/%m/%Y') ASC";
                 
                 
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $quantity=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $name1=$row["customer"];
                    $date=$row["date"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
              <td><?php echo $quantity;?></td>
          </tr>
		<?php
                $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?>  


     <?php if(isset($_POST['submit3'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     $veh=$_POST['vehicle'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Summary of Vehicle</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                 $sql = "SELECT *,SUM(thisquantity) quantity FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND vehicle='$veh' AND batch!='' GROUP BY delivery_item.item ORDER BY STR_TO_DATE(delivery_note.date,'%d/%m/%Y') ASC";
                 
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $quantity=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $name1=$row["customer"];
                    $date=$row["date"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
              <td><?php echo $quantity;?></td>
          </tr>
		<?php
                $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?>

     <?php if(isset($_POST['submit4'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     $dri=$_POST['driver'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Summary of Driver</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Quantity
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                 $sql = "SELECT *,SUM(thisquantity) quantity FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND driver='$dri' AND batch!='' GROUP BY delivery_item.item ORDER BY STR_TO_DATE(delivery_note.date,'%d/%m/%Y') ASC";
                 
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $quantity=0;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $name1=$row["customer"];
                    $date=$row["date"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               
               $item=$row["item"];
                    $sqlitem="SELECT items from items where id='$item'";
                    $queryitem=mysqli_query($conn,$sqlitem);
                    $fetchitem=mysqli_fetch_array($queryitem);
                    $item1=$fetchitem['items'];
                    
                $quantity=$row["quantity"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
               <!--<td><?php echo $date;?></td>-->
               <!--<td><?php echo $cust;?></td>-->
               <td><?php echo $item1;?></td>
              <td><?php echo $quantity;?></td>
          </tr>
		<?php
                $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?>

     <?php if(isset($_POST['submit5'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     $cust=$_POST['cust'];
     
     $sqlcust="SELECT name from customers where id='$cust'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $customer=$fetchcust['name'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Customer - Item Sales Summary Report [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2>
          <h2 style="text-align:right;">Customer: [<?php echo $customer;?>]</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Order
              </th>
              <th>
                  Sale
              </th>
          </tr>
        </thead>
        <tbody>
		<?php   
                $sql = "SELECT id,items FROM items GROUP BY items ORDER BY items ASC";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    while($row = mysqli_fetch_assoc($result)) {
                    $id=$row['id'];
                    $item=$row["items"];
               
        ?>
          <tr>
              
             <td><?php echo $sl;?></td>
             <td><?php echo $item;?></td>
             <td>
                  <?php
                    $sql1 = "SELECT *,SUM(quantity) ordersum FROM sales_order INNER JOIN order_item ON sales_order.id = order_item.item_id WHERE"
                         ." STR_TO_DATE(sales_order.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND sales_order.customer='$cust' AND order_item.item='$id'";
                    
                    $result1 = mysqli_query($conn, $sql1);
                    if (mysqli_num_rows($result1) > 0) 
                    {
                    $row1 = mysqli_fetch_assoc($result1);
                    echo $order=$row1['ordersum'];
                    if($order<1){ echo '0';}
                    }
                   ?>
             </td>
             <td>
                  <?php
                    $sql2 = "SELECT *,SUM(thisquantity) sale FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_note.customer='$cust' AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $row2 = mysqli_fetch_assoc($result2);
                    echo $sale=$row2['sale'];
                    if($sale<1){ echo '0';}
                    }
                  ?>
             </td>
          </tr>
		<?php
                $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>    
             
           
        </div>
      </div>
    </div>    
    <?php } ?>




     <?php if(isset($_POST['submit'])) { 
     $fdate=$_POST['fdate'];
     $tdate=$_POST['tdate'];
     ?>
     <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Summary Report of Items [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Id
              </th> 
              <th>
                  Item
              </th>
              <th>
                  Sale
              </th>
              <th>
                  Unit Price
              </th>
              <th style="text-align:center;">
                  Total
              </th>
          </tr>
        </thead>
        <tbody>
		<?php   
                $sql = "SELECT id,items FROM items GROUP BY items ORDER BY items ASC";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) 
                    {
                    $sl=1;
                    $grand=0;
                    while($row = mysqli_fetch_assoc($result)) 
                    {
                    $id=$row['id'];
                    $item=$row["items"];
               
        ?>
          <tr>
               <?php
                    $unit=0;
                    $sql2 = "SELECT *,SUM(thisquantity) sale,SUM(amt) total,AVG(price) unit FROM delivery_note INNER JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE"
                         ." STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate','%d/%m/%Y') AND STR_TO_DATE('$tdate','%d/%m/%Y')"
                         ."AND delivery_item.item='$id' AND delivery_item.batch!=''";
                    
                    $result2 = mysqli_query($conn, $sql2);
                    if (mysqli_num_rows($result2) > 0) 
                    {
                    $row2 = mysqli_fetch_assoc($result2);
                    $sale=$row2['sale'];
                    $unit=$row2['unit'];
                    $total=$row2['total'];
                    $grand=$grand+$total;
                    if($sale > 0) 
                    {
                  ?>
               
              
             <td><?php echo $sl;?></td>
             <td><?php echo $item;?></td>
             
             <td>
                  <?php
                    echo $sale;
                  ?>
             </td>
             
             <td>
                  <?php
                    echo custom_money_format("%!i", $unit);
                  ?>
             </td>
             <td align="right">
                  <?php
                    echo custom_money_format("%!i", $total);
                  ?>
             </td>
            <?php $sl=$sl+1; } } ?>
          </tr>
		<?php
		}
		}
		?>
          <tr>
            <td colspan="3"><b></b></td>
            <td colspan="1" align="right"><b>GRAND TOTAL</b></td>
            <td colspan="1" align="right"><b><?php echo custom_money_format("%!i", $grand);?></b></td>
          </tr>
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>    
    <?php } ?> 
     


   
    
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>