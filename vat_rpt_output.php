<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0); ?>

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
          <h2>Generate VAT Report for Supplier's</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post" onsubmit="target_popup(this)">
               <div class="form-group row">
              <label for="date" align="right" class="col-sm-1 form-control-label">From</label>
              <div class="col-sm-5">
                <input type="text" name="fdate" id="fdate" placeholder="Start Date" required="required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <input type="text" name="tdate" id="tdate" placeholder="End Date"  required="required" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">View</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/vat_rpt_output" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     

     <?php if(isset($_POST['submit'])) 
     {
     $fdate = $_POST['fdate'];
     $tdate = $_POST['tdate'];
     ?>
     
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Date : <?php echo $today=date("d/m/Y");?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php // echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  Date
              </th>
              <th style="text-align: center;">
                   Invoice
              </th>
              <th style="text-align: center;">
                  Supplier
              </th>
              <th style="text-align: center;">
                  TRN
              </th>
              <th style="text-align: center;">
                  Amount
              </th>
              <th style="text-align: center;">
                  VAT
              </th>
              <th style="text-align: center;">
                  Total
              </th>
          </tr>
        </thead>
        <tbody>    
	<?php  
          $sql = "SELECT `shop` as customer, `name` as customername, `tin` as tin, `date` as date, `expenses`.`id` as expenses, `expenses`.`inv` as invoice, `amt` as amount, `vat` as vat, `amount` as total
                   FROM `expenses` INNER JOIN `customers` ON `customers`.`id` = `expenses`.`shop` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY `expenses` ORDER BY `expenses`";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        $tamount = 0;
        $tvat = 0;
        $ttransport = 0;
        $grand = 0;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>        
          <tr>
             <td><?php echo $sl;?></td>
             
             <td style="text-align: right;"><?php
                echo $date = $row["date"];
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $invoice = $row["invoice"];
             ?></td>
             
             <td style="text-align: center;"><?php
                  echo $customer = $row["customername"];
             ?></td>
             
             <td style="text-align: center;"><?php
                echo $trn = $row["tin"];
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $amount = $row["amount"];
                $tamount = $tamount + $amount;
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $vat = $row["vat"];
                $tvat = $tvat + $vat;
             ?></td>
             
             <td style="text-align: right;"><?php
                echo $total = $row["total"];
                $grand = $grand + $total;
             ?></td>
             
          </tr>  
          <?php $sl++; } } ?>
          <tr>
              <td colspan="4"></td>
              <td colspan="1" style="text-align: right;"><b>Total</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tamount);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tvat);?></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $grand);?></b></td>   
          </tr> 
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