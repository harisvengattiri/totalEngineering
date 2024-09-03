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
$fdate=$_POST["date1"];
$tdate=$_POST["date2"];
$mindate = '01/01/2015';
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
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>PDC DETAILS [Receivable]</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
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
                  Date
              </th>
              <th>
                  Due Date
              </th>
              <th>
                  Serial No.
              </th>
              <th style="width:25%;">
                  Bank Name
              </th>
              <th>
                  Cheque No
              </th>
              <th>
                  Status
              </th>
              <th id="hdate">
                  Amount
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
         $sql = "SELECT * FROM reciept WHERE
                 STR_TO_DATE(reciept.duedate, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status != 'Cleared'
                 ORDER BY duedate";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0)
		{
        $sl=1;
        $bal = 0;
        $totalcredit = 0;
        while($row = mysqli_fetch_assoc($result)) {
            $id=$row['id'];
            $date=$row['pdate'];
            
            $customer = $row['customer'];    
            $sqlcust="SELECT name from customers where id='$customer'";
            $querycust=mysqli_query($conn,$sqlcust);
            $fetchcust=mysqli_fetch_array($querycust);
            $cust=$fetchcust['name'];
                
            $bank=$row['inward'];
            $bnk=$row['inward'];
            $sqlbnk="SELECT name FROM customers where id='$bnk'"; 
            $resultbnk = mysqli_query($conn, $sqlbnk);
            $rowbnk = mysqli_fetch_assoc($resultbnk);
            $bank_name = $rowbnk['name'];
               
            $idet='RPT|'.sprintf("%04d",$id);
          ?>
          <tr>
               <td id="bsl"><?php echo $sl;?></td>
               <td><?php echo $cust;?></td>
               <td><?php echo $date;?></td>
               <td><?php echo $row['duedate'];?></td>
               <td><?php echo $idet;?></td>
               <td><?php echo $bank_name;?></td>
               <td><?php echo $row['cheque_no'];?></td>
               <td><?php echo $row['status'];?></td>
               <td><?php echo $row['amount'];?></td>
          </tr>
		<?php
                $sl=$sl+1;
                $row['amount'] = ($row['amount'] != NULL) ? $row['amount'] : 0;
                $totalcredit=$totalcredit+$row['amount'];
		} } ?>
             
         <?php if(mysqli_num_rows($result) > 0) { ?>    
          <tr>
              <td colspan="6"></td>
              <td style="text-align: right;"><b>Total</b></td>
              <td style="text-align: right;"><b></b></td>
              <td style="text-align: right;"><b><?php echo custom_money_format('%!i',$totalcredit);?></b></td>
         </tr>
         <?php } ?>
          
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>