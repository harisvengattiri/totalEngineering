<?php include "config.php";?>
<?php include "includes/menu.php";?>
<?php error_reporting(0);?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
	<span style="float: left;"><h2>Trial Balance</h2></span>
    </div><br/>
    <div class="box-body">
         
         <form role="form" action="<?php echo $baseurl;?>/trial_balance" method="post">
         <div class="form-group row">
              <div class="col-sm-2">
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
              <div class="col-sm-2">
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
         $date2=$_POST['date2'];
         
         $sql_open = "SELECT sum(amount) as expense_opening FROM expenses WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
         $result_open = mysqli_query($conn,$sql_open);
         $row_open = mysqli_fetch_assoc($result_open);
         $opening_expense_dr = $row_open['expense_opening'];
         
          $sql = "SELECT sum(amount) as amount FROM expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
          $result = mysqli_query($conn,$sql);
          $row = mysqli_fetch_assoc($result);
//          $opening_expense_dr = 0;         
          $opening_expense_cr = 0;
          $expense_dr = ($row['amount'] != NULL) ? $row['amount'] : 0;

          $expense_cr = 0;
          $closing_expense_dr = $expense_dr + $opening_expense_dr;
          $closing_expense_cr = 0;
          
         $sql_open = "SELECT sum(total) as liability_opening FROM liability WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y'))";
         $result_open = mysqli_query($conn,$sql_open);
         $row_open = mysqli_fetch_assoc($result_open);
         $opening_liability_dr = $row_open['liability_opening'];
          
          $sql = "SELECT sum(total) as amount FROM liability WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y')";
          $result = mysqli_query($conn,$sql);
          $row = mysqli_fetch_assoc($result);
//          $opening_liability_dr = 0;         
          $opening_liability_cr = 0;
          $liability_dr = ($row['amount'] != NULL) ? $row['amount'] : 0;
          $liability_cr = 0;
          $closing_liability_dr = $liability_dr + $opening_liability_dr;
          $closing_liability_cr = 0;
          
     ?>  
         <h5 align="center"><b>Period:</b><u><?php echo $date1;?> - <?php echo $date2;?></u></h5>  
      <table class="table m-b-none">
        <thead>
             <tr>
              <th>
                  Account
              </th>
              <th style="text-align:center;">
                 Currency
              </th>
              <th style="text-align:center;">
                 Opening DR
              </th>
              <th style="text-align:center;">
                 Opening CR
              </th>
              <th style="text-align:center;">
                 Debit
              </th>
              <th style="text-align:center;">
                 Credit
              </th>
              <th style="text-align:center;">
                 Closing DR
              </th> 
              <th style="text-align:center;">
                 Closing CR
              </th> 
             </tr>
        </thead>
        <tbody>
             
    <!--rows starts from here in table-->
    
    <!-- row starts here-->
    
              <?php
                
                
                ?>
    
    
              <tr>
              <td id="share_expense" style="cursor:pointer;"><i class="fa fa-plus" aria-hidden="true"></i> EXPENSES</td>
              <td align="center">AED</td>
              <td align="right"><?php echo number_format($opening_expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($opening_expense_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($expense_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_expense_cr, 2, '.', '');?></td>
             </tr>
        <?php
        $sql1 = "SELECT sum(amount) as amount,category,tag FROM expenses INNER JOIN expense_categories ON expenses.category = expense_categories.id WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
        $result1 = mysqli_query($conn,$sql1);  
        if (mysqli_num_rows($result1) > 0)
	{
        $sl=1;
        while($row1 = mysqli_fetch_assoc($result1)) 
        {
             $expense_cat = $row1['category'];
             
         $sql_open = "SELECT sum(amount) as expense_opening FROM expenses WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')) AND category='$expense_cat'";
         $result_open = mysqli_query($conn,$sql_open);
         $row_open = mysqli_fetch_assoc($result_open);
         $opening_expense_dr = $row_open['expense_opening'];
?>             
<script>
$(document).ready(function(){
     $("#share_expense").click(function(){
          $("#sharing_exp<?php echo $sl;?>").toggle();
     });
     });
</script>     
<?php
//          $opening_expense_dr = 0;         
          $opening_expense_cr = 0;
          $expense_dr = $row1['amount'];
          $expense_cr = 0;
          $closing_expense_dr = $expense_dr + $opening_expense_dr;
          $closing_expense_cr = 0;
  
          $tag = $row1['tag'];
         ?>    
             <tr id="sharing_exp<?php echo $sl;?>" style="display: none;">
              <td><?php echo $tag;?></td>
              <td align="center">AED</td>
              <td align="right"><?php echo number_format($opening_expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($opening_expense_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($expense_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_expense_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_expense_cr, 2, '.', '');?></td>
             </tr>
       <?php $sl++; } } ?>
   <!--row ends here-->
             
   <!--next row starts from here-->                
             <tr>
              <td id="share_liability" style="cursor:pointer;"><i class="fa fa-plus" aria-hidden="true"></i> LIABILITY</td>
              <td align="center">AED</td>
              <td align="right"><?php echo number_format($opening_liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($opening_liability_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($liability_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_liability_cr, 2, '.', '');?></td>
             </tr>
        <?php
//        $sql1 = "SELECT sum(amount) as amount,category,tag FROM liabilities INNER JOIN liability_categories ON liabilities.category = liability_categories.id WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$date1', '%d/%m/%Y') AND STR_TO_DATE('$date2', '%d/%m/%Y') GROUP BY category";
//        $result1 = mysqli_query($conn,$sql1);
//        if (mysqli_num_rows($result1) > 0)
//	{
//        $sl=1;
//        while($row1 = mysqli_fetch_assoc($result1))
//        {
//             $liability_cat = $row1['category'];
//             
//         $sql_open = "SELECT sum(amount) as liability_opening FROM liabilities WHERE (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$date1', '%d/%m/%Y')) AND category='$liability_cat'";
//         $result_open = mysqli_query($conn,$sql_open);
//         $row_open = mysqli_fetch_assoc($result_open);
//         $opening_liability_dr = $row_open['liability_opening'];    
?>             
<!--<script>
$(document).ready(function(){
     $("#share_liability").click(function(){
          $("#sharing_lbl<?php echo $sl;?>").toggle();
     });
     });
</script>     -->
         <?php        
//          $opening_liability_cr = 0;
//          $liability_dr = $row1['amount'];
//          $liability_cr = 0;
//          $closing_liability_dr = $liability_dr + $opening_liability_dr;
//          $closing_liability_cr = 0;
//           
//          $tag = $row1['tag'];
         ?>    
<!--             <tr id="sharing_lbl<?php echo $sl;?>" style="display: none;">
              <td><?php echo $tag;?></td>
              <td align="center">AED</td>
              <td align="right"><?php echo number_format($opening_liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($opening_liability_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($liability_cr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_liability_dr, 2, '.', '');?></td>
              <td align="right"><?php echo number_format($closing_liability_cr, 2, '.', '');?></td>
             </tr>-->
       <?php // $sl++; } } ?>       
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
