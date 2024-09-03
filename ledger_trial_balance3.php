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
                   <form role="form" action="<?php echo $baseurl;?>/ledger_trial_balance3" method="post">
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
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand1 FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp1 = $resultrcp['receipt_grand1'];
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand2 FROM reciept WHERE status='Cleared' AND (STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp2 = $resultrcp['receipt_grand2'];
             $cleared_balance = $openingrcp1-$openingrcp2; 
             
             $sqlrcpbal = "SELECT sum(grand) as receipt_grand3 FROM reciept WHERE status!='Cleared' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp = mysqli_query($conn, $sqlrcpbal);
             $resultrcp = mysqli_fetch_array($queryrcp);
             $openingrcp3 = $resultrcp['receipt_grand3'];
             $sqlrcpbal1 = "SELECT sum(grand) as receipt_grand4 FROM reciept WHERE status='Bounce' AND (STR_TO_DATE(reciept.pdate,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(reciept.pdate,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
             $queryrcp1 = mysqli_query($conn, $sqlrcpbal1);
             $resultrcp1 = mysqli_fetch_array($queryrcp1);
             $openingrcp4 = $resultrcp1['receipt_grand4'];
             $real_opening_pending1 = $openingrcp3-$openingrcp4;
             $real_opening_pending = $real_opening_pending1+$cleared_balance;
            
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
            
            $total_receipted_opening = $openingrcp1+$openingrcp3;
            $opening_not_rcp = ($real_opening+$adv_bal1)-($total_receipted_opening+$credit_note1);
            
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
            
<!--OPENINGS SECTION ENDS-->
             <tr>
              <td><b>OPENING</b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_borrow);?></b></td>
              <td align="right"><b><?php echo custom_money_format('%!i', $actual_opening);?></b></td>
             </tr>
