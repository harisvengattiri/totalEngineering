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
<h2 style="text-align:center;margin-bottom:1px;">PDC STATEMENT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <!--<span style="font-size:15px;">Account: <?php // echo $cat_name;?><br>Sub Account: <?php // echo $sub_cat_name;?></span>-->
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
              <th>
                  Customer
              </th>
              <th>
                  Receipt
              </th>
              <th>
                  Date
              </th>
              <th>
                  Due Date
              </th>
              <th>
                  Cheque Number
              </th>
              <th style="text-align: right;">
                  Receipted Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
		$sl=1;$tot_bal=0;
            
        $sqlPdc = "SELECT amount AS pdcAmount,pdate AS date,duedate,id AS receipt,customer AS cust,cheque_no
                  FROM `reciept` WHERE
                  STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                  AND STR_TO_DATE(`clearance_date`, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                  ORDER BY id";
        $queryPdc = mysqli_query($conn,$sqlPdc);
        while($resultPdc = mysqli_fetch_array($queryPdc)) {
            
            $receipt = $resultPdc['receipt'];
            $date = $resultPdc['date'];
            $duedate = $resultPdc['duedate'];
            $pdcInHand = $resultPdc['pdcAmount'];
            $cheque_no = $resultPdc['cheque_no'];
            
            $cust = $resultPdc['cust'];
                    $sql_cust = "SELECT name FROM `customers` WHERE id='$cust'";
                    $query_cust = mysqli_query($conn,$sql_cust);
                    $result_cust = mysqli_fetch_array($query_cust);
                    $customer = $result_cust['name'];
    
        $tot_bal = $tot_bal+$pdcInHand;

        ?>
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td><?php echo $customer;?></td>
               <td><?php echo $receipt;?></td>
               <td id="bso"><?php echo $date;?></td>
               <td id="bso"><?php echo $duedate;?></td>
               <td><?php echo $cheque_no;?></td>
               <td id="bso" style="text-align: right;"><?php echo custom_money_format('%!i', $pdcInHand);?></td>
          </tr>
          
		<?php
            $sl=$sl+1; }
        ?>
        
        <tr>
            <td colspan="6"></td>
            <td id="bdate" style="text-align: right;"><b><?php echo custom_money_format('%!i', $tot_bal);?></b></td>
        </tr>
        
    </tbody>
</table>
 
 
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>