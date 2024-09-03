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
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
if(!empty($fdate))
              {
              $date_sql = "WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('".$fdate."', '%d/%m/%Y') AND STR_TO_DATE('".$tdate."', '%d/%m/%Y')";
              }

$customer=$_POST["company"];
if(!empty($customer))
              {
              $customer_sql = "AND customer ='".$customer."'"; 
              }
$rep=$_POST["rep"];
if(!empty($rep))
              {
              $rep_sql = "AND salesrep ='".$rep."'"; 
              }           
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
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>QUOTATION REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>  
  
  
  
<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
               <th>
                  Sl No
              </th>
              <th>
                  Quto No:
              </th>
              <th>
                  Quto date
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Customer site
              </th>
              <th>
                  Sales Rep
              </th>
              <th>
                  Amount
              </th>
              <th>
                  Status
              </th>
                      
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
//          $sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' ORDER BY date";
        $sql = "SELECT * FROM quotation ".$date_sql.$customer_sql.$rep_sql."";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) { 
               $sl=1;
               $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
             $id=$row['id'];
             
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
             $site=$row["site"];
             $date=$row["date"];
             
             $rep1=$row["salesrep"];
               $sqlrep="SELECT name from customers where id='$rep1'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];

               $subtotal = $row['subtotal'];
               $subtotal = ($subtotal != NULL) ? $subtotal : 0;
               
        ?>
          <tr>
               <td><?php echo $sl;?></td>
               <td>QTN|<?php echo sprintf("%06d", $id);?></td>
               <td><?php echo $date;?></td>
               <td><?php echo $cust;?></td> 
               <td><?php echo $site;?></td>
               <td><?php echo $rep;?></td>
               <td><?php echo $subtotal*1.05;?></td>
               <td><?php echo $row['status'];?></td>
               
               
          </tr>
		<?php
                $sl=$sl+1;  
		}
		}
		?>
        </tbody>
<!--              <tr>
              <td colspan="6"><b></b></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php // echo $tquantity;?></b></td>
              </tr>-->
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>