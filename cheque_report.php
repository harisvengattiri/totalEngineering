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

    <div id="sitepo" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
             <h2>Cheque Status Report 
                  <!--<span style="color:red;">[From & To Dates are mandatory]</span>-->
             </h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/cheque_status_report" method="post" onsubmit="target_popup(this)"> 
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
            <div class="form-group row">
              <label for="date" align="" class="col-sm-3 form-control-label">Cheque Status</label>
              <div class="col-sm-8">
               <select name="status" class="form-control select2" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                   <option value="">Select Status</option>
                   <option value="Cleared">Cleared</option>
                   <option value="Uncleared">Uncleared</option>
                   <option value="Hold">Hold</option>
                   <option value="Bounce">Bounce</option>
               </select>
              </div>
            </div>
             
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php // echo $cdn_url;?>/reports/rcv2" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
            
</div>



</div>
  
<?php include "includes/footer.php";?>