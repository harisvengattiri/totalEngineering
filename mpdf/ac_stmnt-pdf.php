<?php
   include "../config.php";
//   error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$mindate = '01/01/2015';
$customer=$_GET["cst"];
  ?>
  
  <?php
    $sqlcust="SELECT name,op_bal,slmn from customers where id='$customer'";
    $querycust=mysqli_query($conn,$sqlcust);
    $fetchcust=mysqli_fetch_array($querycust);
    $cust=$fetchcust['name'];
    $opening=$fetchcust['op_bal'];
    $opening = ($opening != NULL) ? $opening : 0;
    $rep_id = $fetchcust['slmn'];
    
    // $sql_stf_id = "SELECT `rep` FROM `invoice` WHERE `customer`='$customer'";
    // $query_stf_id=mysqli_query($conn,$sql_stf_id);
    // $fetch_stf_id=mysqli_fetch_array($query_stf_id);
    // $rep_id=$fetch_stf_id['rep'];
        $sqlrep="SELECT name from customers where id='$rep_id'";
        $queryrep=mysqli_query($conn,$sqlrep);
        $fetchrep=mysqli_fetch_array($queryrep);
        $rep=$fetchrep['name'];
  ?>
  
<!--<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>-->
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>ACCOUNT STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Customer: <?php echo $cust;?><br>Customer No: <?php echo $customer;?><br>Salesman: <?php echo $rep;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php if ($fdate==''){echo "Since Inception";} else{echo $fdate;}?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th id="hsl">
                  Sl No
              </th>
<!--              <th>
                  Customer
              </th>-->
              <th>
                  Date
              </th>
              <th>
                  Serial No.
              </th>
              <th style="width:25%;">
                  Site / Bank Name
              </th><th>
                  L.P.O / Cheque No
              </th>
              <th>
                  Description/Ref
              </th>
             
              <th id="hso">
                  Debit
              </th>
              <th id="hdate">
                  Credit
              </th>
              <th id="hso">
                  Balance
              </th>
              
