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
          <form role="form" action="" method="post" onsubmit="target_popup(this)">
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
                <div class="form-group"><div class="input-group date" data-ui-jp="datetimepicker" data-ui-options="{
                viewMode: 'years',
                format: '01-MM-YYYY',
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
              }"><input type="text" name="date" Required class="form-control"> <span class="input-group-addon"><span class="fa fa-calendar"></span></span></div></div>
              </div>
            </div>   
            
               <div class="form-group row m-t-md">
               <div align="right" class="col-sm-offset-2 col-sm-12">
                <button type="reset" class="btn btn-sm btn-outline rounded b-danger text-danger">Clear</button>
                <!--<button name="print" type="submit" class="btn btn-sm btn-outline rounded b-info text-info">Print</button>-->
                <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Compare</button>
                <button type="submit" formaction="<?php echo $cdn_url;?>/reports/convert_report_compare" class="btn btn-sm btn-outline rounded b-success text-success">Print</button>
              </div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>     
     

     <?php if(isset($_POST['submit'])) 
     {

          $thismonth1 = $_POST['date'];
          $thismonth2 = date("t-m-Y", strtotime($thismonth1));
          $thismonth3 = strtotime($thismonth2);
          
          $thismonth = date("m,Y", $thismonth3);
          $prevmonth = date('m,Y', strtotime('-1 month', strtotime($thismonth1)));
          $prevmonth1 = date('m,Y', strtotime('-2 month', strtotime($thismonth1)));
         
          $month1 = date('M', strtotime($thismonth));
          $month2 = date('M', strtotime('-1 month', strtotime($thismonth2)));
          $month3 = date('M', strtotime('-2 month', strtotime($thismonth2)));
          
          
          
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
             <h2>Comparison From : <?php echo $prevmonth1;?> - <?php echo $thismonth;?></h2>
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
                  SALESMAN
              </th>
              <th>
                   Month
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>    
	<?php
//        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        
        $sql = "SELECT `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') as period, ROUND(sum(`total`),2) as amount
                    FROM `delivery_note`
                    WHERE  DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$thismonth'
                    OR DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$prevmonth'
                    OR DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$prevmonth1'
                    GROUP BY `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%Y,%m')
                    ORDER BY `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%Y,%m')";
        
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
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
               echo $period = $row["period"];
             ?></td>
             
             <td><?php
               echo $amount = $row["amount"];
             ?></td>       
          </tr>
		<?php
                $sl++;  
		}
		}
		?>
          
<!--          <tr>
               <td colspan="1"></td>
               <td colspan="1" align="right"><b>TOTAL&nbsp;</b></td>
               <td colspan="1" align="left"><b><?php echo $tamount1=round($tamount1,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $tamount2=round($tamount2,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $tamount3=round($tamount3,2);?></b></td>
               <td colspan="1" align="left"><b><?php echo $totalsum=round($totalsum,2);?></b></td>
          </tr>-->
          
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
             <h2>Comparison From : <?php echo $prevmonth1;?> - <?php echo $thismonth;?></h2>
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
                  SALESMAN
              </th>
              <th>
                   Month
              </th>
              <th>
                  Amount
              </th>
          </tr>
        </thead>
        <tbody>
        <?php
//        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$prevmonth1', '%d/%m/%Y') AND STR_TO_DATE('$today', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        
        $sql = "SELECT `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') as period, ROUND(sum(`total`),2) as amount
                    FROM `delivery_note`
                    WHERE `rep` = $rep AND DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$thismonth'
                    OR DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$prevmonth'
                    OR DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%m,%Y') = '$prevmonth1'
                    GROUP BY `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%Y,%m')
                    ORDER BY `rep`, DATE_FORMAT(STR_TO_DATE(`date`,'%d/%m/%Y'), '%Y,%m')";
        
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>     
             
          <tr>
             <td> <?php echo $sl;?> </td>
             <td><?php
               $rep = $row["rep"];
               $sqlcust="SELECT name from customers where id='$rep'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               echo $cust=$fetchcust['name'];
             ?></td>
             
             <td><?php
               echo $period = $row["period"];
             ?></td>
         
             <td><?php
               echo $amount = $row["amount"];
             ?></td>

          </tr>
        <?php $sl++; } } ?>	
          
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