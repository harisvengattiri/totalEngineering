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
     
   <div style="" id="batch" class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Report of Delivery Notes to be Invoiced</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post">
            <div class="form-group row">
             <label align="" class="col-sm-2 form-control-label">Customer</label>    
             <div class="col-sm-8">
               <!--<select name="lot" class="form-control">-->
               <select name="customer" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
				<?php 
				$sql = "SELECT id,name FROM customers where type='company'";
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
                 
                 
<!--              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
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
              </div>-->

<!--              <label for="date" align="right" class="col-sm-1 form-control-label">To</label>
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
              </div>-->
            </div>
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <!--<button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>-->
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>  

     <?php if(isset($_POST['submit'])) 
     {$customer=$_POST['customer'];
          $sqlcust="SELECT name from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
     
     ?>
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Delivery Notes to be Invoiced of: <?php echo $cust;?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Customer Site
              </th>
              <th>
                  Sales Order
              </th>
              <th>
                  Delivery Note
              </th>
              
              
          </tr>
        </thead>
        <tbody>
             
	<?php
	$sql = "SELECT * FROM delivery_note where customer='$customer' AND invoiced=''";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
        ?>  
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row["date"];?></td>
             <?php
             $site=$row['customersite']; 
             $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
             ?>
             <td><?php echo $site1;?></td>
             <td><?php echo $row['order_referance'];?></td>
             <td><?php echo sprintf('%06d',$row['id']);?></td>
             
<!--             <td><?php // echo $quantity;?></td>
             <td><?php // echo $delquan;?></td>-->
<!--             <td><?php echo $lotquan;?></td>-->
          </tr>
		<?php
                $sl++;  
		}
		}
		?>
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