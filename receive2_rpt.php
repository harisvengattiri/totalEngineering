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
             <h2>Receivable II Report 
                  <!--<span style="color:red;">[From & To Dates are mandatory]</span>-->
             </h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="<?php echo $baseurl;?>/report/rcv2_report" method="post" onsubmit="target_popup(this)"> 
              <div class="form-group row">
              <label for="date" align="" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-8">
               <select name="company" class="form-control select2" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT name,id FROM customers where type='Company' order by name";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?> <option value="">CUSTOMER</option> <?php
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["id"]; ?>">[CST-<?php echo $row["id"];?>]<?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
               </select>
              </div>
              </div>
             
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/rcv2" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  
  <?php// if($_SESSION['role'] == 'gm') { ?>
  <!--This is for temporaray check-->
  <!--  <div id="sitepo" class="row">-->
  <!--  <div class="col-md-6">-->
  <!--    <div class="box">-->
  <!--      <div class="box-header">-->
  <!--           <h2>Receivable II Report [PDC Deducted]-->
  <!--           </h2>-->
  <!--        </div>-->
  <!--      <div class="box-divider m-a-0"></div>-->
  <!--      <div class="box-body">-->
  <!--        <form role="form" action="<?php echo $baseurl;?>/report/rcv2_report_final" method="post" onsubmit="target_popup(this)"> -->
  <!--            <div class="form-group row">-->
  <!--            <label for="date" align="" class="col-sm-2 form-control-label">Customer</label>-->
  <!--            <div class="col-sm-8">-->
  <!--             <select name="company" class="form-control select2" Required="Required" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
				 <?php 
				// $sql = "SELECT name,id FROM customers where type='Company' order by name";
 			// 	$result = mysqli_query($conn, $sql);
				// if (mysqli_num_rows($result) > 0) 
				// {
                ?>
                <!--<option value="">CUSTOMER</option>-->
                <?php
				// while($row = mysqli_fetch_assoc($result)) 
				// {
				 ?>
		<!--		<option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]?></option>-->
				 <?php 
				// }} 
				 ?>
  <!--             </select>-->
  <!--            </div>-->
  <!--            </div>-->
             
  <!--          <div class="form-group row m-t-md">-->
  <!--            <div align="right" class="col-sm-offset-2 col-sm-12">-->
  <!--              <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
  <!--              <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>-->
  <!--            </div>-->
  <!--          </div>-->
  <!--        </form>-->
  <!--      </div>-->
  <!--    </div>-->
  <!--  </div>-->
  <!--</div>-->
  <?php// } ?>
  
            
</div>

<!-- ############ PAGE END-->

</div>
  <!-- / -->
  
<?php include "includes/footer.php";?>