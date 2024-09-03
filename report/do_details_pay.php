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
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>DELIVERY DETAILS [Receivable]</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $period;?></span></td>
     </tr>     
</table>
      
<?php
    $sql = "SELECT dn.id as do,dn.customer as cid,cs.name as customer,cst.p_name as site,dn.invoiced,cs.cust_type,
            ca.credit as limit1,ca.credit1 as limit2,cs.period as crdt_period,dn.rep as salesman,dn.date
            FROM delivery_note dn
            join customers cs on dn.customer=cs.id
            join customer_site cst on dn.customersite=cst.id
            left join credit_application ca on dn.customer=ca.company
            WHERE dn.date='$period'";

     $result = mysqli_query($conn, $sql);
     if (mysqli_num_rows($result) > 0) 
	{ 
     $sl=1;
?>
 <table id="tbl1" align="center" style="width:96%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                   DO
              </th>
              <th>
                   Inv
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Site
              </th>
              <th>
                   Sales Person
              </th>
              <th>
                   Status
              </th>
              <th>
                   Credit Limit
              </th>
              <th>
                   Outstanding
              </th>
              <th>
                   Credit Period
              </th>
              <th>
                   Over Credit Period
              </th>
              <th>
                   Last Payment
              </th>
              <th>
                   Date of Last Payment
              </th>
              <th>
                   PDC Available
              </th>
              
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) 
        { 
            $id = $row['do'];
            $cid = $row['cid'];
            $rep = $row['salesman'];
            
            $row['limit1'] = ($row['limit1'] != NULL) ? $row['limit1'] : 0;
            $row['limit2'] = ($row['limit2'] != NULL) ? $row['limit2'] : 0;

            $limit = $row['limit1'] + $row['limit2'];
            
              $sql3 = "SELECT name FROM customers WHERE id='$rep'";
        	    $result3 = mysqli_query($conn, $sql3);
        	    $row3 = mysqli_fetch_assoc($result3);
            
        	    $sql1 = "SELECT grand,clearance_date FROM reciept WHERE `customer`='$cid' AND `status`='Cleared' ORDER BY STR_TO_DATE(`clearance_date`,'%d/%m/%Y') DESC LIMIT 1";
        	    $result1 = mysqli_query($conn, $sql1);
        	    $row1 = mysqli_fetch_assoc($result1);
        	    
        	    $sql2 = "SELECT sum(grand) as pdc_available FROM reciept WHERE customer='$cid' AND pmethod='cheque' AND status!='Cleared'";
        	    $result2 = mysqli_query($conn, $sql2);
        	    $row2 = mysqli_fetch_assoc($result2);
        	    
        	    $sql4 = "SELECT sum(total) as total_delivery,sum(transport) as total_transport FROM delivery_note WHERE customer='$cid'";
        	    $result4 = mysqli_query($conn, $sql4);
        	    $row4 = mysqli_fetch_assoc($result4);
        	    $delivery = $row4['total_delivery'];
              $delivery = ($delivery != NULL) ? $delivery : 0;
        	    $trp = $row4['total_transport'];
              $trp = ($trp != NULL) ? $trp : 0;
        	    $total_delivery = ($delivery-$trp)*1.05;
        	    $total_delivery = $total_delivery+$trp;
        	    
        	    $sql5 = "SELECT sum(grand) as total_receipt FROM reciept WHERE customer='$cid' AND status='Cleared' AND type!=1";
        	    $result5 = mysqli_query($conn, $sql5);
        	    $row5 = mysqli_fetch_assoc($result5);
        	    $sql6 = "SELECT sum(total) as total_cd_note FROM credit_note WHERE customer='$cid'";
        	    $result6 = mysqli_query($conn, $sql6);
        	    $row6 = mysqli_fetch_assoc($result6);

              $row5['total_receipt'] = ($row5['total_receipt'] != NULL) ? $row5['total_receipt'] : 0;
              $row6['total_cd_note'] = ($row6['total_cd_note'] != NULL) ? $row6['total_cd_note'] : 0;

        	    $total_receipt = $row5['total_receipt']+$row6['total_cd_note'];
        	    
        	    $total_outstanding = $total_delivery-$total_receipt;
        	    
                
                $credit_period = $row['crdt_period'];
                // $tdays = strtotime(date('Y-m-d'))-strtotime(str_replace('/','-',$period));
                // $days = round($tdays / (60 * 60 * 24));
                // $over = $days-$credit_period;
                
                // $sql7 = "SELECT id,date FROM (
                //             (SELECT inv.id,inv.date FROM invoice inv
                //             LEFT JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                //             LEFT JOIN credit_note cn ON inv.id=cn.invoice
                //             WHERE inv.customer='$cid' AND inv.grand > 0 AND rinv.invoice IS NULL AND cn.invoice IS NULL ORDER BY inv.id ASC LIMIT 1)
                //             UNION ALL
                //             (SELECT inv.id,inv.date FROM invoice inv
                //             JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                //             JOIN reciept rpt ON rpt.id=rinv.reciept_id
                //             WHERE inv.customer='$cid' AND inv.grand > 0 AND rpt.status!='Cleared' ORDER BY inv.id ASC LIMIT 1)
                //         ) xss ORDER BY id ASC LIMIT 1";
                
                $sql7 = "SELECT id,date FROM (
                            (SELECT inv.id,inv.date FROM invoice inv
                            WHERE inv.customer='$cid' AND inv.grand > 0 AND inv.status != 'Paid' ORDER BY inv.id ASC LIMIT 1)
                            UNION ALL
                            (SELECT inv.id,inv.date FROM invoice inv
                            JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                            JOIN reciept rpt ON rpt.id=rinv.reciept_id
                            WHERE inv.customer='$cid' AND inv.grand > 0 AND inv.status = 'Paid' AND rpt.status!='Cleared' ORDER BY inv.id ASC LIMIT 1)
                        ) xss ORDER BY id ASC LIMIT 1";
                
                $result7 = mysqli_query($conn, $sql7);
        	    $row7 = mysqli_fetch_assoc($result7);
                $oldest_date = $row7['date'];
                if($oldest_date != NULL){
                $tdays = strtotime(date('Y-m-d'))-strtotime(str_replace('/','-',$oldest_date));
                $days = round($tdays / (60 * 60 * 24));
                $over = $days-$credit_period;
                }else{$over=0;}
                
                
                $invoiced = $row['invoiced'];
                if($invoiced == NULL){$invoiced='No';}
        	    
        ?>     
            
             <tr>
                <td><?php echo $id;?></td>
                <td><?php echo $invoiced;?></td>
                <td><?php echo $row['customer'];?></td>
                <td><?php echo $row['site'];?></td>
                <td><?php echo $row3['name'];?></td>
                <td><?php echo $row['cust_type'];?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$limit);?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$total_outstanding);?></td>
                <td><?php echo $credit_period;?> Days</td>
                <td><?php if($over > 0){ echo $over;}?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$row1['grand']);?></td>
                <td><?php echo $row1['clearance_date'];?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$row2['pdc_available']);?></td>
                
             </tr>
             
        <?php 
            $sl=$sl+1;
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