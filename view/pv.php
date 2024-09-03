<?php include "../config.php";?>
<?php include "../includes/menu.php";?>

<?php
  $id=$_GET['id'];
  
  $sql = "SELECT * FROM pv where id='$id'";
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
            $cdate=$row['clearance_date'];
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
                    
                    if($sub == NULL) {
                        $sql_calc1 = "SELECT sum(debt_total) AS amt1 FROM `journal` WHERE `debt_cat`='$category'";
                        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category'";
                        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category'";
                        $sql_calc4 = "SELECT sum(crdt_total) AS amt4 FROM `journal` WHERE `crdt_cat`='$category'";
                        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category'";
                    } else {
                        $sql_calc1 = "SELECT sum(debt_total) AS amt1 FROM `journal` WHERE `debt_cat`='$category' AND `debt_sub`='$subcategory'";
                        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category' AND `subcategory`='$subcategory'";
                        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category' AND `sub`='$subcategory'";
                        $sql_calc4 = "SELECT sum(crdt_total) AS amt4 FROM `journal` WHERE `crdt_cat`='$category' AND `crdt_sub`='$subcategory'";
                        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category' AND `id`='$subcategory'";
                    }
                    $query_calc1 = mysqli_query($conn,$sql_calc1);
                    $result_calc1 = mysqli_fetch_array($query_calc1);
                    $amt1 = $result_calc1['amt1'];
                    $amt1 = ($amt1 != NULL) ? $amt1 : 0;
                    $query_calc2 = mysqli_query($conn,$sql_calc2);
                    $result_calc2 = mysqli_fetch_array($query_calc2);
                    $amt2 = $result_calc2['amt2'];
                    $amt2 = ($amt2 != NULL) ? $amt2 : 0;
                    $query_calc3 = mysqli_query($conn,$sql_calc3);
                    $result_calc3 = mysqli_fetch_array($query_calc3);
                    $amt3 = $result_calc3['amt3'];
                    $amt3 = ($amt3 != NULL) ? $amt3 : 0;
                    $query_calc4 = mysqli_query($conn,$sql_calc4);
                    $result_calc4 = mysqli_fetch_array($query_calc4);
                    $amt4 = $result_calc4['amt4'];
                    $amt4 = ($amt4 != NULL) ? $amt4 : 0;
                    $query_calc5 = mysqli_query($conn,$sql_calc5);
                    $result_calc5 = mysqli_fetch_array($query_calc5);
                    $amt5 = $result_calc5['amt5'];
                    $amt5 = ($amt5 != NULL) ? $amt5 : 0;
                    
                    $bal_amt = ($amt1+$amt4+$amt5) - ($amt2+$amt3);
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
 
          <form role="form" action="<?php echo $baseurl;?>/pv" method="post">
               
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
                <label for="type" align="" class="col-sm-2 form-control-label">Voucher No</label>
              <div class="col-sm-4">
                   <input type="text" class="form-control" name="voucher" value="<?php echo $voucher;?>" readonly>
              </div>
              <label for="type" align="right" class="col-sm-2 form-control-label">Balance Amount</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" value="<?php echo $bal_amt;?>" readonly>
              </div>
            </div>
               
            <div class="form-group row">
               <label for="type" class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                  <input type="text" class="form-control" name="tamount" value="<?php echo $amount;?>" placeholder="Amount">
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
                
              <label for="type" align="" class="col-sm-2 form-control-label">Clearance Date</label>
              <div class="col-sm-4">
                 <input type="text" name="cdate" value="<?php echo $cdate;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <label for="type" align="" class="col-sm-2 form-control-label">Cheque No:</label>
              <div class="col-sm-4">
              <input type="text" class="form-control" name="checkno" value="<?php echo $checkno;?>">    
              </div>      
                  
              <label for="type" align="right" class="col-sm-2 form-control-label">Bank</label>
              <div class="col-sm-4">
                <select class="form-control" name="inward">
                <?php $sql = "SELECT * FROM `expense_subcategories` WHERE `id`='$inward'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{ 
                    $row = mysqli_fetch_assoc($result);
                ?><option><?php echo $row["category"];?></option><?php
                }
                ?>             
                </select>
              </div>
              </div>
               
              
            <div class="form-group row m-t-md">
              <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="submit" class="btn btn-sm btn-outline rounded b-danger text-danger">Back To List</button>
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
<?php include "../includes/footer.php";?>