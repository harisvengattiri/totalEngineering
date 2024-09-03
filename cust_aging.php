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
      <div class="box col-md-10">
        <div class="box-header">
    	<span style="float: left;"><h2>Customer Aging Report</h2></span>
        </div><br/>
        <div class="box-body">
             
             <form role="form" action="<?php echo $baseurl;?>/report/aging_report" method="post" onsubmit="target_popup(this)">
             
             <div class="form-group row">
                <div class="col-sm-4">
                       
                    <select name="staff" id="icustomer" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value="all">ALL Salesman</option>
                    <?php 
                        $sql="SELECT * FROM customers WHERE type='SalesRep'";
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
                
                      <div class="col-sm-4">
                        <select type="text" class="form-control" name="cust_type" id="cust_type">
                            <option value="">ALL Customer</option>
                            <option value="Cash">Cash Customer</option>
                            <option value="Credit">Credit Customer</option>
                        </select>
                      </div>
                    <label for="email" align="right" class="col-sm-1 form-control-label">Period</label>
                      <div class="col-sm-2">
                        <select type="text" class="form-control" name="period" id="period"></select>
                      </div>
                </div>
                
            <div class="form-group row">   
                <label for="date" align="" class="col-sm-2 form-control-label">Select Month</label>
                <div class="col-sm-4">
                    <div class="form-group"><div class="input-group date" data-ui-jp="datetimepicker" data-ui-options="{
                    viewMode: 'years',
                    format: '01-MM-YYYY',
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
                  }"><input type="text" name="month" Required class="form-control"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span></div></div>
                  </div>
                  
                <label for="date" align="right" class="col-sm-2 form-control-label">Select Duration</label>
                <div class="col-sm-2">
                    <select class="form-control" name="duration" required>
                        <option value="">Select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
                  
                      
                
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
                    <!--<button type="submit" formaction="<?php echo $baseurl;?>/report/cust_aging_view" class="btn btn-sm btn-outline rounded b-info text-info">View</button>-->
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
<script type="text/javascript">
 $(document).ready(function() {
  $("#cust_type").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"../add/get_period_all",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#period").html(resp);
        }
      });
    } else {
      $("#period").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script> 
<?php include "includes/footer.php";?>
