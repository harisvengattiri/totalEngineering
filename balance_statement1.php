<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box col-md-6">
    <div class="box-header">
	<span style="float: left;"><h2>Balance Sheet</h2></span>
    </div><br/>
    <div class="box-body">
         
         <form role="form" action="<?php echo $baseurl;?>/balance_statement1" method="post">
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
    <div>
	
         <?php if(isset($_POST['submit']))
         {
         $start_date='01/01/2018';
              
         $date1=$_POST['date1'];
         if($date1 == NULL){$date1 = '01/01/2018';}
         $date2=$_POST['date2'];    
     ?>  
         <h5 align="center"><b>Period: <?php echo $date1;?> - <?php echo $date2;?></b></h5>  
      <table class="table m-b-none">
        <thead>
             <tr>
              <th>
                  Particular
              </th>
              <th style="text-align:center;">
                 Amount
              </th> 
             </tr>
        </thead>
        <tbody>
             
    <!--rows starts from here in table-->
    <tr>
      <td><b>ASSETS</b></td>
    </tr>
           <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_enbd FROM voucher WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_enbd FROM miscellaneous WHERE bank='815' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
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
              elseif (substr($idet, 0, 3) === 'MSC')
              {
              $enbd_cr1=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd = ($enbd_cr+$enbd_cr1)-$enbd_dr;
            $amount_asset1[] = $enbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_cbd FROM voucher WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_cbd FROM miscellaneous WHERE bank='816' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
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
              elseif (substr($idet, 0, 3) === 'MSC')
              {
              $cbd_cr1=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd = ($cbd_cr+$cbd_cr1)-$cbd_dr;
            $amount_asset1[] = $cbd;
          ?>
             <tr>
              <td>BANK - E.N.B.D</td>
              <td align="right"><?php echo number_format($enbd, 2, '.', '');?></td>
             </tr>
             <tr>
              <td>BANK - C.B.D</td>
              <td align="right"><?php echo number_format($cbd, 2, '.', '');?></td>
             </tr>
             
          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE STR_TO_DATE(op_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue = $result_opening['opening_revenue'];

            $sql = "SELECT sum(grand) as op_rcp FROM reciept WHERE type='1' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $opening_receipt = $fetch['op_rcp'];
            
            $pending_opening = $opening_revenue-$opening_receipt;
            // $revenues[] = $opening_revenue;
            // $received[] = $pending_opening;
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = $invoice_amount-($invoice_vat+$invoice_trp);
            // $revenues[] = $invoice_amount;
          ?>   
          <?php
            $sql = "SELECT SUM(grand) as total_receipt,sum(discount) AS receipt_discount FROM reciept WHERE STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $receipt_discount = $result['receipt_discount'];
            $receipt_total = $result['total_receipt'];
            // $received[] = $receipt_discount;
          ?>
          <?php
            $sql = "SELECT sum(total) as credit_note FROM credit_note WHERE STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $credit_note = $result['credit_note'];
            // $received[] = $credit_note;
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
            // $revenues[] = $adv_bal;
          ?>
          <?php
            $receipt_pending = ($opening_revenue+$invoice_amount+$adv_bal)-($receipt_total+$credit_note+$pending_opening);
            $amount_asset1[] = $receipt_pending;
          ?>
             <tr>
              <td>Customers</td>
              <td align="right"><?php echo number_format($receipt_pending, 2, '.', '');?></td>
             </tr>
          <?php  
          $sql_expense = "SELECT sum(vat) as expense_vat,sum(amount) as total_expense FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
          $query_expense = mysqli_query($conn,$sql_expense);
          $result_expense = mysqli_fetch_array($query_expense);
          $expense_vat = $result_expense['expense_vat'];
          $total_expense = $result_expense['total_expense'];
          $amount_asset1[] = $expense_vat;
         ?>  
             <tr>
              <td>Tax Receivables</td>
              <td align="right"><?php echo number_format($expense_vat, 2, '.', '');?></td>
             </tr>
  
           <?php
            $sql = "SELECT sum(amount) AS Uncleared_rcp FROM reciept WHERE status='Uncleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_rcp = $result['Uncleared_rcp'];
            $amount_asset1[] = $Uncleared_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Bounce_rcp FROM reciept WHERE status='Bounce' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Bounce_rcp = $result['Bounce_rcp'];
            $amount_asset1[] = $Bounce_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Hold_rcp FROM reciept WHERE status='Hold' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Hold_rcp = $result['Hold_rcp'];
            $amount_asset1[] = $Hold_rcp;
          ?>
             <tr>
              <td>Bill Receivables</td>
              <td align="right"><?php echo number_format($Uncleared_rcp, 2, '.', '');?></td>
             </tr>
             <tr>
              <td>Bill Receivables - Bounce</td>
              <td align="right"><?php echo number_format($Bounce_rcp, 2, '.', '');?></td>
             </tr>
             <tr>
              <td>Bill Receivables - Hold</td>
              <td align="right"><?php echo number_format($Hold_rcp, 2, '.', '');?></td>
             </tr>
           <?php
            $amount_asset1[] = -($invoice_vat);
           ?>
             <tr>
              <td>Tax Payable</td>
              <td align="right">(<?php echo number_format($invoice_vat, 2, '.', '');?>)</td>
             </tr>

            <tr>
                  <td><b>Total Current Assets</b></td>
                  <?php $amount_asset1_total = number_format(array_sum($amount_asset1), 2, '.', '');?>
                  <td align="right"><b><?php echo $amount_asset1_total;?></b></td>
             </tr>
          
          
          
    <tr>
      <td><b>Property & Equipment</b></td>
    </tr>  
             <!--row starts from here-->
        <?php
        $sql = "SELECT sum(amount) as amount,category FROM assets WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
        $result = mysqli_query($conn,$sql);
        if (mysqli_num_rows($result) > 0)
    	{
        $sl=1;
        while($row = mysqli_fetch_assoc($result))
        {
        $amount = $row['amount'];
        $amount_asset2[] = $row['amount'];
        $cat = $row['category'];
          $sql_cat = "SELECT tag FROM expense_categories WHERE id=$cat";
          $result_cat = mysqli_query($conn,$sql_cat);
          $row_cat = mysqli_fetch_assoc($result_cat);
          $tag = $row_cat['tag'];
        ?>             
             <tr>
              <td><?php echo $tag;?></td>
              <td align="right"><?php echo number_format($amount, 2, '.', '');?></td>
             </tr>
       <?php  $sl++; } } ?>       
    <!--row ends here-->
            <tr>
                  <td><b>Total Property & Equipment</b></td>
                  <?php $amount_asset2_total = number_format(array_sum($amount_asset2), 2, '.', '');?>
                  <td align="right"><b><?php echo $amount_asset2_total;?></b></td>
             </tr>
             <tr>
              <td><hr></td>
            </tr>
             <tr>
                  <td><b>Total Assets</b></td>
                  <?php $amount_asset_total = $amount_asset1_total+$amount_asset2_total;?>
                  <td align="right"><b><?php echo number_format($amount_asset_total, 2, '.', '');?></b></td>
             </tr>
             
             
  <!--LIABILITY SECTION-->           
             
             <tr>
                  <td><b>LIABILITY</b></td>
             </tr>
   
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_vch FROM voucher WHERE status='Uncleared' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_vch = $result['Uncleared_vch'];
            $amount_lbl1[] = $Uncleared_vch;
          ?>
             <tr>
              <td>Bill Payable</td>
              <td align="right"><?php echo number_format($Uncleared_vch, 2, '.', '');?></td>
             </tr>
         <?php
         $sql_voucher = "SELECT sum(grand) as voucher_grand,sum(discount) as voucher_discount FROM voucher WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
         $query_voucher = mysqli_query($conn,$sql_voucher);
         $result_voucher = mysqli_fetch_array($query_voucher);
         $voucher_grand = $result_voucher['voucher_grand'];
         $voucher_discount = $result_voucher['voucher_discount'];
        ?>
        <?php
            $sql = "SELECT sum(tbl.total) as non_voucher FROM (
                    SELECT expenses.inv as inv,sum(expenses.amount) as total FROM expenses 
                    WHERE STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND inv NOT IN (SELECT voucher_invoice.inv FROM voucher_invoice WHERE STR_TO_DATE(voucher_invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY voucher_invoice.inv) GROUP BY expenses.inv
                        ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $non_voucher = $result['non_voucher'];
            $partial_voucher_balance = $total_expense-($voucher_grand+$non_voucher);
            $total_non_voucher = $non_voucher+$partial_voucher_balance;
            $amount_lbl1[] = $total_non_voucher;
        ?> 
             
             <tr>
              <td>Creditors</td>
              <td align="right"><?php echo number_format($total_non_voucher, 2, '.', '');?></td>
             </tr>
             
             <tr>
                  <td><b>Total Current Liabilities</b></td>
                  <?php $amount_lbl1_total = number_format(array_sum($amount_lbl1), 2, '.', '');?>
                  <td align="right"><b><?php echo $amount_lbl1_total;?></b></td>
             </tr> 
    <tr>
       <td><b>Long-Term Liabilities</b></td>
    </tr>         
   <!--row starts from here-->
        <?php
        $sql1 = "SELECT sum(total) as amount,cat FROM liability WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY cat";
        $result1 = mysqli_query($conn,$sql1);
        if (mysqli_num_rows($result1) > 0)
	    {
        while($row1 = mysqli_fetch_assoc($result1))
        {
        $amount = $row1['amount'];
        $amount_lbl2[] = $row1['amount'];
        $cat = $row1['cat'];
          $sql_cat = "SELECT tag FROM expense_categories WHERE id=$cat";
          $result_cat = mysqli_query($conn,$sql_cat);
          $row_cat = mysqli_fetch_assoc($result_cat);
          $tag = $row_cat['tag'];
        ?>             
             <tr>
              <td><?php echo $tag;?></td>
              <td align="right"><?php echo number_format($amount, 2, '.', '');?></td>
             </tr>
       <?php  $sl++; } } ?>       
    <!--row ends here-->
        <tr>
           <td><b>Total Long-Term Liabilities</b></td>
            <?php $amount_lbl2_total = number_format(array_sum($amount_lbl2), 2, '.', '');?>
           <td align="right"><b><?php echo $amount_lbl2_total;?></b></td>
        </tr>
             <tr>
                  <td><hr></td>
             </tr>
             <tr>
                  <td><b>Total Liabilities</b></td>
                  <?php $amount_lbl_total = $amount_lbl1_total+$amount_lbl2_total;?>
                  <td align="right"><b><?php echo $amount_lbl_total;?></b></td>
             </tr>
             <tr>
                  <td><hr></td>
             </tr>
             <tr>
                  <?php
                    $sql_capital = "SELECT sum(amount) as capital FROM capital";
                    $query_capital = mysqli_query($conn,$sql_capital);
                    $row_capital = mysqli_fetch_assoc($query_capital);
                    $capital = $row_capital['capital'];
                    
                  ?>
                  <td><b>Capital Invested</b></td>
                  <td align="right"><b><?php echo number_format($capital, 2, '.', '');?></b></td>
             </tr>
             
             <tr>
                  <?php
                    $net_income = $amount_asset_total-$amount_lbl_total-$capital;
                  ?>
                  <td><b>NET INCOME</b></td>
                  <td align="right"><b><?php echo number_format($net_income, 2, '.', '');?></b></td>
             </tr>
             <tr>
                  <?php
                    $total_capital = $net_income+$capital;
                  ?>
                  <td><b>Total Capital</b></td>
                  <td align="right"><b><?php echo number_format($total_capital, 2, '.', '');?></b></td>
             </tr>
             <tr>
                  <?php
                    $total_lbl_capital = $total_capital+$amount_lbl_total;
                  ?>
                  <td><b>Total Liabilities & Capital</b></td>
                  <td align="right"><b><?php echo number_format($total_lbl_capital, 2, '.', '');?></b></td>
             </tr>
        </tbody>
      </table>
         <?php } ?>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
