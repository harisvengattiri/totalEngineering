<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$rep=$_GET["rep"];
  ?>
  
  <?php
$sqlrep="SELECT name from customers where id='$rep'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep1=$fetchrep['name'];
  ?>
  

<h2 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?>Comparison & Percentage</h2>
<table align="center" width="90%">
     <tr>
          <td width="60%">
               <!--<h4 style=""><span style="font-size:15px;">SalesMan: <?php echo $rep;?></span>-->
                    <!--<br><span>COC No: <?php echo $coc;?></span>-->
               </h4>
          </td>
          <td width="40%"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
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
                  Amount
              </th>
              <th>
                  Percentage
              </th>
              
          </tr>
        </thead>
        <tbody>    
	<?php
        $sql = "SELECT rep FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY rep ORDER BY rep";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {     
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
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount=$row1["amount"];
                         }
               echo round($amount,2);
             ?></td>
             
             <td><?php
              $per=$amount/$tamount*100;
              echo $percentage = round($per,2).'%';
             ?></td>
             
          </tr>
		<?php
                $sl++;  
		}
		}
		?>
          <tr>
               <td colspan="1"></td>
               <td colspan="1" align="right"><b>TOTAL&nbsp;</b></td>
               <td colspan="1" align="left"><b><?php echo $total=round($tamount,2);?></b></td>
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
                  Amount
              </th>
              <th>
                  Percentage
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
               $sql1 = "SELECT sum(amt) AS amount FROM delivery_note JOIN delivery_item ON delivery_note.id = delivery_item.delivery_id WHERE STR_TO_DATE(delivery_note.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND delivery_note.rep=$rep AND delivery_item.batch!=''";
               $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount=0;
                         $row1 = mysqli_fetch_assoc($result1);
                         $amount=$row1["amount"];
                         }
               echo round($amount,2);
               if($amount==''){echo 0;}
             ?></td>
             
             
             <td><?php
              $per=$amount/$tamount*100;
              echo $percentage = round($per,2).'%';
             ?></td>
             
          </tr>
		
          <tr>
               <td colspan="1"></td>
               <td colspan="1" align="right"><b>TOTAL&nbsp;</b></td>
               <td colspan="1" align="left"><b><?php echo $total=round($tamount,2);?></b></td>
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