<?php
  include "../config.php";
  error_reporting(0);
  
$today=$_GET["date"];
$rep=$_GET["rep"];
  ?>
  
  <?php
          $today1 = str_replace('/', '-', $today);
          
          $thismonth = date('01/m/Y', strtotime('0 month', strtotime($today1)));
          $prevmonth = date('01/m/Y', strtotime('-1 month', strtotime($today1)));
          $prevmonth1 = date('01/m/Y', strtotime('-2 month', strtotime($today1)));
          
          $month0 = date('M', strtotime('0 month', strtotime($today1)));
          $month1 = date('M', strtotime('-1 month', strtotime($today1)));
          $month2 = date('M', strtotime('-2 month', strtotime($today1)));
  ?>
  

<h2 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?>COMPARISON REPORT</h2>
<table align="center" width="90%">
     <tr>
          <td width="60%">
               <!--<h4 style=""><span style="font-size:15px;">SalesMan: <?php echo $rep;?></span>-->
                    <!--<br><span>COC No: <?php echo $coc;?></span>-->
               </h4>
          </td>
          <td width="40%"><span style="font-size:15px;"> Date: From <?php echo $prevmonth1;?> - To <?php echo $today;?></span></td>
     </tr>     
</table>

     <?php
               $sql1 = "SELECT sum(amt) AS tamount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $tamount=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $tamount=$row1["tamount"];
                         }
     ?>

     <?php
       if($rep==''){
     ?>
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


     <?php } else { ?>
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

       <?php } ?>

<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>