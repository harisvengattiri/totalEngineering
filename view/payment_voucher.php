<?php include "../config.php";?>
<?php include "../includes/menu.php";?>

<?php
  $id=$_GET['id'];
  
  $sql = "SELECT * FROM payment_voucher where id='$id'";
        $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$name=$row['name'];
            $amount=$row['amount'];
            $date=$row['date'];
            $voucher=$row['voucher'];
            $pmethod=$row['pmethod'];
            $duedate=$row['duedate'];
            $checkno=$row['checkno'];
            $inward=$row['inward'];
            
            $cat = $row['category'];
                $sql_cat = "SELECT * FROM `expense_categories` WHERE id='$cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $cat_name = $result_cat['tag'];
            $sub = $row['subcategory'];
                $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$sub'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $sub_cat_name = $result_sub_cat['category'];
		}
		}
?>
<div class="app-body">

<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h2>View Payment Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
 
          <form role="form" action="" method="post">
                 
            <div class="form-group row">
              <label for="name" class="col-sm-2 form-control-label">Supplier</label>
              <div class="col-sm-6">
                <select name="customer" id="customer" class="form-control">
                  <?php 
                              $customer=$_POST['customer'];
                              
                              $sqlcust="SELECT name from customers where id='$name'";
                              $querycust=mysqli_query($conn,$sqlcust);
                              $fetchcust=mysqli_fetch_array($querycust);
                              $cust=$fetchcust['name'];
				?>
				<option><?php echo $cust;?></option>	
                </select>
              </div>
            </div>
               
               
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" value="<?php echo $amount;?>" placeholder="Amount">
              </div>
               
              <label for="type" align="right" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="voucher" value="<?php echo $voucher;?>" readonly>
              </div> 
            </div>
            
            <div class="form-group row">
                <label for="category" class="col-sm-2 form-control-label">Category</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $cat_name;?>">
                </div>
                <label for="name" align="right" class="col-sm-2 form-control-label">Sub Category</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" value="<?php echo $sub_cat_name;?>">
                </div>
            </div>
                 
            <div class="form-group row">
              <label for="type" align="" class="col-sm-2 form-control-label">Payment Method</label>
              <div class="col-sm-4">
                  <select name="pmethod" class="form-control select2-multiple" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                    <option value=""><?php echo $pmethod;?></option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="card">Card</option>
                </select>
              </div>
                 
               <label for="type" align="right" class="col-sm-2 form-control-label">Payment Date</label>
              <div class="col-sm-4">
                  <input type="text" required name="pdate" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                
              <label for="type" align="" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="checkno" value="<?php echo $checkno;?>">    
              </div>
                
              <label for="type" align="right" class="col-sm-2 form-control-label">Cheque Date</label>
               
              <div class="col-sm-4">
                   <input type="text" name="duedate" value="<?php echo $duedate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                   <select class="form-control" name="inward">
                   <?php     $sql = "SELECT * FROM customers WHERE id=$inward";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{ 
                                $row = mysqli_fetch_assoc($result);
                                ?><option><?php echo $row["name"];?></option><?php
                                }
                   ?>             
                   </select>
              </div>
              </div>
   
          <?php
             $sql = "SELECT * FROM payment_voucher_invoice where payment='$id'";
             $result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) 
		{
		while($row = mysqli_fetch_assoc($result)) 
		{
			$inv=$row['inv'];
                        $amount=$row['amt'];
                        $disc=$row['discount']; 
          ?>      
               
               
            <div class="form-group row">
              <label for="name" class="col-sm-1 form-control-label">Invoice</label>
              <div class="col-sm-2">
                <select name="invoice[]" class="form-control select2" id="inv0" placeholder="Invoice" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">
                <option><?php echo $inv;?></option>	
                </select>
              </div>
<!--              <div class="col-sm-3">
                   <input type="number" class="form-control" name="balance[]" id="bal0" placeholder="Balance" readonly>
              </div>-->
              <div class="col-sm-3">
                   <input type="number" step=".01" min="1" class="form-control" name="amount[]" id="amt0" value="<?php echo $amount;?>">
              </div>
              <div class="col-sm-3">
                   <input type="number" step=".01" min="1" class="form-control" name="discount[]" id="amt1" value="<?php echo $disc;?>">
              </div>
            </div>
           
            <?php }} ?>    
               
              
<!--            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <button name="submit"type="submit" id="sub" class="btn btn-sm btn-outline rounded b-success text-success">Save</button>
              </div>
            </div>-->

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
<?php include "../includes/footer.php";?>