<!--              <th>
                  Sales Person
              </th>-->
                      
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
         
        $sqlinvbal="SELECT sum(grand) AS gran FROM invoice WHERE customer='$customer' AND (STR_TO_DATE(invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(invoice.date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultinv = mysqli_query($conn, $sqlinvbal);
        $rowinv = mysqli_fetch_assoc($resultinv);
        $invgran=$rowinv['gran'];
        $invgran = ($invgran != NULL) ? $invgran : 0;

        $sqlrcp = "SELECT round(sum(grand),2) as amount, NULL as adjust FROM `reciept` WHERE `customer`='$customer' AND (STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) AND status= 'Cleared'";
        $resultrcp = mysqli_query($conn, $sqlrcp);
        $rowrcp = mysqli_fetch_assoc($resultrcp);
        $rcpamount = $rowrcp['amount'];
        $rcpamount = ($rcpamount != NULL) ? $rcpamount : 0;
        
        
        $sqlcdt="SELECT sum(total) AS cdtamount FROM credit_note WHERE customer='$customer' AND (STR_TO_DATE(credit_note.date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(credit_note.date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultcdt= mysqli_query($conn, $sqlcdt);
        $rowcdt = mysqli_fetch_assoc($resultcdt);
        $cdtamount=$rowcdt['cdtamount'];
        $cdtamount = ($cdtamount != NULL) ? $cdtamount : 0;
        
        $sqlmisc="SELECT sum(total) AS miscamount FROM miscellaneous WHERE customer='$customer' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultmisc= mysqli_query($conn, $sqlmisc);
        $rowmisc = mysqli_fetch_assoc($resultmisc);
        $misclnsamount=$rowmisc['miscamount'];
        $misclnsamount = ($misclnsamount != NULL) ? $misclnsamount : 0;
        
        $sqlmis="SELECT sum(total) AS misamount FROM miscellaneous WHERE customer='$customer' AND status='Cleared' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultmis= mysqli_query($conn, $sqlmis);
        $rowmis = mysqli_fetch_assoc($resultmis);
        $miscamount=$rowmis['misamount'];
        $miscamount = ($miscamount != NULL) ? $miscamount : 0;
        
        $sqlrfd="SELECT sum(amount) AS refund FROM refund WHERE customer='$customer' AND (STR_TO_DATE(date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultrfd= mysqli_query($conn, $sqlrfd);
        $rowrfd = mysqli_fetch_assoc($resultrfd);
        $refund=$rowrfd['refund'];
        $refund = ($refund != NULL) ? $refund : 0;
        
        $invtotbal=$invgran+$opening+$misclnsamount+$refund;
        $getamount=$rcpamount+$cdtamount+$miscamount;
        
        $balance=$invtotbal-$getamount;
        
           
         $sql ="SELECT * FROM (
                              (SELECT CONCAT('INV', id) AS id, time, o_r AS po, invoice.date AS adate, invoice.site AS site, lpo AS lpo, NULL as c_date, NULL as ref, invoice.grand as credit FROM invoice WHERE customer='$customer' AND STR_TO_DATE(invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         UNION ALL
                              (SELECT CONCAT('RCP', id) AS id, time, NULL AS po, reciept.clearance_date AS adate, reciept.bank AS site, reciept.cheque_no AS lpo, duedate as c_date, ref as ref,reciept.amount as credit FROM reciept WHERE customer='$customer' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status= 'Cleared')
                         UNION ALL
                              (SELECT CONCAT('CDT', id) AS id, time, NULL AS po, credit_note.date AS adate, NULL AS site, invoice AS lpo, NULL as c_date, NULL as ref,credit_note.total as credit FROM credit_note WHERE customer='$customer' AND STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         UNION ALL
                              (SELECT CONCAT('RFD', id) AS id, time, NULL AS po, refund.date AS adate, remarks AS site, NULL AS lpo, NULL as c_date, NULL as ref,refund.amount as credit FROM refund WHERE customer='$customer' AND STR_TO_DATE(refund.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         UNION ALL
                              (SELECT CONCAT('MSC', id) AS id, time, NULL AS po, miscellaneous.date AS adate, particulars AS site, NULL AS lpo, NULL as c_date, NULL as ref,miscellaneous.total as credit FROM miscellaneous WHERE customer='$customer' AND STR_TO_DATE(miscellaneous.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         UNION ALL
                              (SELECT CONCAT('MSI', id) AS id, time, NULL AS po, miscellaneous.clearance_date AS adate, particulars AS site, NULL AS lpo, NULL as c_date, NULL as ref,miscellaneous.total as credit FROM miscellaneous WHERE customer='$customer' AND STR_TO_DATE(miscellaneous.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status= 'Cleared')
                         ) results ORDER BY STR_TO_DATE(adate, '%d/%m/%Y'),time ASC";     
                      
        $result = mysqli_query($conn, $sql);
        $totaldebit = $totalcredit = 0;
        if(mysqli_num_rows($result) > 0)
		{
            $sl=0;
            $tquantity=0;
            $bal=$balance;
        while($row = mysqli_fetch_assoc($result)) {
              $adt = 0;
              $idet=$row["id"];
              $date=$row['adate'];
              $lpo=$row['lpo'];
              $po=$row['po'];
              $ref=$row['ref'];
             
              if (substr($idet, 0, 3) === 'INV')
              {
              $cr=0;
              $dr=$row["credit"];
              $dr = ($dr != NULL) ? $dr : 0;
              $des='Invoice'.'[PO|'.$po.']';
                              $site=$row['site'];
                              $sqlsite="SELECT p_name from customer_site where id='$site'";
                              $querysite=mysqli_query($conn,$sqlsite);
                              $fetchsite=mysqli_fetch_array($querysite);
                              $site1=$fetchsite['p_name'];
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
              if (substr($idet, 0, 3) === 'RCP')
              {
              $id=substr($idet, 3);          
                  
              $dr=0;
              $cr=$row["credit"];
              $cr = ($cr != NULL) ? $cr : 0;
              $des='Receipt/'.$ref.'/'.$row["c_date"];
                              $bank=$row['site'];
                              $sqlbank="SELECT name from banks where id='$bank'";
                              $querybank=mysqli_query($conn,$sqlbank);
                              $fetchbank=mysqli_fetch_array($querybank);
                              $bank1=$fetchbank['name'];
                              
                    $sqladt="SELECT sum(adjust) AS adt FROM reciept_invoice where reciept_id='$id'";
                    $resultadt = mysqli_query($conn, $sqladt);
                    $rowadt = mysqli_fetch_assoc($resultadt);
                    $adt=$rowadt['adt'];
                    $adt = ($adt != NULL) ? $adt : 0;
                    
                $lpo = $lpo;    
                
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
              if (substr($idet, 0, 3) === 'CDT')
              {
              $dr=0;
              $cr=$row["credit"];
              $cr = ($cr != NULL) ? $cr : 0;
              $des='Credit Note';
              $lpo = 'INV|'.$lpo;
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
              if (substr($idet, 0, 3) === 'MSC')
              {
              $cr=0;
              $dr=$row["credit"];
              $dr = ($dr != NULL) ? $dr : 0;
              $des='Miscelanious';
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
              if (substr($idet, 0, 3) === 'MSI')
              {
              $dr=0;
              $cr=$row["credit"];
              $cr = ($cr != NULL) ? $cr : 0;
              $des='Miscelanious Cleared';
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
              if (substr($idet, 0, 3) === 'RFD')
              {
              $cr=0;
              $dr=$row["credit"];
              $dr = ($dr != NULL) ? $dr : 0;
              $des='Refund To Customer';
              $idet=substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));
              }
          ?>
          <?php if($sl==0) { ?>
             <tr>
              <td colspan="1"><?php ?></td>
              <td colspan="1"><?php echo $fdate;?></td>
              <td colspan="1"></td>
              <td colspan="1">Opening Balance</td>
              <td colspan="2"></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $bal);?></b></td>
              <td colspan="1" style="text-align: right;"><b>0.00</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $bal);?></b></td>
             </tr>
             
           <?php 
           $opbal = $bal;$sl++; } 
           ?>
           
        <?php if($dr !=0 || $cr !=0) { ?>
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td id="bso"><?php echo $date;?></td>
               <td><?php echo $idet;?></td>
               <td><?php 
               if (substr($idet, 0, 3) === 'INV')
               {
                   echo $site1;
               }
               else if(substr($idet, 0, 3) === 'RCP')
               {
                   echo $bank1;
               }
               else if(substr($idet, 0, 3) === 'MSC')
               {
                   echo $row["site"];
               }
               else if(substr($idet, 0, 3) === 'RFD')
               {
                   echo $row["site"];
               }
               ?></td> 
               
               <td><?php echo $lpo; ?></td> 
               <td><?php echo $des; ?></td>

               <td id="bso" style="text-align: right;"><?php echo custom_money_format('%!i', $dr);?></td>
               <td id="bdate" style="text-align: right;"><?php echo custom_money_format('%!i', $cr);?></td>
               
               <td id="bso" style="text-align: right;"><?php
                   $bal = $bal+$dr-$cr;
                   $bal = number_format($bal, 2, '.', '');
                   echo custom_money_format('%!i', $bal);
               ?></td>     
          </tr>
        <?php $sl=$sl+1; } ?>
          
          <?php if($adt > 0) { $bal=$bal-$adt;?>
          <tr>
              <td colspan="1"></td>
              <td colspan="1"><?php echo $date;?></td>
              <td colspan="3"></td>
              <td colspan="1">Discount</td>
              <td colspan="1"></td>
              <td colspan="1" style="text-align: right;"><?php echo custom_money_format('%!i', $adt);?></td>
              <td colspan="1" style="text-align: right;">
              <?php
                 echo custom_money_format('%!i', $bal);
              ?>
              </td>
          </tr>
          <?php } ?>
          
		<?php
                $totaldebit=$totaldebit+$dr;
                $totalcredit=$totalcredit+$cr+$adt;
		}
                } else
                { ?>
              <tr>
              <td colspan="1">1</td>
              <td colspan="1"><?php echo $fdate;?></td>
              <td colspan="1"></td>
              <td colspan="1">Opening Balance</td>
              <td colspan="2"></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $opening);?></b></td>
              <td colspan="1" style="text-align: right;"><b>0.00</b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $opening);?></b></td>
             </tr> 
             <?php }
             $totaldebit=$totaldebit+$balance;
		?>
             
             
              <?php
              if(mysqli_num_rows($result) > 0)
		      {
              ?>
              <tr>
              <td colspan="5"></td>
              <td><b>Total</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totaldebit);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalcredit);?></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$bal);?></b></td>
              </tr>
              <?php } ?>
        </tbody>
      </table>

<?php
  $sql="SELECT * FROM reciept where customer = '$customer' AND status != 'Cleared'"; 
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
	    { $sl=1;
?>
<table id="tbl1" align="center" style="width:90%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                  Sl
              </th>
              <th>
                   Date
              </th>
              <th>
                   Cheque No
              </th>
              <th>
                   Due Date
              </th>
              <th>
                   Bank
              </th>
              <th>
                   Status
              </th>
              <th style="width:20%;">
                   Invoice No
              </th>
              <th>
                   Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php 
        $totalamount = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];

            $grand = $row['grand'];
            $grand = ($grand != NULL) ? $grand : 0;
        ?>     
            
             <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row['pdate'];?></td>
             <td><?php echo $row['cheque_no'];?></td>
             <td><?php echo $row['duedate'];?></td>
             <td>
                  <?php
                    $bnk=$row['bank'];
                    $sqlbnk="SELECT name FROM banks where id='$bnk'"; 
                    $resultbnk = mysqli_query($conn, $sqlbnk);
                    $rowbnk = mysqli_fetch_assoc($resultbnk);
                  ?>
                  <?php echo $rowbnk['name'];?>
             </td>
             <td><?php echo $row['status'];?></td>
             <td>
                  <?php
                    $sql3="SELECT invoice FROM reciept_invoice where reciept_id='$id'"; 
                    $result3 = mysqli_query($conn, $sql3);
                    if (mysqli_num_rows($result3) > 0) 
                    {
                    $sl1=0;
                    while($row3 = mysqli_fetch_assoc($result3)) 
                    {
                    if($sl1 %4 == 0) {echo '<br>';}     
                  ?>
                  INV|<?php echo $row3['invoice'];?>
                  <?php $sl1++; } } ?>
             </td>
             <td style="text-align: right;"><?php echo custom_money_format('%!i', $grand);?></td>
             </tr>
             
                <?php 
                
                $sl=$sl+1; 
                $totalamount=$totalamount+$grand;
                } 
                ?>    
              <tr>
              <td colspan="6"></td>
              <td><b>Total</b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalamount);?></b></td>
              </tr>  
              
       </tbody>
 </table>
        <?php } ?>


<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{
?>
<body onload="window.print()">
<?php } ?>