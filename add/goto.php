<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php 
  if($_GET['id'])
  {
  $id=$_GET['id'];
   $or=$_GET['or'];
  $status='success';
  }
  ?>
    
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="success") { ?>
	<p><a class="list-group-item b-l-success">
          <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label success pos-rlt m-r-xs">
		  <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-success">Your Submission was Successfull!</span>
    </a></p>
	<?php } else if($status=="failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
	<?php } ?>
      <div class="box">
        <div class="box-header">
          <h2>Your Last Added Delivery Note No is: <?php echo sprintf("%06d",$id);?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
             <h5> Click the below link to show the PDF of Delivery Note.</h5> 
            <div class="form-group row m-t-md">
              <div class="col-sm-offset-2 col-sm-12">
                  <?php
            	    //  session_start();
            	     $company=$_SESSION["username"];
            	  ?>
                <!--<button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
               <a href="<?php echo $cdn_url; ?>/prints/delivery_note?dno=<?php echo $id;?>&open=<?php echo $company;?>" target="_blank"><button class="btn btn-sm btn-outline rounded b-success text-success">Show</button></a>
                
              </div>
            </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "../includes/footer.php";?>