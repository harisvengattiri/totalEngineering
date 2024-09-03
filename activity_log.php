<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
              
         <form role="form" action="<?php echo $baseurl;?>/activity_log" method="post">
         <div class="form-group row">
              <div class="col-sm-2">
                <input type="text" name="date1" id="date" placeholder="From Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div class="col-sm-2">
                   <?php $today=date("d/m/Y"); ?>
                <input type="text" name="date2" value="<?php echo $today;?>" required id="date" placeholder="To Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <select name="user" id="item" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                  <?php 
				$sql = "SELECT username FROM users";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
				<option value="<?php echo $row["username"]; ?>"><?php echo $row["username"]?></option>
				<?php 
				}} 
				?>
                </select> 
              </div>
                <!--<button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
             </div> 
         </form>  
     
        <?php if(isset($_POST['submit']))
         {
         $user = $_POST['user'];
         $date1=$_POST['date1'];
         $date2=$_POST['date2'];
        ?>
     
     <div class="row">
        
          <div class="p-a">
                    <h6 class="text-muted m-a-0">Activity Log of : <?php echo $user;?></h6>
	  </div>
          
          
                   <?php
                   $sql = "select * from activity_log WHERE user='$user' AND STR_TO_DATE(`time`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') ORDER BY STR_TO_DATE(`time`, '%d/%m/%Y') DESC";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
                   ?>
                   <div class="col-md-3">
                   <div class="streamline streamline-theme m-b" style="margin-bottom: -5px;">
                   <?php
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
                   ?>
                   </div>
	      </div>     
                   <?php
                   }}
		   ?>
        </div>
     
     
         <?php }?>
     
   
               <div class="row">
     
                <div class="p-a">
                    <h6 class="text-muted m-a-0">Activity Log</h6>
	        </div>
                
                
	        <div class="col-md-3">
	        <div class="streamline streamline-theme m-b">
                   <?php
                   $sql = "select * from activity_log ORDER BY `id` DESC LIMIT 0,50";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }}}
		   ?>
	        </div>
	        </div>
	        <div class="col-md-3">
	        <div class="streamline streamline-theme m-b">
                   <?php
                   $sql = "select * from activity_log ORDER BY `id` DESC LIMIT 50,50";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }}}
		   ?>
	        </div>
	        </div>
	        <div class="col-md-3">
	        <div class="streamline streamline-theme m-b">
                   <?php
                   $sql = "select * from activity_log ORDER BY `id` DESC LIMIT 100,50";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }}}
		   ?>
	        </div>
	        </div>
	        <div class="col-md-3">
	        <div class="streamline streamline-theme m-b">
                   <?php
                   $sql = "select * from activity_log ORDER BY `id` DESC LIMIT 150,50";
 
	           $result = mysqli_query($conn, $sql);
		   if (mysqli_num_rows($result) > 0) 
		   {
		   while($row = mysqli_fetch_assoc($result)) 
		   {
		   if ($row["process"]=='delete') 
		   {
		   ?>
	           <div class="sl-item b-danger">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was deleted by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='edit') 
		   {
		   ?>
	           <div class="sl-item b-info">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was edited by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='add') 
		   {
		   ?>
	           <div class="sl-item b-success">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> was added by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   elseif ($row["process"]=='status') 
		   {
		   ?>
	           <div class="sl-item b-warning">
	             <div class="sl-content">
	               <!--<div><?php echo strtoupper(substr($row["code"],0,3)).sprintf("%04d", substr($row["code"],3));?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>-->
	               <div><?php echo strtoupper($row["code"])?> status changed by <a class="text-info"><?php echo ucfirst($row["user"]);?></a>.</div>
                       <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }
		   else
		   {
		   ?>
	           <div class="sl-item b-warn">
	             <div class="sl-content">
	               <div><a class="text-info">Something has been done by <?php echo ucfirst($row["user"]);?></a>.</div>
	               <div class="sl-date text-muted"><?php echo strtoupper($row["time"]);?></div>
	             </div>
	           </div>
		   <?php
		   }}}
		   ?>
	        </div>
	        </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
