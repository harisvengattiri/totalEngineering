<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0);?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box col-md-8">
    <div class="box-header">
	<span style="float: left;"><h2>Income Statement</h2></span>
    </div><br/>
    <div class="box-body">
         
         <form role="form" action="<?php echo $baseurl;?>/income_statement" method="post">
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
         $date1=$_POST['date1'];
         $date2=$_POST['date2'];
         
        //  $date_help = str_replace('/','-',$date2);
        // $year = date('Y', strtotime($date_help));
         $year_start = '01/01/2018';
     ?>  
      <h5 align=""><b>Period: <?php echo $date1;?> - <?php echo $date2;?></b></h5>  
      <table class="table m-b-none">
        <thead>
             <tr>
              <th>
                 REVENUES
              </th>
              <th style="text-align:center;">
                 Current Month
              </th>
              <th style="text-align:center;">
                 %
              </th>
              <th style="text-align:center;">
                 Year to Date
              </th>
              <th style="text-align:center;">
                 %
              </th>
             </tr>
        </thead>
        <tbody>
             
    <!-- row starts here-->
    
    <!--calculation for an year-->
          <?php
            $sql_invoice_year = "SELECT sum(total) AS inv_amount_year,sum(vat) AS vat_year,sum(transport) AS transport_year FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice_year = mysqli_query($conn,$sql_invoice_year);
            $result_invoice_year = mysqli_fetch_array($query_invoice_year);
            $invoice_amount_year = $result_invoice_year['inv_amount_year'];
            $vat_year = $result_invoice_year['vat_year'];
            $invoice_total_year = $invoice_amount_year;
            $transport_year = $result_invoice_year['transport_year'];
          ?>
          <?php
            $sql_com_year = "SELECT sum(amount) AS commission FROM pay_role_item WHERE component='Commission' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_com_year = mysqli_query($conn,$sql_com_year);
            $result_com_year = mysqli_fetch_array($query_com_year);
            $commission_year = $result_com_year['commission'];
            $commission_year = ($commission_year != NULL) ? $commission_year : 0;
          ?>
          <?php
            $sql_misc_year = "SELECT sum(total) AS total FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_misc_year = mysqli_query($conn,$sql_misc_year);
            $result_misc_year = mysqli_fetch_array($query_misc_year);
            $miscellaneous_year = $result_misc_year['total'];
          ?>
          <?php
            $sql_discount_year = "SELECT sum(discount) AS discount_year FROM reciept WHERE STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_discount_year = mysqli_query($conn,$sql_discount_year);
            $result_discount_year = mysqli_fetch_array($query_discount_year);
            $discount_year = $result_discount_year['discount_year'];
          ?>
          <?php
          $grand_year = $invoice_total_year + $transport_year + $miscellaneous_year - $discount_year - $commission_year;
          $invoice_per_year = $invoice_total_year/$grand_year*100;
          $transport_per_year = $transport_year/$grand_year*100;
          $discount_per_year = $discount_year/$grand_year*100;
          
          $commission_per_year = $commission_year/$grand_year*100;
          $miscellaneous_per_year = $miscellaneous_year/$grand_year*100;
          ?>
    
    
    <!--calculation for a period-->
          <?php
            $sql_invoice = "SELECT sum(total) AS inv_amount,sum(vat) AS vat,sum(transport) AS transport FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_invoice = mysqli_query($conn,$sql_invoice);
            $result_invoice = mysqli_fetch_array($query_invoice);
            $invoice_amount = $result_invoice['inv_amount'];
            $vat = $result_invoice['vat'];
            $invoice_total = $invoice_amount;
            $invoice_total = ($invoice_total != NULL) ? $invoice_total : 0;
            $transport = $result_invoice['transport'];
            $transport = ($transport != NULL) ? $transport : 0;
          ?>
          <?php
            $sql_com = "SELECT sum(amount) AS commission FROM pay_role_item WHERE component='Commission' AND STR_TO_DATE(pdate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_com = mysqli_query($conn,$sql_com);
            $result_com = mysqli_fetch_array($query_com);
            $commission = $result_com['commission'];
            $commission = ($commission != NULL) ? $commission : 0;
          ?>
          <?php
            $sql_misc = "SELECT sum(total) AS total FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_misc = mysqli_query($conn,$sql_misc);
            $result_misc = mysqli_fetch_array($query_misc);
            $miscellaneous = $result_misc['total'];
            $miscellaneous = ($miscellaneous != NULL) ? $miscellaneous : 0;
          ?>
          <?php
            $sql_discount = "SELECT sum(discount) AS discount FROM reciept WHERE STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
            $query_discount = mysqli_query($conn,$sql_discount);
            $result_discount = mysqli_fetch_array($query_discount);
            $discount = $result_discount['discount'];
            $discount = ($discount != NULL) ? $discount : 0;
          ?>
          <?php
          $grand_period = $invoice_total + $transport + $miscellaneous - $discount - $commission;
          if($grand_period == 0) {$invoice_per = $transport_per = $discount_per = $commission_per = $miscellaneous_per = 0;}
          else {
          $invoice_per = $invoice_total/$grand_period*100;
          $transport_per = $transport/$grand_period*100;
          $discount_per = $discount/$grand_period*100;
          
          $commission_per = $commission/$grand_period*100;
          $miscellaneous_per = $miscellaneous/$grand_period*100;
          }
          ?>
    
             <tr>
              <td>Revenues</td>
              <td align="right"><?php echo number_format($invoice_total, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($invoice_per, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($invoice_total_year, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($invoice_per_year, 2, '.', '');?>%</td>
             </tr>
<!-- row ends here-->


<!-- row starts here--> 
             <tr>
              <td>Transport Service</td>
              <td align="right"><?php echo number_format($transport, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($transport_per, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($transport_year, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($transport_per_year, 2, '.', '');?>%</td>
             </tr>
<!-- row ends here-->

<!-- row starts here--> 
             <tr>
              <td>Commission on Sales</td>
              <td align="right"><?php echo number_format($commission, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($commission_per, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($commission_year, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($commission_per_year, 2, '.', '');?>%</td>
             </tr>
<!-- row ends here-->

<!-- row starts here--> 
             <tr>
              <td>Other Income - Scrab</td>
              <td align="right"><?php echo number_format($miscellaneous, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($miscellaneous_per, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($miscellaneous_year, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($miscellaneous_per_year, 2, '.', '');?>%</td>
             </tr>
<!-- row ends here-->

<!-- row starts here--> 
             <tr>
              <td>Discount on Revenue</td>
              <td align="right">(<?php echo number_format($discount, 2, '.', '');?>)</td>
              <td align="right">(<?php echo number_format($discount_per, 2, '.', '');?>%)</td>
              
              <td align="right">(<?php echo number_format($discount_year, 2, '.', '');?>)</td>
              <td align="right">(<?php echo number_format($discount_per_year, 2, '.', '');?>%)</td>
             </tr>
<!-- row ends here-->
              <tr>
              <td><b>TOTAL REVENUE</b></td>
              <td align="right"><b><?php echo number_format($grand_period, 2, '.', '');?></b></td>
              <td align="right"><b><?php echo number_format(100, 2, '.', '');?>%</b></td>
              
              <td align="right"><b><?php echo number_format($grand_year, 2, '.', '');?></b></td>
              <td align="right"><b><?php echo number_format(100, 2, '.', '');?>%</b></td>
             </tr>
             
  <!--row starts here-->             
        <?php
//        $sql1 = "SELECT sum(amt) as amount,category,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id WHERE expense_categories.type = 1 AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
        $sql1 = "SELECT sum(expense_amount) as amount,cat as category,tag FROM (
                (SELECT amt as expense_amount,category as cat,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id
                 WHERE expense_categories.type = 1 AND STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 UNION ALL
                (SELECT total as expense_amount,petty_item.type as cat,tag FROM petty_item INNER JOIN expense_categories ON petty_item.type = expense_categories.id
                 WHERE expense_categories.type = 1 AND STR_TO_DATE(petty_item.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 )result GROUP BY cat";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        $total_expense_amount_period_1 = 0;
        $total_expense_amount_year_1 = 0;
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_cat_1 = $row1['category'];
         $expense_amount_year_1[] = $row1['amount'];
         $tag_1[] = $row1['tag'];
//                $sql2 = "SELECT sum(amt) as amount FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND category='$expense_cat_1'";
                $sql2 ="SELECT sum(expense_amount) as amount FROM (
                         (SELECT amt as expense_amount FROM expenses WHERE category='$expense_cat_1' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                          UNION ALL
                         (SELECT total as expense_amount FROM petty_item WHERE type='$expense_cat_1' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                          )result";
                $result2 = mysqli_query($conn,$sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $expense_amount_period_1[] = $row2['amount'];     
        
       $total_expense_amount_period_1 = $total_expense_amount_period_1 + $row2['amount'];
       $total_expense_amount_year_1 = $total_expense_amount_year_1 + $row1['amount'];
        }
        }
       ?>
       <?php
//        $sql1 = "SELECT sum(amt) as amount,category,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id WHERE expense_categories.type = 2 AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
        $sql1 = "SELECT sum(expense_amount) as amount,cat as category,tag FROM (
                (SELECT amt as expense_amount,category as cat,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id
                 WHERE expense_categories.type = 2 AND STR_TO_DATE(expenses.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 UNION ALL
                (SELECT total as expense_amount,petty_item.type as cat,tag FROM petty_item INNER JOIN expense_categories ON petty_item.type = expense_categories.id
                 WHERE expense_categories.type = 2 AND STR_TO_DATE(petty_item.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$year_start', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                 )result GROUP BY cat";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        $total_expense_amount_period_2 = 0;
        $total_expense_amount_year_2 = 0;
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
         $expense_cat_2 = $row1['category'];
         $expense_amount_year_2[] = $row1['amount'];
         $tag_2[] = $row1['tag'];
//                $sql2 = "SELECT sum(amt) as amount FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') AND category='$expense_cat_2'";
                $sql2 ="SELECT sum(expense_amount) as amount FROM (
                         (SELECT amt as expense_amount FROM expenses WHERE category='$expense_cat_2' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                          UNION ALL
                         (SELECT total as expense_amount FROM petty_item WHERE type='$expense_cat_2' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y'))
                          )result";
                $result2 = mysqli_query($conn,$sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $expense_amount_period_2[] = $row2['amount'];     
        
       $total_expense_amount_period_2 = $total_expense_amount_period_2 + $row2['amount'];
       $total_expense_amount_year_2 = $total_expense_amount_year_2 + $row1['amount'];
        }
        }
       ?>
  <!--total expense calculation-->
  <?php
   $total_expense_amount_period = $total_expense_amount_period_1 + $total_expense_amount_period_2;
   $total_expense_amount_year = $total_expense_amount_year_1 + $total_expense_amount_year_2;
  ?>
  
          <tr>
               <td><b>Cost of Sale</b></td>
          </tr>
       <?php $cnt_1 = count($tag_1);
         for($i=0;$i<$cnt_1;$i++) { ?>  
             <tr>
              <td>C.O.R. <?php echo $tag_1[$i];?></td>
              <td align="right"><?php
               $expense_amount_period_1[$i] = ($expense_amount_period_1[$i] != NULL) ? $expense_amount_period_1[$i] : 0;
               echo number_format($expense_amount_period_1[$i], 2, '.', '');
               ?></td>
              <?php
              if($grand_period != NULL) {
               $expense_per_1 = $expense_amount_period_1[$i]/$grand_period * 100;
              } else { $expense_per_1 = 0;}  
              ?>
              <td align="right"><?php echo number_format($expense_per_1, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($expense_amount_year_1[$i], 2, '.', '');?></td>
              <?php $expense_per_year_1 = $expense_amount_year_1[$i]/$grand_year * 100; ?>
              <td align="right"><?php echo number_format($expense_per_year_1, 2, '.', '');?>%</td>
             </tr>
       <?php } ?>
  <!--row ends here-->
             <tr>
              <td><b>TOTAL COST OF SALES</b></td>
              <td align="right"><b><?php echo number_format($total_expense_amount_period_1, 2, '.', '');?></b></td>
              <?php
               if($grand_period != NULL) {
               $total_expense_per_1 = $total_expense_amount_period_1/$grand_period * 100;
               } else { $total_expense_per_1 = 0;}
              ?>
              <td align="right"><b><?php echo number_format($total_expense_per_1, 2, '.', '');?>%</b></td>
              
              <td align="right"><b><?php echo number_format($total_expense_amount_year_1, 2, '.', '');?></b></td>
              <?php $total_expense_per_year_1 = $total_expense_amount_year_1/$grand_year * 100; ?>
              <td align="right"><b><?php echo number_format($total_expense_per_year_1, 2, '.', '');?>%</b></td>
             </tr>
             <?php
             $gross_profit_period_1 = $grand_period - $total_expense_amount_period_1;
             $gross_profit_year_1 = $grand_year - $total_expense_amount_year_1;
             ?>
             <tr>
              <td><b>GROSS PROFIT</b></td>
              <td align="right"><b><?php echo number_format($gross_profit_period_1, 2, '.', '');?></b></td>
              <?php $gross_profit_per = 100 - $total_expense_per_1; ?>
              <td align="right"><b><?php echo number_format($gross_profit_per, 2, '.', '');?>%</b></td>
              
              <td align="right"><b><?php echo number_format($gross_profit_year_1, 2, '.', '');?></b></td>
              <?php $gross_profit_per_year = 100 - $total_expense_per_year_1; ?>
              <td align="right"><b><?php echo number_format($gross_profit_per_year, 2, '.', '');?>%</b></td>
             </tr>        
    <!--row ends here-->            

  <!--second part-->  
   <!--row starts here-->
          <tr>
               <td><b>General Expense</b></td>
          </tr>
       <?php $cnt_2 = count($tag_2);
         for($i=0;$i<$cnt_2;$i++) { ?>  
             <tr>
              <td><?php echo $tag_2[$i];?></td>
              <td align="right"><?php
              if($expense_amount_period_2[$i] != NULL) {
               echo number_format($expense_amount_period_2[$i], 2, '.', '');
              } else { echo 0.00;}
               ?></td>
              <?php
              if($grand_period != NULL) {
              $expense_per_2 = $expense_amount_period_2[$i]/$grand_period * 100;
              } else { $expense_per_2 = 0;}
              ?>
              <td align="right"><?php echo number_format($expense_per_2, 2, '.', '');?>%</td>
              
              <td align="right"><?php echo number_format($expense_amount_year_2[$i], 2, '.', '');?></td>
              <?php $expense_per_year_2 = $expense_amount_year_2[$i]/$grand_year * 100; ?>
              <td align="right"><?php echo number_format($expense_per_year_2, 2, '.', '');?>%</td>
             </tr>
       <?php } ?>
  <!--row ends here-->
             <tr>
              <td><b>TOTAL EXPENSE</b></td>
              <td align="right"><b><?php echo number_format($total_expense_amount_period_2, 2, '.', '');?></b></td>
              <?php
               if($grand_period != NULL) {
               $total_expense_per_2 = $total_expense_amount_period_2/$grand_period * 100;
               } else { $total_expense_per_2 = 0;}
              ?>
              <td align="right"><b><?php echo number_format($total_expense_per_2, 2, '.', '');?>%</b></td>
              
              <td align="right"><b><?php echo number_format($total_expense_amount_year_2, 2, '.', '');?></b></td>
              <?php $total_expense_per_year_2 = $total_expense_amount_year_2/$grand_year * 100; ?>
              <td align="right"><b><?php echo number_format($total_expense_per_year_2, 2, '.', '');?>%</b></td>
             </tr>
             <?php
             $net_income_period = $grand_period - $total_expense_amount_period;
             $net_income_year = $grand_year - $total_expense_amount_year;
             ?>
             <tr>
              <td><b>NET INCOME</b></td>
              <td align="right"><b><?php echo number_format($net_income_period, 2, '.', '');?></b></td>
              <?php $net_income_per = 100 - $total_expense_per_2 - $total_expense_per_1; ?>
              <td align="right"><b><?php echo number_format($net_income_per, 2, '.', '');?>%</b></td>
              
              <td align="right"><b><?php echo number_format($net_income_year, 2, '.', '');?></b></td>
              <?php $net_income_year_per = 100 - $total_expense_per_year_2 - $total_expense_per_year_1; ?>
              <td align="right"><b><?php echo number_format($net_income_year_per, 2, '.', '');?>%</b></td>
             </tr>        
    <!--row ends here-->    
    
    
    
    
    
    
             
   <!--row starts here--> 
        <?php
        $sql2 = "SELECT sum(amount) as amount,category,tag FROM assets INNER JOIN asset_categories ON assets.category = asset_categories.id WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
        $result2 = mysqli_query($conn,$sql2);  
        if (mysqli_num_rows($result2) > 0)
	    {
        while($row2 = mysqli_fetch_assoc($result2)) 
        {
         $asset_cat = $row2['category'];
         $asset_amount = $row2['amount'];
         $asset_tag = $row2['tag'];
         ?>    
             <tr>
              <td><?php echo $sl;?></td>
              <td><?php echo $asset_tag;?></td>
              <td align="right"><?php echo number_format(0, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($asset_amount, 2, '.', '');?></td>
             </tr>
       <?php $sl++; } } ?>
   <!--row ends here--> 
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
