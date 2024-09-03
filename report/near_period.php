<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(!empty($_POST))
{
    $period = $_POST['date'];
}
$period = date('d/m/Y');
?>
<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page {margin: 0mm;}
           #hlpo,#blpo{width:100px;word-break: break-all;}
           #hsite,#bsite{width:200px;word-break: break-all;}
           #hitem,#bitem{width:200px;word-break: break-all;}
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
           
         /*p.bodyText {font-size:10pt}*/
      }
  
</style>

<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
h1, h2 {
    font-family: Arial, Helvetica, sans-serif;
}
th,td {
    font-family: verdana;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>CUSTOMERS NEAR CREDIT PERIOD</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $period;?></span></td>
     </tr>     
</table>
      
<?php
//     $sql = "SELECT invo,date,cid,customer,cust_type,period,rep,(dt1-period) ocp FROM (
//                 SELECT invo,date,cid,customer,cust_type,period,rep,DATEDIFF(CURDATE(),STR_TO_DATE(date, '%d/%m/%Y')) dt1 FROM (
//                     SELECT invo,date,cid,customer,cust_type,period,rep FROM (
//                         (SELECT inv.id as invo,inv.date,cs.id as cid,cs.name as customer,cs.cust_type,cs.period,cs1.name as rep FROM invoice inv
//                         JOIN customers cs ON inv.customer=cs.id
//                         LEFT JOIN reciept_invoice rinv ON inv.id=rinv.invoice
//                         LEFT JOIN credit_note cn ON inv.id=cn.invoice
//                         LEFT JOIN sales_order so ON inv.o_r=so.order_referance
//                         JOIN customers cs1 ON so.salesrep=cs1.id
//                         WHERE inv.grand > 0 AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')) AND rinv.invoice IS NULL AND cn.invoice IS NULL)
//                         UNION ALL
//                         (SELECT inv.id as invo,inv.date,cs.id as cid,cs.name as customer,cs.cust_type,cs.period,cs1.name as rep FROM invoice inv
//                         JOIN customers cs ON inv.customer=cs.id
//                         JOIN reciept_invoice rinv ON inv.id=rinv.invoice
//                         JOIN reciept rpt ON rpt.id=rinv.reciept_id
//                         LEFT JOIN sales_order so ON inv.o_r=so.order_referance
//                         JOIN customers cs1 ON so.salesrep=cs1.id
//                         WHERE inv.grand > 0 AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')) AND rpt.status!='Cleared')
//                     ) xss ORDER BY invo ASC
//     			) xmm GROUP BY cid
// 			) xdd WHERE (dt1-period) > -3 AND (dt1-period) < 3 ORDER BY (dt1-period) ASC";
			
// 	$sql = "SELECT invo,date,cid,customer,cust_type,period,rep,amt,(dt1-period) ocp FROM (
//                 SELECT invo,date,cid,customer,rep,amt,cust_type,period,DATEDIFF(CURDATE(),STR_TO_DATE(date, '%d/%m/%Y')) dt1 FROM (
//                     SELECT invo,date,cid,repr,amt,cs.name as customer,cs1.name as rep,cs.cust_type,cs.period FROM (
//                         (SELECT inv.id as invo,inv.date,inv.customer as cid,inv.rep as repr,inv.grand as amt FROM invoice inv
//                         WHERE inv.grand > 0 AND inv.status != 'Paid' AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')))
//                         UNION ALL
//                         (SELECT inv.id as invo,inv.date,inv.customer as cid,inv.rep as repr,inv.grand as amt FROM invoice inv
//                         JOIN reciept_invoice rinv ON inv.id=rinv.invoice
//                         JOIN reciept rpt ON rpt.id=rinv.reciept_id
//                         WHERE inv.grand > 0 AND inv.status = 'Paid' AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')) AND rpt.status!='Cleared')
//                     ) results
//                     JOIN customers cs ON results.cid=cs.id
//                     JOIN customers cs1 ON results.repr=cs1.id
//                     ORDER BY invo ASC
//     			) xmm
// 			) xdd WHERE (dt1-period) > -3 AND (dt1-period) < 3 GROUP BY cid ORDER BY (dt1-period) ASC";
			
	$sql="SELECT * FROM (
            SELECT *,ROW_NUMBER() OVER (PARTITION BY cid ORDER BY invo ASC) as rn FROM (
            SELECT 1 as slno,invo,date,cid,customer,cust_type,period,due,cq_amt,rep,amt,(dt1-period) ocp FROM (
            SELECT invo,date,cid,customer,rep,amt,cust_type,period,due,cq_amt,DATEDIFF(CURDATE(), STR_TO_DATE(date, '%d/%m/%Y')) dt1 FROM (
                                SELECT invo,date,cid,repr,amt,cs.name as customer,cs1.name as rep,cs.cust_type,cs.period,due,cq_amt FROM (
                                    (SELECT inv.id as invo,inv.date,inv.customer as cid,inv.rep as repr,inv.grand as amt,'' as due,'' as cq_amt FROM invoice inv
                                    WHERE inv.grand > 0 AND inv.status != 'Paid' AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')))
                                    UNION ALL
                                    (SELECT inv.id as invo,inv.date,inv.customer as cid,inv.rep as repr,inv.grand as amt,rpt.duedate as due,rpt.grand as cq_amt FROM invoice inv
                                    JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                                    JOIN reciept rpt ON rpt.id=rinv.reciept_id
                                    WHERE inv.grand > 0 AND inv.status = 'Paid' AND (STR_TO_DATE(inv.date,'%d/%m/%Y') > STR_TO_DATE('01/01/2020', '%d/%m/%Y')) AND rpt.status!='Cleared')
                                ) results
                                JOIN customers cs ON results.cid=cs.id
                                JOIN customers cs1 ON results.repr=cs1.id
                			) xmm ORDER BY invo,(dt1-period) ASC
            			) xdd WHERE (dt1-period) > -3 AND (dt1-period) < 3)xxx
                ) rrt where rn=1 ORDER BY ocp ASC";
			
    
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{ $sl=1;
?>
 <table id="tbl1" align="center" style="width:96%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Customer Type
              </th>
              <th>
                   Salesman
              </th>
              <th>
                   Credit Period
              </th>
              <th>
                  Oldest Unpaid Invoice
              </th>
              <th>
                  Invoice Amount
              </th>
              <th>
                  Invoice Date
              </th>
              <th>
                   Over Credit Period
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) 
        { 
            // $cid = $row['id'];
                
                // if($oldest_date != NULL){
                // $tdays = strtotime(date('Y-m-d'))-strtotime(str_replace('/','-',$oldest_date));
                // $days = round($tdays / (60 * 60 * 24));
                // $over = $days-$credit_period;
                // }else{$over=NULL;}
                
            //     if($over > -3)
        	   // {

        $row['amt'] = ($row['amt'] != NULL) ? $row['amt'] : 0;
        ?>     
            
             <tr>
                <td><?php echo $sl;?></td>
                <td><?php echo $row['customer'];?></td>
                <td><?php echo $row['cust_type'];?></td>
                <td><?php echo $row['rep'];?></td>
                <td><?php echo $row['period'];?></td>
                
                <td><?php echo $row['invo'];?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$row['amt']);?></td>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $row['ocp'];?></td>
             </tr>
             
        <?php
        $sl=$sl+1;
        // }
            
        }
        ?>
       </tbody>
 </table>
 <?php } ?>
      

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>