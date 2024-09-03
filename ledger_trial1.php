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
    <div class="col-md-8">
      <div class="box">
        <div class="box-header"><h2>Ledger Trial Balance</h2></div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
                   <form role="form" action="<?php echo $baseurl;?>/ledger_trial1" method="post">
         <div class="form-group row">
             <div class="col-sm-4">
                   <?php $today=date("d/m/Y"); ?>
                <input type="text" name="date1" value="<?php echo $today;?>" id="date" placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              <div class="col-sm-4">
                   <?php $today=date("d/m/Y"); ?>
                <input type="text" name="date2" value="<?php echo $today;?>" id="date" placeholder="End Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Search</button>
             </div> 
         </form>
        </div>
      </div>
    </div>
  </div>     
     

     <?php if(isset($_POST['submit'])) 
     {
         $start_date='01/01/2018';
         $date2=$_POST['date2']; 
         $date_help = str_replace('/','-',$date2);
         $year = date('Y', strtotime($date_help));
         
         $date1='01/01/'.$year; 
         $date1=$_POST['date1'];
     ?>  
     <div class="col-md-8">
      <div class="box">
        <div class="box-header">
             <h5 align=""><b>Period: <?php echo $date1;?> - <?php echo $date2;?></b></h5>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
             <tr>
              <th style="text-align:center;">
                 Particular
              </th>
              <th style="text-align:center;">
                 Debit
              </th>
              <th style="text-align:center;">
                 Credit
              </th>
             </tr>
        </thead>
        <tbody>   
             
          <!--CODING SECTION-->                
<!--OPENINGS SECTION-->
<?php
             $sqlinvbal="SELECT sum(grand) AS openings_grand FROM invoice WHERE (STR_TO_DATE(invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(invoice.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryinv = mysqli_query($conn, $sqlinvbal);
             $resultinv = mysqli_fetch_array($queryinv);
             $openinginv = $resultinv['openings_grand'];
             $sql_opening1 = "SELECT sum(op_bal) AS opening_revenue0 FROM customers WHERE (STR_TO_DATE(op_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(op_date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $query_opening1 = mysqli_query($conn,$sql_opening1);
             $result_opening1 = mysqli_fetch_array($query_opening1);
             $opening_revenue0 = $result_opening1['opening_revenue0'];
             
             $real_opening = $openinginv+$opening_revenue0;
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp = $resultrcp['receipt_grand'];
            
            $sql = "SELECT sum(grand) as total_advance FROM reciept WHERE type='2' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $total_advance1 = $result['total_advance'];
            
            $sql = "SELECT sum(tbl.total) AS in_advance FROM (
                    SELECT reciept.id as id,reciept.grand as grand,sum(reciept_invoice.total) as total FROM reciept
                    INNER JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')) AND (STR_TO_DATE(reciept_invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept_invoice.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')) GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $in_advance1 = $result['in_advance'];
            $adv_bal1 = $total_advance1-$in_advance1;
             
            $sql = "SELECT sum(total) as credit_note1 FROM credit_note WHERE (STR_TO_DATE(credit_note.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(credit_note.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note1 = $result['credit_note1'];
            
            $opening_not_rcp = ($real_opening+$adv_bal1)-($openingrcp+$credit_note1);
            
            $sql = "SELECT sum(amount) as opening_exp FROM expenses WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $opening_exp = $result['opening_exp'];
            
            $sql = "SELECT sum(grand) as opening_vch FROM voucher WHERE (STR_TO_DATE(clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(clearance_date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $opening_vch = $result['opening_vch'];
            
               $sql = "SELECT (sum(petty_vch_op)-sum(petty_exp_op)) as petty_bal_op FROM ("
                  . "(SELECT sum(amount) as petty_vch_op,NULL as petty_exp_op FROM petty_voucher WHERE (STR_TO_DATE(petty_voucher.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(petty_voucher.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')))"
                  . "UNION ALL"
                  . "(SELECT NULL as petty_vch_op,sum(total) as petty_exp_op FROM petty_item WHERE (STR_TO_DATE(petty_item.date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(petty_item.date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')))"
                  . ")result";
               $query = mysqli_query($conn,$sql);
               $result = mysqli_fetch_array($query);
               $petty_bal_op = $result['petty_bal_op'];
               if($petty_bal_op > 0) 
               {
               $actual_opening = $opening_not_rcp+$real_opening_pending+$petty_bal_op;
               $actual_borrow = $opening_exp-$opening_vch;
               } else {
               $actual_opening = $opening_not_rcp+$real_opening_pending;
               $actual_borrow = ($opening_exp-$opening_vch)+$petty_bal_op;
               }
               $revenues[] = $actual_opening;
               $received[] = $actual_borrow;
        ?>
            <tr>
              <td><b>OPENING</b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_borrow);?></b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_opening);?></b></td>
            </tr>
            
<!--OPENINGS SECTION ENDS-->

          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE STR_TO_DATE(op_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue = $result_opening['opening_revenue'];
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = ($invoice_amount+$opening_revenue)-($invoice_vat+$invoice_trp);
            $revenues[] = $invoice_amount+$opening_revenue;
          ?> 
          <?php
            $sql = "SELECT SUM(grand) as total_receipt FROM reciept WHERE STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $receipt_total = $fetch['total_receipt'];
            $received[] = $receipt_total;
          ?>
          <?php
            $sql = "SELECT sum(total) as credit_note FROM credit_note WHERE STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note = $result['credit_note'];
            $received[] = $credit_note;
          ?>

          <?php
            $sql = "SELECT sum(grand) as total_advance FROM reciept WHERE type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $total_advance = $result['total_advance'];
            
            $sql = "SELECT sum(tbl.total) AS in_advance FROM (
                    SELECT reciept.id as id,reciept.grand as grand,sum(reciept_invoice.total) as total FROM reciept
                    INNER JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND STR_TO_DATE(reciept_invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $in_advance = $result['in_advance'];
            $adv_bal = $total_advance-$in_advance;
            $revenues[] = $adv_bal;
          ?>
          <?php
            $receipt_pending = ($actual_opening+$opening_revenue+$invoice_amount+$adv_bal)-($receipt_total+$credit_note);
            $received[] = $receipt_pending;
          ?>
             <tr>
              <td>Revenues</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invo_amt);?></td>
             </tr>
             <tr>
              <td>Invoice Vat</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_vat);?></td>
             </tr>
             <tr>
              <td>Transport Service</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_trp);?></td>
             </tr>
             <tr>
              <td>Receipt</td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_total);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Credit Note</td>
              <td align="right"><?php echo custom_money_format('%!i', $credit_note);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Receipt pending</td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_pending);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Advance Balance</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $adv_bal);?></td>
             </tr>
             
             
             
 <!--TOTAL CALCULATION-->
<!-- row starts here--> 
             <?php$revenue_total = array_sum($revenues);
               $received_total = array_sum($received);
             ?>
             <tr>
              <td><b>TOTAL</b></td>
              <td align="right"><?php echo custom_money_format('%!i', $received_total);?></td>
              <td align="right"><?php echo custom_money_format('%!i', $revenue_total);?></td>
             </tr>
<!-- row ends here-->
             
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