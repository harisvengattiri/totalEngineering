<?php include "../config.php";?>
<?php include "../includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
$debit = $_GET['id'];
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-8">
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
          <h2>View Debit Note</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

        <?php
        $sql = "SELECT * FROM `debitnote` WHERE id='$debit'";
        $query = mysqli_query($conn,$sql);
        $result = mysqli_fetch_array($query);
        $amt = $result['amt'];
        $vat = $result['vat'];
        $dbt_amt = $result['dbt_amt'];
        $date = $result['date'];
        $note = $result['note'];
        
        $cat = $result['cat'];
            $sql_cat = "SELECT tag FROM `expense_categories` WHERE id='$cat'";
            $query_cat = mysqli_query($conn,$sql_cat);
            $result_cat = mysqli_fetch_array($query_cat);
            $cat_name = $result_cat['tag'];
        $sub_cat = $result['sub'];
            $sql_sub_cat = "SELECT category FROM `expense_subcategories` WHERE id='$sub_cat'";
            $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
            $result_sub_cat = mysqli_fetch_array($query_sub_cat);
            $sub_cat_name = $result_sub_cat['category'];
        ?>
        <form role="form" action="<?php echo $baseurl;?>/debitnote" method="post">

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Category</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" placeholder="Category" value="<?php echo $cat_name;?>">
              </div>
              <label align="right" class="col-sm-2 form-control-label">Sub Category</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" placeholder="Sub Category" value="<?php echo $sub_cat_name;?>">
              </div>
            </div>
            
            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Amount</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="amt" value="<?php echo $amt;?>">
              </div>
              <label align="right" class="col-sm-2 form-control-label">VAT</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="vat" value="<?php echo $vat;?>">
              </div>
            </div>

            <div class="form-group row">
              <label for="Quantity" class="col-sm-2 form-control-label">Debit Amount</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="dbt_amt" value="<?php echo $dbt_amt;?>">
              </div>
              <label align="right" class="col-sm-2 form-control-label">Date</label>
                <div class="col-sm-4">
                    <input type="text" name="date" value="<?php echo $date;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options = "{
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
              <label for="Quantity" class="col-sm-2 form-control-label">Note</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="note" placeholder="Note" value="<?php echo $note;?>">
              </div>
            </div>
              
            <div class="form-group row m-t-md">
              <div allign="right" class="col-sm-offset-2 col-sm-12">

                <a href="<?php echo $baseurl;?>/debitnote"><button class="btn btn-sm btn-outline rounded b-danger text-danger">Back</button></a>
                <!-- <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Save</button> -->
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