<?php include "../config.php";?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php 
if(!isset($_POST['print']))
{?>
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">-->
<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>/report/css/style.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">


	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js">
	</script>
<!--	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js">
	</script>-->
        <script type="text/javascript" language="javascript" src="js/pagination.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
<!--	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js">
	</script>-->
        <script type="text/javascript" language="javascript" src="js/pdf.js"></script>
        
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js">
	</script>
	
<style>
    table.dataTable tbody td {
    word-break: break-word;
    vertical-align: top;
    /*width:10%;*/
}
</style>	
	
<script type="text/javascript" class="init">
// $(document).ready(function() {
//     $('#report').DataTable( {
//         dom: 'Bfrtip',
//         buttons: [
//             'copy', 'csv', 'excel'
//         ],
//         "columnDefs": [
//             { "width": "20%", "targets": 1 }
//         ]
//     } );
// } );
$(document).ready(function() {
    var table = $('#report').DataTable( { 
        autoWidth: false, 
        paging: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel'
        ],
        "columnDefs": [
            { "width": "30%", "targets": 1 }
          ]
    } );
} );
</script>

<?php } ?>
<?php
if(isset($_POST))
{
    $staff = $_POST['staff'];
    $cust_type = $_POST['cust_type'];
    $period = $_POST['period'];
    $month = $_POST['month'];
}
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
<title>Mancon Block Factory</title>
<?php 
if(isset($_POST['print']))
{?>
<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
    font-size: 15px;
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
.width {width:15px;}
tr:nth-child(even){background-color: #f2f2f2}
</style>
<?php } ?>
<center><h1>MANCON BLOCK FACTORY.</h1>
<h2>CUSTOMER AGING REPORT [<?php echo $staff_name;?>]</h2></center>

<?php
    for ($i = 5; $i >= 0; $i--) 
    {
    $monthx[] = date("M Y", strtotime ( -$i .'month', strtotime ( $month ) )) ;
    }
?>
<!--<table id="report" class="display nowrap" cellspacing="0" width="100%">-->
<table id="report" class="display" cellspacing="0" width="100%">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Credit Limit
              </th>
              <th>
                   Payment<br>Terms
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
              <th>Total</th>
          </tr>
        </thead>
        <tbody>
             
    <?php
    $sl=1;
    $sql_cust = "SELECT cs.id as cust_id,cs.name as cust_name,cs_slmn.name as slman,
                 cs.cust_type as pterm1,cs.period as pterm2,cda.credit as cdt1,cda.credit1 as cdt2
                 FROM `invoice` i
                 INNER JOIN `customers` cs ON i.customer = cs.id
                 INNER JOIN `customers` cs_slmn ON i.rep = cs_slmn.id
                 LEFT JOIN `credit_application` cda ON i.customer = cda.company
                 WHERE i.status != 'Paid' $date_sql $staff_sql $type_sql $period_sql GROUP BY i.customer";
    $query_cust = mysqli_query($conn,$sql_cust);
    while($fetch_cust = mysqli_fetch_array($query_cust))
    {
        $cust_name = $fetch_cust['cust_name'];
        $cust_id = $fetch_cust['cust_id'];
        $slman = $fetch_cust['slman'];
        $credit_limit = $fetch_cust['cdt1']+$fetch_cust['cdt2'];
        $pterm = $fetch_cust['pterm1'].' '.$fetch_cust['pterm2'].' Days';
        

    for ($i = 5; $i >= 0; $i--) 
    {
    $monthmy = date("m/Y", strtotime ( -$i .'months', strtotime ( $month ) )) ;
    
        $sql = "SELECT sum(i.grand) as tgrand FROM invoice i
            WHERE i.grand > 0 AND i.status != 'Paid' AND i.customer='$cust_id' $staff_sql AND date LIKE '%$monthmy'";
            
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{
	while($row = mysqli_fetch_assoc($result)) 
    {
    $tgrand[] = custom_money_format('%!n',$row['tgrand']);
    $rep = $row['rep'];
    $cust_tgrand = $cust_tgrand+$row['tgrand'];
        
?>

<?php
} } }
?>

    <tr>
        <td><?php echo $sl;?></td>
        <td><?php echo $cust_name;?></td>
        <td><?php echo $credit_limit;?></td>
        <td><?php echo $pterm;?></td>
        <td><?php echo $slman;?></td>
        <td><?php echo $tgrand[0];?></td>
        <td><?php echo $tgrand[1];?></td>
        <td><?php echo $tgrand[2];?></td>
        <td><?php echo $tgrand[3];?></td>
        <td><?php echo $tgrand[4];?></td>
        <td><?php echo $tgrand[5];?></td>
        <td><?php echo custom_money_format('%!n',$cust_tgrand);?></td>
    </tr>
             
<?php $sl=$sl+1; $tgrand = []; $cust_tgrand = 0; } ?>    
             
             
        </tbody>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>