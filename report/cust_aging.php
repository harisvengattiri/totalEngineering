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
    $customer = $_POST['customer'];
}
if($customer == 'all'){
    $custsql1 = '';
    $custsql2 = '';
    $customer_name = 'ALL';
}else{
        $sqlcust="SELECT name from customers where id='$customer'";
        $querycust=mysqli_query($conn,$sqlcust);
        $fetchcust=mysqli_fetch_array($querycust);
        $customer_name=$fetchcust['name'];
    
    $custsql1 = "AND i.customer='$customer'";
    $custsql2 = "AND inv.customer='$customer'";
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
<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>CUSTOMER AGING REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Customer: <?php echo $customer_name;?></span></td>
     </tr>     
</table>
      
<?php
    // $sql = "SELECT invoice,cid,customer,grand,date,period FROM (
    //         (SELECT i.id as invoice,i.customer as cid,cs.name as customer,i.grand,i.date,ca.period FROM invoice i 
    //          LEFT JOIN reciept_invoice ri ON i.id=ri.invoice
    //          JOIN customers cs ON i.customer=cs.id 
    //          LEFT JOIN credit_application ca ON i.customer=ca.company
    //          WHERE i.grand !='' AND ri.invoice IS NULL $custsql1)
    //          UNION ALL
    //         (SELECT inv.id as invoice,inv.customer as cid,cs.name as customer,inv.grand,inv.date,ca.period FROM invoice inv
    //          JOIN reciept_invoice rinv ON inv.id=rinv.invoice
    //          JOIN reciept rpt ON rinv.reciept_id=rpt.id
    //          JOIN customers cs ON inv.customer=cs.id 
    //          LEFT JOIN credit_application ca ON inv.customer=ca.company
    //          WHERE rpt.status!='Cleared' $custsql2)
    //         ) xxy GROUP BY invoice ORDER BY STR_TO_DATE(date,'%d/%m/%Y') ASC";
    
            
    // $sql = "SELECT invoice,cid,customer,grand,date,period,type FROM (
    //         (SELECT i.id as invoice,i.customer as cid,cs.name as customer,i.grand,i.date,cs.period,cs.cust_type as type FROM invoice i 
    //          LEFT JOIN reciept_invoice ri ON i.id=ri.invoice
    //          LEFT JOIN credit_note cn ON i.id=cn.invoice
    //          JOIN customers cs ON i.customer=cs.id
    //          WHERE i.grand > 0 AND ri.invoice IS NULL AND cn.invoice IS NULL $custsql1)
    //          UNION ALL
    //         (SELECT inv.id as invoice,inv.customer as cid,cs.name as customer,inv.grand,inv.date,cs.period,cs.cust_type as type FROM invoice inv
    //          JOIN reciept_invoice rinv ON inv.id=rinv.invoice
    //          JOIN reciept rpt ON rinv.reciept_id=rpt.id
    //          JOIN customers cs ON inv.customer=cs.id
    //          WHERE rpt.status!='Cleared' $custsql2)
    //         ) xxy GROUP BY invoice ORDER BY STR_TO_DATE(date,'%d/%m/%Y') ASC";
    
    $sql = "SELECT invoice,cid,cs.name as customer,grand,date,cs.period,cs.cust_type as type FROM (
                (SELECT i.id as invoice,i.customer as cid,i.grand,i.date FROM invoice i
                 WHERE i.grand > 0 AND i.status != 'Paid' $custsql1)
                 UNION ALL
                (SELECT inv.id as invoice,inv.customer as cid,inv.grand,inv.date FROM invoice inv
                 JOIN reciept_invoice rinv ON inv.id=rinv.invoice
                 JOIN reciept rpt ON rinv.reciept_id=rpt.id
                 WHERE inv.grand > 0 AND inv.status = 'Paid' AND rpt.status!='Cleared' $custsql2)
            ) results
            JOIN customers cs ON results.cid=cs.id
            GROUP BY invoice ORDER BY STR_TO_DATE(date,'%d/%m/%Y') ASC";
            
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
                   Date
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Status
              </th>
              <th>
                   Invoice
              </th>
              <th>
                   Amount
              </th>
              <th>
                   Credit Period
              </th>
              <th>
                   Over Credit Period<br> [Days]
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) 
        {
            
        //   $cust = $row['customer']; 
        //     $sqlcust="SELECT name from customers where id='$cust'";
        //     $querycust=mysqli_query($conn,$sqlcust);
        //     $fetchcust=mysqli_fetch_array($querycust);
        //     $customer=$fetchcust['name'];
            
            
            // $sqlsite="SELECT p_name from customer_site where id='$site'";
            // $querysite=mysqli_query($conn,$sqlsite);
            // $fetchsite=mysqli_fetch_array($querysite);
            // $cust_site=$fetchsite['p_name'];
            
        $date = $row['date'];
        $credit_period = $row['period'];
        $tdays = strtotime(date('Y-m-d'))-strtotime(str_replace('/','-',$date));
        $days = round($tdays / (60 * 60 * 24));
        $over = $days-$credit_period;
         
        $row['grand'] = ($row['grand'] != NULL) ? $row['grand'] : 0;
        ?>     
            
            <tr>
                <td><?php echo $sl;?></td>
                <td><?php echo $date;?></td>
                <td><?php echo $row['customer'];?></td>
                <td><?php echo $row['type'];?></td>
                <td><?php echo $row['invoice'];?></td>
                <td style="text-align:right"><?php echo custom_money_format("%!i",$row['grand']);?></td>
                <td><?php echo $credit_period;?></td>
                <td><?php if($over > 0) { echo $over;}?></td>
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