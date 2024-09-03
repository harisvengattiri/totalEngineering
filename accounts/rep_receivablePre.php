<?php
  include "../config.php";
  error_reporting(0);
  session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(!empty($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$mindate = '01/01/2015';
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

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;">RECEIVABLE STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <!--<span style="font-size:15px;">Account: <?php // echo $cat_name;?><br>Sub Account: <?php // echo $sub_cat_name;?></span>-->
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php if ($fdate==''){echo "Since Inception";} else{echo $fdate;}?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>
 
 
 
    <table id="tbl1" align="center" style="width:80%;">
        <thead>
          <tr>
              <th id="hsl">
                  Sl No
              </th>
              <th>
                  Invoice
              </th>
              <th>
                  Date
              </th>
              <th style="text-align: right;">
                  Invoiced Amount
              </th>
              <th style="text-align: right;">
                  Received Amount
              </th>
              <th style="text-align: right;">
                  Balance
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
		    $sl=1;$tot_bal=0;
		    
// 	OLD METHOD	    
	//  $sqlReceivables = "SELECT `invoice`,`date`,`amount` FROM `additionalRcp` WHERE `section` = 'INV'
    //             AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    //         $queryReceivables = mysqli_query($conn,$sqlReceivables);
    //         while($resultReceivables = mysqli_fetch_array($queryReceivables)) {

    //             $invoice = $resultReceivables['invoice'];
    //             $date = $resultReceivables['date'];
    //             $invoicedAmount = $resultReceivables['amount'];
    //             $invoicedAmount = ($invoicedAmount != NULL) ? $invoicedAmount : 0;
                    
    //             $sqlReceipt = "SELECT sum(amount) AS receiptedAmount FROM `additionalRcp` WHERE `section` != 'INV' AND `invoice` = '$invoice'
    //                           AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    //             $queryReceipt = mysqli_query($conn,$sqlReceipt);
    //             $resultReceipt = mysqli_fetch_array($queryReceipt);
    //             $receiptedAmount = $resultReceipt['receiptedAmount'];
    //             $receiptedAmount = ($receiptedAmount != NULL) ? $receiptedAmount : 0;
                
    //             $bal = $invoicedAmount+$receiptedAmount;
    //             $bal = ($bal != NULL) ? $bal : 0;
    //             $tot_bal = $tot_bal+$bal;
                
// NEW METHOD                
    $sqlReceivables = "
    SELECT
        a1.`invoice`,a1.`date`,
        SUM(CASE WHEN a2.`section` = 'INV' THEN a2.`amount` ELSE 0 END) AS invoicedAmount,
        SUM(CASE WHEN a2.`section` != 'INV' THEN a2.`amount` ELSE 0 END) AS receiptedAmount
    FROM
        `additionalRcp` a1
    LEFT JOIN
        `additionalRcp` a2 ON a1.`invoice` = a2.`invoice`
    WHERE
        a1.`section` = 'INV'
        AND STR_TO_DATE(a1.`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
     	AND STR_TO_DATE(a2.`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
    GROUP BY
        a1.`invoice`
     ";
    $queryReceivables = mysqli_query($conn,$sqlReceivables);
    while($resultReceivables = mysqli_fetch_array($queryReceivables)) {            
    
        $invoice = $resultReceivables['invoice'];
        $date = $resultReceivables['date'];
        $invoicedAmount = $resultReceivables['invoicedAmount']; 
        $receiptedAmount = $resultReceivables['receiptedAmount']; 
        $bal = $invoicedAmount+$receiptedAmount;
        $tot_bal = $tot_bal+$bal;
    if($bal != 0) {
        ?>
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td><?php echo $invoice;?></td>
               <td id="bso"><?php echo $date;?></td>
               <td id="bso" style="text-align: right;"><?php echo custom_money_format('%!i', $invoicedAmount);?></td>
               <td id="bdate" style="text-align: right;"><?php echo custom_money_format('%!i', -$receiptedAmount);?></td>
               <td id="bdate" style="text-align: right;"><?php echo custom_money_format('%!i', $bal);?></td>
          </tr>
          
		<?php
            $sl=$sl+1;} }
        ?>
        
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="bdate" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tot_bal);?></b></td>
        </tr>
        
    </tbody>
</table>
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>