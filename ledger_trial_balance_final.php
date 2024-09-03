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
                   <form role="form" action="<?php echo $baseurl;?>/ledger_trial_balance_final" method="post">
         <div class="form-group row">
              <div class="col-sm-4">
                <input type="text" name="date1" id="date" placeholder="Start Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
              
         $date1=$_POST['date1'];
         if($date1 == NULL){$date1=$start_date;}
         $date2=$_POST['date2'];
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
<!-- row starts here--> 
          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue,sum(rcp) AS opening_receipt FROM customers";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue = $result_opening['opening_revenue'];
            $opening_receipt = $result_opening['opening_receipt'];
            $pending_opening = $opening_revenue-$opening_receipt;
            $revenues[] = $opening_revenue;
            $received[] = $pending_opening;
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = $invoice_amount-($invoice_vat+$invoice_trp);
            $revenues[] = $invoice_amount;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='815' AND status='Cleared')
                    UNION ALL (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_enbd FROM voucher WHERE inward='815' AND status='Cleared')
                    )results";
            $query = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'RPT')
              {
              $enbd_cr=$row["Cleared_rcp_enbd"];
              }
              elseif (substr($idet, 0, 3) === 'PVR')
              {
              $enbd_dr=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd = $enbd_cr-$enbd_dr;
            $received[] = $enbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='816' AND status='Cleared')
                    UNION ALL (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_cbd FROM voucher WHERE inward='816' AND status='Cleared')
                    )results";
            $query = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'RPT')
              {
              $cbd_cr=$row["Cleared_rcp_cbd"];
              }
              elseif (substr($idet, 0, 3) === 'PVR')
              {
              $cbd_dr=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd = $cbd_cr-$cbd_dr;
            $received[] = $cbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_credit FROM reciept WHERE inward='1291' AND status='Cleared')
                    UNION ALL (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_credit FROM voucher WHERE inward='1291' AND status='Cleared')
                    )results";
            $query = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'RPT')
              {
              $credit_cr=$row["Cleared_rcp_credit"];
              }
              elseif (substr($idet, 0, 3) === 'PVR')
              {
              $credit_dr=$row["Cleared_rcp_credit"];
              }
            }
            $credit_bank = $credit_cr-$credit_dr;
            $received[] = $credit_bank;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_vch FROM voucher WHERE status='Uncleared'";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_vch = $result['Uncleared_vch'];
            $revenues[] = $Uncleared_vch;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_rcp FROM reciept WHERE status='Uncleared'";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_rcp = $result['Uncleared_rcp'];
            $received[] = $Uncleared_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Bounce_rcp FROM reciept WHERE status='Bounce'";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Bounce_rcp = $result['Bounce_rcp'];
            $received[] = $Bounce_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Hold_rcp FROM reciept WHERE status='Hold'";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Hold_rcp = $result['Hold_rcp'];
            $received[] = $Hold_rcp;
          ?>
          <?php
            $sql = "SELECT sum(discount) AS receipt_discount FROM reciept";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $receipt_discount = $result['receipt_discount'];
            $received[] = $receipt_discount;
          ?>
          <?php
            $sql = "SELECT sum(total) as credit_note FROM credit_note";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note = $result['credit_note'];
            $received[] = $credit_note;
          ?>
          <?php
            $sql = "SELECT sum(tbl.grand) as Receipted_invoice_sum,sum(tbl.total) as Receipted_invoice_amount,sum(tbl.adjust) as Receipted_invoice_adjust FROM (
                    SELECT invoice.id, invoice.grand as grand, sum(reciept_invoice.total) as total,sum(reciept_invoice.adjust) as adjust
                    FROM reciept_invoice JOIN invoice
                    WHERE invoice.id=reciept_invoice.invoice
                    GROUP BY reciept_invoice.invoice
                    ORDER BY invoice.id
                        ) as tbl
                    ";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $rcp_inv = $result['Receipted_invoice_sum'];
            $rcp = $result['Receipted_invoice_amount'];
            $rcp_adjust = $result['Receipted_invoice_adjust'];
         ?>
         <?php
            $sql = "SELECT sum(invoice.grand) as non_rcp FROM invoice 
                    WHERE id NOT IN (SELECT reciept_invoice.invoice FROM reciept_invoice GROUP BY reciept_invoice.invoice)";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $non_rcp = $result['non_rcp'];
            $bal_partial = $rcp_inv-$rcp-$credit_note;
            $receipt_pending = $non_rcp+$bal_partial;
            $received[] = $receipt_pending;
          ?>
          <?php
            
            $sql = "SELECT sum(grand) as total_advance FROM reciept WHERE type='2'";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $total_advance = $result['total_advance'];
            
            $sql = "SELECT sum(tbl.total) AS in_advance FROM (
                    SELECT reciept.id as id,reciept.grand as grand,sum(reciept_invoice.total) as total FROM reciept
                    INNER JOIN
                    reciept_invoice ON reciept.id = reciept_invoice.reciept_id WHERE reciept.type='2' GROUP BY reciept_invoice.reciept_id
                    ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $in_advance = $result['in_advance'];
            $adv_bal = $total_advance-$in_advance;
            $revenues[] = $adv_bal;
          ?>
          <?php  
          $sql_expense = "SELECT sum(vat) as expense_vat,sum(amount) as total_expense FROM expenses";
          $query_expense = mysqli_query($conn,$sql_expense);
          $result_expense = mysqli_fetch_array($query_expense);
          $expense_vat = $result_expense['expense_vat'];
          $total_expense = $result_expense['total_expense'];
          $received[] = $expense_vat;
         ?>
         <?php
         $sql_voucher = "SELECT sum(grand) as voucher_grand,sum(discount) as voucher_discount FROM voucher";
         $query_voucher = mysqli_query($conn,$sql_voucher);
         $result_voucher = mysqli_fetch_array($query_voucher);
         $voucher_grand = $result_voucher['voucher_grand'];
         $voucher_discount = $result_voucher['voucher_discount'];
         $revenues[] = $voucher_discount;
        ?>
        <?php
            $sql = "SELECT sum(tbl.total) as non_voucher FROM (
                    SELECT expenses.inv as inv,sum(expenses.amount) as total FROM expenses 
                    WHERE inv NOT IN (SELECT voucher_invoice.inv FROM voucher_invoice  GROUP BY voucher_invoice.inv) GROUP BY expenses.inv
                        ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $non_voucher = $result['non_voucher'];
            $partial_voucher_balance = $total_expense-($voucher_grand+$non_voucher);
            $total_non_voucher = $non_voucher+$partial_voucher_balance;
            $revenues[] = $total_non_voucher;
        ?>
