<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
$journal = $_GET['id'];
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="success") {?>
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
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>View Journal Voucher</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

        <?php
        $sql = "SELECT * FROM `journal` WHERE id='$journal'";
        $query = mysqli_query($conn,$sql);
        $result = mysqli_fetch_array($query);
        
        $debt_amount = $result['debt_amount'];
        $debt_vat = $result['debt_vat'];
        
        $crdt_amount = $result['crdt_amount'];
        $crdt_vat = $result['crdt_vat'];
        
        $date = $result['date'];
        $inv = $result['inv'];
        $note = $result['note'];
            
            $debt_cat = $result['debt_cat'];
                $sql_pre_cat = "SELECT * FROM `expense_categories` WHERE id='$debt_cat'";
                $query_pre_cat = mysqli_query($conn,$sql_pre_cat);
                $result_pre_cat = mysqli_fetch_array($query_pre_cat);
                $debt_cat_name = $result_pre_cat['tag'];
            $debt_sub = $result['debt_sub'];
                $sql_pre_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$debt_sub'";
                $query_pre_sub_cat = mysqli_query($conn,$sql_pre_sub_cat);
                $result_pre_sub_cat = mysqli_fetch_array($query_pre_sub_cat);
                $debt_sub_name = $result_pre_sub_cat['category'];
                
            $crdt_cat = $result['crdt_cat'];
                $sql_cat = "SELECT * FROM `expense_categories` WHERE id='$crdt_cat'";
                $query_cat = mysqli_query($conn,$sql_cat);
                $result_cat = mysqli_fetch_array($query_cat);
                $crdt_cat_name = $result_cat['tag'];
            $crdt_sub = $result['crdt_sub'];
                $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE id='$crdt_sub'";
                $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                $result_sub_cat = mysqli_fetch_array($query_sub_cat);
                $crdt_sub_name = $result_sub_cat['category'];
        ?>
        
        <form role="form" action="<?php echo $baseurl;?>/journal" method="post">

            <div class="form-group row">
              <label class="col-sm-1 form-control-label">Date</label>
                <div class="col-sm-3">
                    <input type="text" name="date" id="date" value="<?php echo $date;?>" placeholder="Date" required class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options = "{
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
            
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Particulars</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Debit</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Credit</b></label>
            </div>
            <div class="form-group row">
                <label for="category" class="col-sm-1 form-control-label"><b>Dr.Acc</b></label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" value="<?php echo $debt_cat_name;?>">
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control" value="<?php echo $debt_sub_name;?>">
                </div>
                <div class="col-sm-2">
                  <input type="text" class="form-control" value="<?php echo $debt_amount;?>">
                </div>
                <div class="col-sm-1">
                  <input type="text" class="form-control" value="<?php echo $debt_vat;?>">
                 </div>
            </div>
            
            <div class="form-group row">
                    <label for="category" class="col-sm-1 form-control-label"><b>Cr.Acc</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?php echo $crdt_cat_name;?>">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" value="<?php echo $crdt_sub_name;?>">
                    </div>
                    <div class="col-sm-3"></div>
                    <div class="col-sm-2">
                      <input type="text" class="form-control" value="<?php echo $crdt_amount;?>">
                    </div>
                    <div class="col-sm-1">
                      <input type="text" class="form-control" value="<?php echo $crdt_vat;?>">
                    </div>
            </div>
               
            <div class="form-group row">
              <label for="Quantity" class="col-sm-1 form-control-label">Invoice No</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $inv;?>">
              </div>
              <label align="right" for="Quantity" class="col-sm-1 form-control-label">Note</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" value="<?php echo $note;?>">
              </div>
            </div>
            
            <div class="row">
                <label class="col-sm-4 form-control-label" align="center"><b>Total Amount</b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Total Debit: <?php echo $debt_amount+$debt_vat;?></b></label>
                <label class="col-sm-4 form-control-label" align="center"><b>Total Credit: <?php echo $crdt_amount+$crdt_vat;?></b></label>
            </div>
            
              
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">
                <a href="<?php echo $baseurl;?>/journal"><button class="btn btn-sm btn-outline rounded b-danger text-danger">Back</button></a>
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