<!-- row starts here--> 
          <?php
            $sql_opening = "SELECT sum(op_bal) AS opening_revenue FROM customers WHERE STR_TO_DATE(op_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_opening = mysqli_query($conn,$sql_opening);
            $result_opening = mysqli_fetch_array($query_opening);
            $opening_revenue1 = $result_opening['opening_revenue'];

            $sql = "SELECT sum(grand) as op_rcp FROM reciept WHERE type='1' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $fetch = mysqli_fetch_array($query);
            $opening_receipt1 = $fetch['op_rcp'];
            
            $pending_opening1 = $opening_revenue1-$opening_receipt1;
          ?>
          <?php
            $sql_invoice = "SELECT sum(grand) AS grand,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['grand'];
            $invoice_vat = $result_invoice['vat'];
            $invoice_trp = $result_invoice['transport'];
            $invo_amt = ($invoice_amount+$opening_revenue1)-($invoice_vat+$invoice_trp);
            $revenues[] = $invoice_amount+$opening_revenue1;
          ?>
          <?php
            $sql_msc = "SELECT sum(total) as msc_amt FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')"; 
            $query_msc = mysqli_query($conn,$sql_msc);
            $result_msc = mysqli_fetch_array($query_msc);
            $msc_amount = $result_msc['msc_amt'];
            $revenues[] = $msc_amount;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_enbd FROM voucher WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'RPT')
              {
              $enbd_cr1=$row["Cleared_rcp_enbd"];
              }
              elseif (substr($idet, 0, 3) === 'PVR')
              {
              $enbd_dr1=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd1 = $enbd_cr1-$enbd_dr1;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_cbd FROM voucher WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    )results";
            $query = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($query))
            {  
              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'RPT')
              {
              $cbd_cr1=$row["Cleared_rcp_cbd"];
              }
              elseif (substr($idet, 0, 3) === 'PVR')
              {
              $cbd_dr1=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd1 = $cbd_cr1-$cbd_dr1;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_enbd FROM reciept WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_enbd FROM voucher WHERE inward='815' AND status='Cleared' AND STR_TO_DATE(voucher.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_enbd FROM miscellaneous WHERE bank='815' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PTY', id) AS id, sum(amount) as Cleared_rcp_enbd FROM petty_voucher WHERE bank='815' AND STR_TO_DATE(petty_voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
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
              $enbd_cr_msc=$row["Cleared_rcp_enbd"];
              }
              elseif (substr($idet, 0, 3) === 'PTY')
              {
              $enbd_dr_petty=$row["Cleared_rcp_enbd"];
              }
            }
            $enbd = ($enbd_cr+$enbd_cr_msc)-($enbd_dr+$enbd_dr_petty);
            $received[] = $enbd;
          ?>
          <?php
            $sql = "SELECT * FROM (
                    (SELECT CONCAT('RPT', id) AS id, sum(amount) AS Cleared_rcp_cbd FROM reciept WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PVR', id) AS id, sum(amount) as Cleared_rcp_cbd FROM voucher WHERE inward='816' AND status='Cleared' AND STR_TO_DATE(voucher.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, sum(total) as Cleared_rcp_cbd FROM miscellaneous WHERE bank='816' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                    UNION ALL
                    (SELECT CONCAT('PTY', id) AS id, sum(amount) as Cleared_rcp_cbd FROM petty_voucher WHERE bank='816' AND STR_TO_DATE(petty_voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
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
              $cbd_cr_msc=$row["Cleared_rcp_cbd"];
              }
              elseif (substr($idet, 0, 3) === 'PTY')
              {
              $cbd_dr_petty=$row["Cleared_rcp_cbd"];
              }
            }
            $cbd = ($cbd_cr+$cbd_cr_msc)-($cbd_dr+$cbd_dr_petty);
            $received[] = $cbd;
          ?>
          <?php
            $clearence_receipt_enbd = $enbd_cr1-$enbd_cr;
            $clearence_receipt_cbd = $cbd_cr1-$cbd_cr;
            $clearence_receipt = $clearence_receipt_enbd+$clearence_receipt_cbd;
            
            $clearence_voucher_enbd = $enbd_dr1-$enbd_dr;
            $clearence_voucher_cbd = $cbd_dr1-$cbd_dr;
            $clearence_voucher = $clearence_voucher_enbd+$clearence_voucher_cbd;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_vch FROM voucher WHERE status='Uncleared' AND STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_vch = $result['Uncleared_vch'];
            $Uncleared_vch=$Uncleared_vch+$clearence_voucher;
            $revenues[] = $Uncleared_vch;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Uncleared_rcp FROM reciept WHERE status='Uncleared' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Uncleared_rcp = $result['Uncleared_rcp'];
            $Uncleared_rcp = $Uncleared_rcp+$clearence_receipt;
            $received[] = $Uncleared_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Bounce_rcp FROM reciept WHERE status='Bounce' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Bounce_rcp = $result['Bounce_rcp'];
            $received[] = $Bounce_rcp;
          ?>
          <?php
            $sql = "SELECT sum(amount) AS Hold_rcp FROM reciept WHERE status='Hold' AND STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $Hold_rcp = $result['Hold_rcp'];
            $received[] = $Hold_rcp;
          ?>
          <?php
            $sql = "SELECT SUM(grand) as total_receipt,sum(discount) AS receipt_discount FROM reciept WHERE STR_TO_DATE(reciept.pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $receipt_discount = $result['receipt_discount'];
            $receipt_total = $result['total_receipt'];
            $received[] = $receipt_discount;
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
            $receipt_pending = ($opening_revenue1+$actual_opening+$invoice_amount+$adv_bal)-($receipt_total+$credit_note);
            $received[] = $receipt_pending;
          ?>
          <?php  
          $sql_expense = "SELECT sum(vat) as expense_vat,sum(amount) as total_expense FROM expenses WHERE STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
          $query_expense = mysqli_query($conn,$sql_expense);
          $result_expense = mysqli_fetch_array($query_expense);
          $expense_vat = $result_expense['expense_vat'];
          $total_expense = $result_expense['total_expense'];
          $received[] = $expense_vat;
         ?>
         <?php
         $sql_voucher = "SELECT sum(grand) as voucher_grand,sum(discount) as voucher_discount FROM voucher WHERE STR_TO_DATE(voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
         $query_voucher = mysqli_query($conn,$sql_voucher);
         $result_voucher = mysqli_fetch_array($query_voucher);
         $voucher_grand = $result_voucher['voucher_grand'];
         $voucher_discount = $result_voucher['voucher_discount'];
         $revenues[] = $voucher_discount;
        ?>
        <?php
            $sql = "SELECT sum(tbl.total) as non_voucher FROM (
                    SELECT expenses.inv as inv,sum(expenses.amount) as total FROM expenses 
                    WHERE STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND inv NOT IN (SELECT voucher_invoice.inv FROM voucher_invoice WHERE STR_TO_DATE(voucher_invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY voucher_invoice.inv) GROUP BY expenses.inv
                        ) as tbl";
            $query = mysqli_query($conn,$sql);
            $result = mysqli_fetch_array($query);
            $non_voucher = $result['non_voucher'];
            $partial_voucher_balance = ($total_expense)-($voucher_grand+$non_voucher);
            $total_non_voucher = $actual_borrow+$non_voucher+$partial_voucher_balance;
            $revenues[] = $total_non_voucher;
        ?>
        <?php
          $sql = "SELECT (sum(petty_vch)-sum(petty_exp)) as petty_bal FROM ("
                  . "(SELECT sum(amount) as petty_vch,NULL as petty_exp FROM petty_voucher WHERE STR_TO_DATE(petty_voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))"
                  . "UNION ALL"
                  . "(SELECT NULL as petty_vch,sum(total) as petty_exp FROM petty_item WHERE STR_TO_DATE(petty_item.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))"
                  . ")result";
          $query = mysqli_query($conn,$sql);
          $result = mysqli_fetch_array($query);
          $petty_bal = $result['petty_bal'];
          if($petty_bal > 0) 
          {
          $received[] = $petty_bal;  
          } else {
          $revenues[] = -($petty_bal);
          }
        ?>
<!-- row ends here-->
            
             <tr>
              <td><a href="ledger_trial_balance_details?type=rvn&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Revenues
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invo_amt);?></td>
             </tr>
            
            
             <tr>
              <td><a href="ledger_trial_balance_details?type=trp&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Transport Service
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_trp);?></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=mis&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Other Income -Scrab
              </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $msc_amount);?></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=dis_rpt&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Discount on Revenue
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $receipt_discount);?></td>
              <td align="right"></td>
             </tr>     
