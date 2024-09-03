<?php
  include "../config.php";
  error_reporting(0);
  ?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$customer=$_POST["company"];

               $sqlcust="SELECT name,op_bal from customers where id='$customer'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
               $opening=$fetchcust['op_bal'];
}
?>
<!--<title> <?php //echo $title;?></title>-->
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

<!--<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> ACCOUNT STATEMENT </h1></center><br>
<h3 style="float:left;">Customer: <?php echo $cust;?></h3>
<h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>-->

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>ACCOUNT STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Customer: <?php echo $cust;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
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
                  Description
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
                  
//        $sql = "SELECT * FROM invoice JOIN reciept ON invoice.customer = reciept.customer AND invoice.customer='$customer'";
//        $sql = "SELECT * FROM invoice where customer='$customer' UNION ALL SELECT * FROM reciept where customer='$customer'";
        
                  
//                  $sql = "SELECT id,grand,customer,date FROM invoice where customer='$customer' UNION ALL"
//                          . " SELECT id,amount,customer,pdate FROM reciept where customer='$customer' ORDER BY date";
                  
        
        $sqlinvmin="SELECT MIN(STR_TO_DATE(date, '%d/%m/%Y')) AS invmin FROM invoice where customer='$customer'";
        $resultinvmin = mysqli_query($conn, $sqlinvmin);
        $rowinvmin = mysqli_fetch_assoc($resultinvmin);
        $mindate=$rowinvmin['invmin'];

        $mindate= date("d/m/Y", strtotime($mindate) );
                  
        $sqlinvbal="SELECT sum(grand) AS gran,sum(transport) AS trans FROM invoice WHERE customer='$customer' AND (STR_TO_DATE(invoice.date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(invoice.date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultinv = mysqli_query($conn, $sqlinvbal);
        $rowinv = mysqli_fetch_assoc($resultinv);
        $invgran=$rowinv['gran'];
        $invtrans=$rowinv['trans'];
        $invtotbal=$invgran+$invtrans+$opening;
        
        $sqlrcp="SELECT sum(amount) AS rcpamount FROM reciept WHERE customer='$customer' AND (STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(reciept.clearance_date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) AND status= 'Cleared'";
        $resultrcp = mysqli_query($conn, $sqlrcp);
        $rowrcp = mysqli_fetch_assoc($resultrcp);
        $rcpamount=$rowrcp['rcpamount'];
        
        $sqlcdt="SELECT sum(total) AS cdtamount FROM credit_note WHERE customer='$customer' AND (STR_TO_DATE(credit_note.date,'%d/%m/%Y') >= STR_TO_DATE('$mindate', '%d/%m/%Y') AND STR_TO_DATE(credit_note.date,'%d/%m/%Y') < STR_TO_DATE('$fdate', '%d/%m/%Y')) ";
        $resultcdt= mysqli_query($conn, $sqlcdt);
        $rowcdt = mysqli_fetch_assoc($resultcdt);
        $cdtamount=$rowcdt['cdtamount'];
        
        $getamount=$rcpamount+$cdtamount;
        
        $balance=$invtotbal-$getamount;
        
           
         $sql ="SELECT * FROM (
                              (SELECT NULL as type, NULL as cdt, lpo AS lpo,invoice.site AS site,invoice.id,time,invoice.transport,NULL AS credit, invoice.grand, invoice.date AS adate FROM invoice WHERE customer='$customer' AND STR_TO_DATE(invoice.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         UNION ALL
                              (SELECT type, NULL as cdt, reciept.cheque_no AS lpo, reciept.bank AS site,reciept.id,time,NULL AS tp,reciept.amount, NULL AS credit, reciept.clearance_date AS adate FROM reciept WHERE customer='$customer' AND STR_TO_DATE(reciept.clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status= 'Cleared')
                         UNION ALL
                              (SELECT NULL as type, credit_note.id as cdt, NULL AS lpo,NULL AS site,credit_note.id,time,NULL AS tp,credit_note.total, NULL AS credit, credit_note.date AS adate FROM credit_note WHERE customer='$customer' AND STR_TO_DATE(credit_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                         ) results ORDER BY STR_TO_DATE(adate, '%d/%m/%Y'),time ASC";      
                      
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0)
		{ $sl=1;
                  $tquantity=0;
                  $bal=$balance;
        while($row = mysqli_fetch_assoc($result)) {

               $id=$row['id'];
               
               $credit=$row['credit'];
               
               $grand=$row['grand'];
               $tot=$row['cdt'];
               $type=$row['type'];
               
               if($credit!='')
                    {
                    if($type == 1){$cap='RCP ';$des='Opening';}
                    else{$cap='RCP ';$des='Receipt';}
                    $sqladt="SELECT sum(adjust) AS adt FROM reciept_invoice where reciept_id='$id'";
                    $resultadt = mysqli_query($conn, $sqladt);
                    $rowadt = mysqli_fetch_assoc($resultadt);
                    $adt=$rowadt['adt'];
                    $credit=$credit+$adt;
                    
                    }
               if($tot!='')
                    {
                    $cap='CDT ';$des='Credit Note';
                    }     
               if($grand!='')
                    {
                    $cap='INV ';$des='Invoice';
                    }
               
               $transport=$row['transport'];
               $grandtotal=$grand+$transport;
               
               $date=$row['adate'];
               $time=$row['time'];
               
               $lpo=$row['lpo'];
               
               $site=$row['site'];
                              $sqlsite="SELECT p_name from customer_site where id='$site'";
                              $querysite=mysqli_query($conn,$sqlsite);
                              $fetchsite=mysqli_fetch_array($querysite);
                              $site1=$fetchsite['p_name'];
                              
               $bank=$row['site'];
                              $sqlbank="SELECT name from banks where id='$bank'";
                              $querybank=mysqli_query($conn,$sqlbank);
                              $fetchbank=mysqli_fetch_array($querybank);
                              $bank1=$fetchbank['name'];                
               
          ?>
          <?php if($sl==1){ ?>
             <tr>
              <td colspan="5"><b></b></td>
              <td colspan="1"><b>Opening</b></td>
              <td colspan="2"><b></b></td>
              <td colspan="1" style="text-align: right;"><b><?php echo custom_money_format('%!i', $bal);?></b></td>
             </tr>
           <?php } ?>  
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <!--<td id="bdate"><?php echo $cust;?></td>-->
               <td id="bso"><?php echo $date;?></td>
               <td><?php echo $cap.$id;?></td>
               <td><?php
                 if($credit!='')
                    {
                      echo '<b>'.$bank1.'</b>';
                    }
                    else {
                      echo $site1;
                         }
                 ?></td> 
               <td><?php
                 if($credit!='')
                    {
                      echo '<b>'.$lpo.'</b>';
                    }
                    else {
                          echo $lpo;
                         }
                 ?></td> 
               <td><?php
                 if($type == 1)
                    {
                      echo '<b>'.$des.'</b>';
                    } else {
                           echo $des;
                           }
                 
                 ?></td>
               <!--<td id="bso"><?php echo $time;?></td>-->
               <td id="bso" style="text-align: right;"><?php echo custom_money_format('%!i', $grandtotal);?></td>
               <td id="bdate" style="text-align: right;"><?php echo custom_money_format('%!i', $credit);?></td>
               
               <td id="bso" style="text-align: right;"><?php
                   $ball=$bal+$grandtotal-$credit;
                   $bal=floor($ball * 100) / 100;
                   echo custom_money_format('%!i', $bal);
               ?></td>
               
               
          </tr>
		<?php
                $sl=$sl+1;  
		}
		}
		?>
        </tbody>
<!--              <tr>
              <td colspan="1"><b></b></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              </tr>-->
      </table>
 
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
        $sql="SELECT * FROM reciept where customer='$customer' AND status !='Cleared'"; 
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{ $sl=1;
        while($row = mysqli_fetch_assoc($result)) { 
            $id = $row['id'];    
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
                  <?php$sl1++; } } ?>
             </td>
             <td style="text-align: right;"><?php echo custom_money_format('%!i', $row['amount']);?></td>
             </tr>
             
                <?php $sl=$sl+1; } } ?>      
             
       </tbody>
 </table>
 
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>