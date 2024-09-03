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
    $staff = $_POST['staff'];
    $cust_type = $_POST['cust_type'];
    $period = $_POST['period'];
    $month = $_POST['month'];
}
// $a1 = date('Y/m/d', strtotime("first day of -3 month"));
// $date = "08-03-2020";
// $newdate = date("d/m/Y", strtotime ( '-1 month' , strtotime ( $date ) )) ;

    $pre1 = date("m/Y", strtotime ( '-1 month' , strtotime ( $month ) )) ;
    $pre2 = date("m/Y", strtotime ( '-2 month' , strtotime ( $month ) )) ;
    $pre3 = date("m/Y", strtotime ( '-3 month' , strtotime ( $month ) )) ;
    $pre4 = date("m/Y", strtotime ( '-4 month' , strtotime ( $month ) )) ;
    $pre5 = date("m/Y", strtotime ( '-5 month' , strtotime ( $month ) )) ;
    $pre6 = date("m/Y", strtotime ( '-6 month' , strtotime ( $month ) )) ;
    $pre7 = date("m/Y", strtotime ( '-7 month' , strtotime ( $month ) )) ;
    $pre8 = date("m/Y", strtotime ( '-8 month' , strtotime ( $month ) )) ;
    $pre9 = date("m/Y", strtotime ( '-9 month' , strtotime ( $month ) )) ;
    $pre10 = date("m/Y", strtotime ( '-10 month' , strtotime ( $month ) )) ;



if($staff == 'all'){
    $custsql1 = '';
    $custsql2 = '';
    $staff_name = 'ALL';
} else {
        $sqlstaff="SELECT name from customers where id='$staff'";
        $querystaff=mysqli_query($conn,$sqlstaff);
        $fetchstaff=mysqli_fetch_array($querystaff);
        $staff_name=$fetchstaff['name'];
    
    $staff_sql1 = "AND i.rep='$staff'";
    $staff_sql2 = "AND inv.rep='$staff'";
}
if($cust_type != '') { $type_sql = "WHERE cs.cust_type='$cust_type'";} else { $type_sql = '';}
if($period != '') { $period_sql = "AND cs.period='$period'";} else { $period_sql = '';}

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
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Salesman: <?php echo $staff_name;?></span></td>
     </tr>     
</table>



<?php
    for ($i = 0; $i <= 6; $i++) 
    {
    $monthx[] = date("M Y", strtotime( date( 'Y-m-01' )." -$i months"));
    }
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
              <th><?php echo $monthx[0];?></th>
              <th><?php echo $monthx[1];?></th>
              <th><?php echo $monthx[2];?></th>
              <th><?php echo $monthx[3];?></th>
              <th><?php echo $monthx[4];?></th>
              <th><?php echo $monthx[5];?></th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">

<?php
    // $sql = "SELECT invoice,cid,cs.name as customer,grand,date,cs.period,cs.cust_type as type FROM (
    //             (SELECT i.id as invoice,i.customer as cid,i.grand,i.date FROM invoice i
    //              WHERE i.grand > 0 AND i.status != 'Paid' $staff_sql1)
    //              UNION ALL
    //             (SELECT inv.id as invoice,inv.customer as cid,inv.grand,inv.date FROM invoice inv
    //              JOIN reciept_invoice rinv ON inv.id=rinv.invoice
    //              JOIN reciept rpt ON rinv.reciept_id=rpt.id
    //              WHERE inv.grand > 0 AND inv.status = 'Paid' AND rpt.status!='Cleared' $staff_sql2)
    //         ) results
    //         JOIN customers cs ON results.cid=cs.id
    //         WHERE cs.period='$period' AND cs.cust_type='$cust_type' GROUP BY invoice ORDER BY STR_TO_DATE(date,'%d/%m/%Y') ASC";

    $sl=1;
    // $sql_cust = "SELECT id,name FROM `customers` WHERE type='Company' AND id>2280 AND id<2290";
    // $sql_cust = "SELECT id,name FROM `customers` WHERE id='2286' OR id='2104'";
    
    $sql_cust = "SELECT cs.id as cust_id,cs.name as cust_name FROM `invoice` i
                 INNER JOIN `customers` cs ON i.customer = cs.id
                 WHERE i.status != 'Paid' GROUP BY i.customer";
    $query_cust = mysqli_query($conn,$sql_cust);
    while($fetch_cust = mysqli_fetch_array($query_cust))
    {
        $cust_name = $fetch_cust['cust_name'];
        $cust_id = $fetch_cust['cust_id'];

    for ($i = 0; $i <= 6; $i++) 
    {
    // $monthx[] = date("M Y", strtotime( date( 'Y-m-01' )." -$i months"));
    $monthmy = date("m/Y", strtotime( date( 'Y-m-01' )." -$i months"));

    $sql = "SELECT i.id as invoice,i.customer as cid,sum(i.grand) as tgrand,i.date FROM invoice i
            WHERE i.grand > 0 AND i.status != 'Paid' AND i.customer='$cust_id' AND date LIKE '%$monthmy'";
            
    // echo $sql;
            
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{
	while($row = mysqli_fetch_assoc($result)) 
    {
    $tgrand[] = custom_money_format('%!n',$row['tgrand']);
        
?>

<?php
} } }
?>

    <tr>
        <td><?php echo $sl;?></td>
        <td><?php echo $cust_name;?></td>
        <td><?php echo $tgrand[0];?></td>
        <td><?php echo $tgrand[1];?></td>
        <td><?php echo $tgrand[2];?></td>
        <td><?php echo $tgrand[3];?></td>
        <td><?php echo $tgrand[4];?></td>
        <td><?php echo $tgrand[5];?></td>
    </tr>
             
<?php $sl=$sl+1; $tgrand = []; } ?>

       </tbody>
 </table>

      

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>