<!--EXPENSE SECTION IS BELOW-->       
                
<!-- row starts here-->
        <?php
        $sql1 = "SELECT sum(expense_amount) as expense_amount,cat,tag FROM (
                (SELECT amt as expense_amount,category as cat,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id
                 WHERE expense_categories.type = 1 AND STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 UNION ALL
                (SELECT total as expense_amount,petty_item.type as cat,tag FROM petty_item INNER JOIN expense_categories ON petty_item.type = expense_categories.id
                 WHERE expense_categories.type = 1 AND STR_TO_DATE(petty_item.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 )result GROUP BY cat";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_amount = $row1['expense_amount'];
         $received[] = $expense_amount;
         $tag = $row1['tag'];
         $cat = $row1['cat'];
         ?>    
             <tr>
              <td><a href="ledger_trial_balance_details?type=exp&cat=<?php echo $cat;?>&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  C.O.R. <?php echo $tag;?>
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_amount);?></td>
              <td align="right"></td>
             </tr>
        <?php } } ?>
             
        <?php
        $sql1 = "SELECT sum(expense_amount) as expense_amount,cat,tag FROM (
                (SELECT amt as expense_amount,category as cat,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id
                 WHERE expense_categories.type = 2 AND STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 UNION ALL
                (SELECT total as expense_amount,petty_item.type as cat,tag FROM petty_item INNER JOIN expense_categories ON petty_item.type = expense_categories.id
                 WHERE expense_categories.type = 2 AND STR_TO_DATE(petty_item.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 )result GROUP BY cat";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_amount = $row1['expense_amount'];
         $received[] = $expense_amount;
         $tag = $row1['tag'];
         $cat = $row1['cat'];
         ?>    
             <tr>
              <td><a href="ledger_trial_balance_details?type=exp&cat=<?php echo $cat;?>&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  <?php echo $tag;?>
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_amount);?></td>
              <td align="right"></td>
             </tr>
       <?php } } ?>
             
