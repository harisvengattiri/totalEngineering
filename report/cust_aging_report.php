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
// $newdate = date("d/m/Y", strtotime ( '-1 month' , strtotime ( $date ) )) ;
// $a_date = "2009-11-23";
// echo date("Y-m-t", strtotime($a_date));

    $pre10 = date("d/m/Y", strtotime ( '-9 month' , strtotime ( $month ) )) ;
    $current = date("t/m/Y", strtotime ( '-0 month' , strtotime ( $month ) )) ;
    $date_sql = "AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$pre10', '%d/%m/%Y') AND STR_TO_DATE('$current', '%d/%m/%Y')";
    


if($staff == 'all'){
    $staff_sql = '';
    $staff_name = 'ALL';
} else {
        $sqlstaff="SELECT name from customers where id='$staff'";
        $querystaff=mysqli_query($conn,$sqlstaff);
        $fetchstaff=mysqli_fetch_array($querystaff);
        $staff_name=$fetchstaff['name'];
        $staff_sql = "AND i.rep='$staff'";
}
if($cust_type != '') { $type_sql = "AND cs.cust_type='$cust_type'";} else { $type_sql = '';}
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
    for ($i = 0; $i <= 10; $i++) 
    {
    // $monthx[] = date("M Y", strtotime( date( 'Y-m-01' )." -$i months"));
    $monthx[] = date("M Y", strtotime ( -$i .'month', strtotime ( $month ) )) ;
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
              <th>
                   Salesman
              </th>
              <th><?php echo $monthx[0];?></th>
              <th><?php echo $monthx[1];?></th>
              <th><?php echo $monthx[2];?></th>
              <th><?php echo $monthx[3];?></th>
              <th><?php echo $monthx[4];?></th>
              <th><?php echo $monthx[5];?></th>
              <th><?php echo $monthx[6];?></th>
              <th><?php echo $monthx[7];?></th>
              <th><?php echo $monthx[8];?></th>
              <th><?php echo $monthx[9];?></th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">

<?php
    $sl=1;
    $sql_cust = "SELECT cs.id as cust_id,cs.name as cust_name,cs_slmn.name as slman FROM `invoice` i
                 INNER JOIN `customers` cs ON i.customer = cs.id
                 INNER JOIN `customers` cs_slmn ON i.rep = cs_slmn.id
                 WHERE i.status != 'Paid' $date_sql $staff_sql $type_sql $period_sql GROUP BY i.customer";
    $query_cust = mysqli_query($conn,$sql_cust);
    while($fetch_cust = mysqli_fetch_array($query_cust))
    {
        $cust_name = $fetch_cust['cust_name'];
        $cust_id = $fetch_cust['cust_id'];
        $slman = $fetch_cust['slman'];

    for ($i = 0; $i <= 10; $i++) 
    {
    // $monthmy = date("m/Y", strtotime( date( 'Y-m-01' )." -$i months"));
    $monthmy = date("m/Y", strtotime ( -$i .'months', strtotime ( $month ) )) ;

    $sql = "SELECT sum(i.grand) as tgrand FROM invoice i
            WHERE i.grand > 0 AND i.status != 'Paid' AND i.customer='$cust_id' $staff_sql AND date LIKE '%$monthmy'";
            
    // echo $sql;
            
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{
	while($row = mysqli_fetch_assoc($result)) 
    {
    $tgrand[] = custom_money_format('%!n',$row['tgrand']);
    $rep = $row['rep'];
        
?>

<?php
} } }
?>

    <tr>
        <td><?php echo $sl;?></td>
        <td><?php echo $cust_name;?></td>
        <td><?php echo $slman;?></td>
        <td><?php echo $tgrand[0];?></td>
        <td><?php echo $tgrand[1];?></td>
        <td><?php echo $tgrand[2];?></td>
        <td><?php echo $tgrand[3];?></td>
        <td><?php echo $tgrand[4];?></td>
        <td><?php echo $tgrand[5];?></td>
        <td><?php echo $tgrand[6];?></td>
        <td><?php echo $tgrand[7];?></td>
        <td><?php echo $tgrand[8];?></td>
        <td><?php echo $tgrand[9];?></td>
    </tr>
             
<?php $sl=$sl+1; $tgrand = []; } ?>

       </tbody>
 </table>

      

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>