<?php include "config.php";?>
<?php include "includes/menu.php";?>
<script>
function target_popup(form) {
    window.open('', 'formpopup', 'width=1350,height=650,resizeable,scrollbars');
    form.target = 'formpopup';
}
</script>
<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
      <div class="box col-md-6">
        <div class="box-header">
    	<span style="float: left;"><h2>Check Receivable Invoice Details</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/inv_details_pay" method="post" onsubmit="target_popup(this)">
             <div class="form-group row">
                  <div class="col-sm-8">
                       <select name="date" class="form-control" required>
                           <option value="">Select</option>
                           <option value="1">Last One Month</option>
                           <option value="2">Last Two Months</option>
                           <option value="3">Last Three Months</option>
                       </select>
                </div>
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
                 </div> 
             </form>
         </div>
        <div>
        </div>
      </div>
  </div>
  <div class="row">
     <div class="box col-md-6">
        <div class="box-header">
    	<span style="float: left;"><h2>Check Receivable Delivery Note Details</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/do_details_pay" method="post" onsubmit="target_popup(this)">
             <div class="form-group row">
                  <div class="col-sm-8">
                       <input type="text" name="date" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
                 </div> 
             </form>
         </div>
        <div>
        </div>
      </div>
  </div>
  
  <div class="row">
      <div class="box col-md-6">
        <div class="box-header">
    	<span style="float: left;"><h2>Customer Aging Report</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/cust_aging" method="post" onsubmit="target_popup(this)">
             <div class="form-group row">
                  <div class="col-sm-8">
                       
                    <select name="customer" id="icustomer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="all">ALL</option>
                    <?php 
                        $sql="SELECT * FROM customers WHERE type='Company'";
        				$result = mysqli_query($conn, $sql);
        				if (mysqli_num_rows($result) > 0) 
        				{
        				while($row = mysqli_fetch_assoc($result)) { ?>
        				<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
        				<?php 
        				}} 
        				?>
                    </select>
                       
                       
                </div>
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
                    <button type="submit" formaction="<?php echo $baseurl;?>/report/cust_aging_view" class="btn btn-sm btn-outline rounded b-info text-info">View</button>
                 </div> 
             </form>
         </div>
        <div>
        </div>
      </div>
  </div>
  
  <div class="row">
     <div class="box col-md-6">
        <div class="box-header">
    	<span style="float: left;"><h2>Check Customers Near Credit Period</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/near_period" method="post" onsubmit="target_popup(this)">
             <div class="form-group row">
                  <div class="col-sm-8">
                      <?php $today = date('d/m/Y'); ?>
                       <input type="text" name="date" id="date" value="<?php echo $today;?>" readonly placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
                    <!--<button type="submit" formaction="<?php echo $baseurl;?>/near_period" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                    <!--<button type="submit" formaction="<?php echo $baseurl;?>/report/near_period_print" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                 </div> 
             </form>
         </div>
        <div>
        </div>
      </div>
  </div>
  
    <div class="row">
      <div class="box col-md-8">
        <div class="box-header">
    	<span style="float: left;"><h2>Check Customers Above Credit Period</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/above_period" method="post" onsubmit="target_popup(this)">
             <div class="form-group row">
                 
                 <div class="col-sm-4">
                    <select name="staff" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="0">ALL</option>
                    <?php 
                        $sql="SELECT * FROM customers WHERE type!='Company'";
        				$result = mysqli_query($conn, $sql);
        				if (mysqli_num_rows($result) > 0) 
        				{
        				while($row = mysqli_fetch_assoc($result)) { ?>
        				<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
        				<?php 
        				}} 
        				?>
                    </select>
                 </div>
                 
                  <div class="col-sm-3">
                       <input type="text" name="fdate" id="date" required placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <div class="col-sm-3">
                      <?php $today = date('d/m/Y'); ?>
                       <input type="text" name="date" id="date" value="<?php echo $today;?>" readonly placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                    <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Generate</button>
                    <!--<button type="submit" formaction="<?php echo $baseurl;?>/report/near_period_print" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                 </div> 
             </form>
         </div>
        <div>
        </div>
      </div>
    </div>
  
  
  
</div>





<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