<!--        <tr>
              <td>Opening</td>
              <td align="right"><?php echo custom_money_format('%!i', $pending_opening1);?></td>
              <td align="right"></td>
            </tr>-->
             <tr>
              <td><a href="ledger_trial_balance_details?type=exp_vat&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Tax Receivables
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $expense_vat);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=adv&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Advance
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $adv_bal);?></td>
             </tr>
            
             <tr>
             <td><a href="ledger_trial_balance_details?type=petty&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                 Petty Cash Iftikhar Ali
                 </a></td>
             <?php if($petty_bal > 0) { ?>
              <td align="right"><?php echo custom_money_format('%!i', abs($petty_bal));?></td>
              <td align="right"></td>
             <?php } else { ?>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', abs($petty_bal));?></td>
             <?php } ?>
             </tr>
             
             <tr>
              <td><a href="bank_cash_flow_search?id=815&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  BANK - E.N.B.D
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $enbd);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="bank_cash_flow_search?id=816&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  BANK - C.B.D
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $cbd);?></td>
              <td align="right"></td>
             </tr>
           
             <tr>
              <td><a href="ledger_trial_balance_details?type=uncl_rcp&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivabales
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Uncleared_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=rcp_bnc&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivables - Bounce
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Bounce_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=rcp_hld&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Bill Receivables - Hold
                  </a></td>
              <td align="right"><?php echo custom_money_format('%!i', $Hold_rcp);?></td>
              <td align="right"></td>
             </tr>
             <tr>
             <td><a href="ledger_trial_balance_details?type=crdt_not&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                 Credit Note
                 </a></td>
             <td align="right"><?php echo custom_money_format('%!i', $credit_note);?></td>
             <td align="right"></td>
             </tr>
             <tr>
             <td>
                 <a href="ledger_trial_balance_details?type=csts&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                 Customers
                 </a>
                 </td>
             <td align="right"><?php echo custom_money_format('%!i', $receipt_pending);?></td>
             <td align="right"></td>
             </tr>
             
             <tr>
              <td>
                  <!--<a href="ledger_trial_balance_details?type=uncl_vch&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">-->
                  Bills Payable
                  <!--</a>-->
                  </td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $Uncleared_vch);?></td>
             </tr>
             <tr>
              <td>
                  <!--<a href="ledger_trial_balance_details?type=non_vch&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">-->
                  Creditors
                  <!--</a>-->
                  </td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $total_non_voucher);?></td>
             </tr>
             <tr>
              <td><a href="ledger_trial_balance_details?type=dis_vch&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Discount on Expense
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $voucher_discount);?></td>
             </tr>
             
             <tr>
              <td><a href="ledger_trial_balance_details?type=rvn&dt1=<?php echo $date1;?>&dt2=<?php echo $date2;?>" target="_blank">
                  Tax Payable
                  </a></td>
              <td align="right"></td>
              <td align="right"><?php echo custom_money_format('%!i', $invoice_vat);?></td>
             </tr>
<!-- row ends here-->


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