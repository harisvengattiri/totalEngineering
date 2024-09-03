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
    <div class="col-md-6">
      <div class="box">
        <div class="box-header">
          <h2>Comparison</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form role="form" action="" method="post"> 
            <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">SalesMan</label>
              <div class="col-sm-6">
		<select name="rep" class="form-control">
                  <?php 
                       $sql="SELECT rep AS r FROM delivery_note JOIN customers ON delivery_note.rep = customers.id
                             GROUP BY delivery_note.rep ORDER BY customers.name ";
                       $result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                                ?><option value="">ALL</option><?php     
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $cst=$row["r"];
                                        $sqlcust="SELECT name from customers where id='$cst'";
                                        $querycust=mysqli_query($conn,$sqlcust);
                                        $fetchcust=mysqli_fetch_array($querycust);
                                        $cust=$fetchcust['name'];
				?>
				<option value="<?php echo $cst;?>"><?php echo $cust;?></option>
				<?php 
				}}
                    ?>
                 </select>		
              </div>      
            </div>
            <div class="form-group row">   
            <label for="date" align="" class="col-sm-2 form-control-label">Date</label>
              <div class="col-sm-6">
                   <?php $current=date("d/m/Y"); ?>
                   <input type="text" name="date" value="<?php echo $current;?>" id="date" placeholder="Date" class="form-control has-value" data-ui-jp="datetimepicker" data-ui-options="{
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
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Compare</button>
                <button type="submit" formaction="<?php echo $baseurl;?>/mpdf/convert_report_compare" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     

     <?php if(isset($_POST['submit'])) 
     {
//          $today=date("d/m/Y");
         $today=$_POST['date'];
          
          $today1 = str_replace('/', '-', $today);
          
          $thismonth = date('01/m/Y', strtotime('0 month', strtotime($today1)));
          $prevmonth = date('01/m/Y', strtotime('-1 month', strtotime($today1)));
          $prevmonth1 = date('01/m/Y', strtotime('-2 month', strtotime($today1)));
          
          $month0 = date('M', strtotime('0 month', strtotime($today1)));
          $month1 = date('M', strtotime('-1 month', strtotime($today1)));
          $month2 = date('M', strtotime('-2 month', strtotime($today1)));
          
          
          
//          $thismonth = date('01/m/Y', strtotime('0 month'));
//          $prevmonth = date('01/m/Y', strtotime('-1 month'));
//          $prevmonth1 = date('01/m/Y', strtotime('-2 month'));
//          
//          $month0 = date('M', strtotime('0 month'));
//          $month1 = date('M', strtotime('-1 month'));
//          $month2 = date('M', strtotime('-2 month'));

          $rep=$_POST['rep'];
     ?>

     <?php
       if($rep==''){
     ?>
     
     <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Comparison From : <?php echo $prevmonth1;?> - <?php echo $today;?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  SALESMAN
              </th>
              <th>
                  <?php echo $month2; ?> <br> Amount
              </th>
              <th>
                  <?php echo $month1; ?> <br> Amount
              </th>
              <th>
                  <?php echo $month0; ?> <br> Amount
              </th>
              <th>
                  Total Amount
              </th>
          </tr>
        </thead>
        <tbody>    
	<?php
        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        $totalsum=0;
        $tamount1=0;
        $tamount2=0;
        $tamount3=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>
           
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php
               $rep = $row["rep"];
               $sqlcust="SELECT name from customers where id='$rep'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               echo $cust=$fetchcust['name'];
               
             ?></td>
             
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE (STR_TO_DATE(delivery_note.date,'%d/%m/%Y') >= STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE(delivery_note.date,'%d/%m/%Y') < STR_TO_DATE('$prevmonth', '%d/%m/%Y')) AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount1=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount1=$row1["amount"];
                         }
               echo round($amount1,2);
               if($amount1==''){echo 0;}
             ?></td>
             
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE (STR_TO_DATE(delivery_note.date,'%d/%m/%Y') >= STR_TO_DATE('$prevmonth', '%d/%m/%Y') AND STR_TO_DATE(delivery_note.date,'%d/%m/%Y') < STR_TO_DATE('$thismonth', '%d/%m/%Y')) AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount2=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount2=$row1["amount"];
                         }
               echo round($amount2,2);
               if($amount2==''){echo 0;}
             ?></td>
             
             
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$thismonth', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount3=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount3=$row1["amount"];
                         }
               echo round($amount3,2);
               if($amount3==''){echo 0;}
             ?></td>

             <td><?php
                echo $total=$amount3+$amount2+$amount1;
                     $totalsum=$totalsum+$total;
             ?></td>
             
          </tr>
		<?php
                $tamount1=$tamount1+$amount1;  
                $tamount2=$tamount2+$amount2;
                $tamount3=$tamount3+$amount3;
                  
                $sl++;  
		}
		}
		?>
          
          <tr>
               <td colspan="1"></td>
               <td colspan="1" align="right"><b>TOTAL&nbsp;</b></td>
               <td colspan="1" align="left"><b><?php echo $tamount1=round($tamount1,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $tamount2=round($tamount2,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $tamount3=round($tamount3,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $totalsum=round($totalsum,2);?></b></td>
          </tr>
          
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div>
       <?php } else { ?> 
     <!--testing-->
     
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
             <h2>Comparison From : <?php echo $prevmonth1;?> - <?php echo $today;?></h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          
        <table class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                  SALESMAN
              </th>
              <th>
                  <?php echo $month2; ?> <br> Amount
              </th>
              <th>
                  <?php echo $month1; ?> <br> Amount
              </th>
              <th>
                  <?php echo $month0; ?> <br> Amount
              </th>
              <th>
                  Total Amount
              </th>
              
          </tr>
        </thead>
        <tbody>
          <tr>
             <td> 1 </td>
             <td><?php
               $sqlcust="SELECT name from customers where id='$rep'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               echo $cust=$fetchcust['name'];
             ?></td>
             
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE (STR_TO_DATE(delivery_note.date,'%d/%m/%Y') >= STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE(delivery_note.date,'%d/%m/%Y') < STR_TO_DATE('$prevmonth', '%d/%m/%Y')) AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount1=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount1=$row1["amount"];
                         }
               echo round($amount1,2);
               if($amount1==''){echo 0;}
             ?></td>
         
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE (STR_TO_DATE(delivery_note.date,'%d/%m/%Y') >= STR_TO_DATE('$prevmonth', '%d/%m/%Y') AND STR_TO_DATE(delivery_note.date,'%d/%m/%Y') < STR_TO_DATE('$thismonth', '%d/%m/%Y')) AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount2=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount2=$row1["amount"];
                         }
               echo round($amount2,2);
               if($amount2==''){echo 0;}
             ?></td>
             
             
             <td><?php
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$thismonth', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount3=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount3=$row1["amount"];
                         }
               echo round($amount3,2);
               if($amount3==''){echo 0;}
             ?></td>

             <td><?php
                echo $total=$amount3+$amount2+$amount1;  
             ?></td>
             
             
          </tr>
		
          
        </tbody>
      </table>
             
             
        </div>
      </div>
    </div> 
       <?php } } ?> 
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
  
<?php include "includes/footer.php";?>