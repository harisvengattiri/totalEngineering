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

<!--    <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h2>Lot Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
        </div></div></div></div>-->

   <div id="lot" class="row">
    <div class="col-md-10">
      <div class="box">
        <div class="box-header">
          <h2>Batch Report</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/lot" method="post" onsubmit="target_popup(this)">
            
            <div class="form-group row">

              <label for="staff" class="col-sm-1 form-control-label" >Batch</label>
              <div class="col-sm-3">
               <!--<select name="lot" class="form-control">-->
               <select name="lot" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT batch FROM batches_lots order by batch";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["batch"]; ?>"><?php echo $row["batch"]?></option>
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
    </div>
  </div>       
     
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>