<!-- row ends here-->
             <tr>
              <td>Revenues</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invo_amt);?></td>
             </tr>
             <tr>
              <td>Transport Service</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_trp);?></td>
             </tr>
             <tr>
              <td>Discount on Revenue</td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_discount);?></td>
              <td align="right"></td>
             </tr>     
<!--EXPENSE SECTION IS BELOW-->       
                
<!-- row starts here-->
        <?php
        $sql1 = "SELECT sum(amt) as expense_amount,category,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id "
                . "WHERE expense_categories.type = 1 GROUP BY category";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_amount = $row1['expense_amount'];
         $received[] = $expense_amount;
         $tag = $row1['tag'];
         ?>    
             <tr>
              <td>C.O.R. <?php echo $tag;?></td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_amount);?></td>
              <td align="right"></td>
             </tr>
        <?php } } ?>
             
        <?php
        $sql1 = "SELECT sum(amt) as expense_amount,category,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id"
                . " WHERE expense_categories.type = 2 GROUP BY category";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_amount = $row1['expense_amount'];
         $received[] = $expense_amount;
         $tag = $row1['tag'];
         ?>    
             <tr>
              <td><?php echo $tag;?></td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_amount);?></td>
              <td align="right"></td>
             </tr>
       <?php } } ?>
             
             <tr>
              <td>Opening</td>
              <td align="right"><?php echo custom_money_format('%!i', $pending_opening);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Tax Receivables</td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_vat);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Advance</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $adv_bal);?></td>
             </tr>
             
             <tr>
              <td>BANK - E.N.B.D</td>
              <td align="right"><?php echo custom_money_format('%!i', $enbd);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>BANK - C.B.D</td>
              <td align="right"><?php echo custom_money_format('%!i', $cbd);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>BANK - CREDIT</td>
              <td align="right"><?php echo custom_money_format('%!i', $credit_bank);?></td>
              <td align="right"></td>
             </tr>
           
             <tr>
              <td>Bill Receivabales</td>
              <td align="right"><?php echo custom_money_format('%!i', $Uncleared_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Bill Receivables - Bounce</td>
              <td align="right"><?php echo custom_money_format('%!i', $Bounce_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td>Bill Receivables - Hold</td>
              <td align="right"><?php echo custom_money_format('%!i', $Hold_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
             <td>Credit Note</td>
             <td align="right"><?php echo custom_money_format('%!i', $credit_note);?></td>
             <td align="right"></td>
             </tr>
             <tr>
             <td>Customers</td>
             <td align="right"><?php echo custom_money_format('%!i', $receipt_pending);?></td>
             <td align="right"></td>
             </tr>
             
             <tr>
              <td>Bills Payable</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $Uncleared_vch);?></td>
             </tr>
             <tr>
              <td>Creditors</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $total_non_voucher);?></td>
             </tr>
             <tr>
              <td>Discount on Expense</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $voucher_discount);?></td>
             </tr>
             
             <tr>
              <td>Opening Revenue</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $opening_revenue);?></td>
             </tr>
             <tr>
              <td>Tax Payable</td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_vat);?></td>
             </tr>
<!-- row ends here-->


 <!--TOTAL CALCULATION-->
<!-- row starts here--> 
             <?php
               $revenue_total = array_sum($revenues);
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