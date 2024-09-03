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
        // buttons: ['copy', 'csv', 'excel'],
        // buttons: ['copy', 'csv', 'excel', 'pdf'],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                $("td:first", nRow).html(iDisplayIndex +1);
               return nRow;
        },
        buttons: [
            'copy', 'csv', 'excel',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4'
            }
        ],
        order: [[4, 'asc']],
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
    $duration = $_POST['duration'];
}
    $pre10 = date("d/m/Y", strtotime ( '-7 month' , strtotime ( $month ) )) ;
    $current = date("t/m/Y", strtotime ( '-0 month' , strtotime ( $month ) )) ;
    $date_sql = "AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$pre10', '%d/%m/%Y') AND STR_TO_DATE('$current', '%d/%m/%Y')";
    
    $calc_pre_month = date("t/m/Y", strtotime ( -$duration . 'month' , strtotime ( $month ) )) ;
    $cal_opn_date = "BETWEEN STR_TO_DATE('01/01/2016', '%d/%m/%Y') AND STR_TO_DATE('$calc_pre_month', '%d/%m/%Y')";


if($staff == 'all'){
    $staff_sql = '';
    $staff_name = 'ALL';
} else {
        $sqlstaff="SELECT name from customers where id='$staff'";
        $querystaff=mysqli_query($conn,$sqlstaff);
        $fetchstaff=mysqli_fetch_array($querystaff);
        $staff_name=$fetchstaff['name'];
        $staff_sql = "AND i.rep='$staff'";
        $staff_sql1 = "AND invoice.rep='$staff'";
        $staff_sql2 = "AND invc.rep='$staff'";
}
if($cust_type != '') { $type_sql = "AND cs.cust_type='$cust_type'";} else { $type_sql = '';}
if($period != '') { $period_sql = "AND cs.period='$period'";} else { $period_sql = '';}
?>
<title>CUSTOMER AGING REPORT&nbsp;&nbsp;[ <?php echo $staff_name;?> ]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <?php echo date("d/m/Y");?> ]</title>
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
<h2>CUSTOMER AGING REPORT [<?php echo $staff_name;?>]<span style="font-size:20px;"> [<?php echo date("d/m/Y");?>]</span></h2>
</center>

<?php
    for ($i = $duration-1; $i >= 0; $i--) 
    {
    $monthx[] = date("M Y", strtotime ( -$i .'month', strtotime ( $month ) )) ;
    }
?>
<!--<table id="report" class="display nowrap" cellspacing="0" width="100%">-->
<table id="report" class="display nowrap" cellspacing="0" width="100%">
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
                  Days
              </th>
              <?php if($staff == 'all') { ?>
              <th>
                   Salesman
              </th>
              <?php } ?>
              <th>
                  Opening
              </th>
              <?php for($i=0;$i<$duration;$i++) { ?>
              <th><?php echo $monthx[$i];?></th>
              <?php } ?>
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
                 WHERE i.status != 'Paid' $date_sql $staff_sql $type_sql $period_sql GROUP BY i.customer ORDER BY cs.period";
    $query_cust = mysqli_query($conn,$sql_cust);

    while($fetch_cust = mysqli_fetch_array($query_cust))
    {
        $cust_name = $fetch_cust['cust_name'];
        $cust_id = $fetch_cust['cust_id'];
        $slman = $fetch_cust['slman'];
        $credit_limit = $fetch_cust['cdt1']+$fetch_cust['cdt2'];
        // $pterm = $fetch_cust['pterm1'].' '.$fetch_cust['pterm2'].' Days';
        $pterm1 = $fetch_cust['pterm1'];
        $pterm2 = $fetch_cust['pterm2'];
        
        
        $sql_opn = "
            SELECT (total-(received+credit)) as opn_balance FROM (
                SELECT ROUND(sum(`total`),2) as total,ROUND(sum(`received`),2) as received,ROUND(sum(`credit`),2) as credit FROM (
                    (SELECT sum(i.grand) as total, 0 as received, 0 as credit FROM invoice i
                    WHERE i.grand > 0 AND i.status = '' AND i.customer='$cust_id' $staff_sql AND STR_TO_DATE(i.`date`, '%d/%m/%Y') $cal_opn_date)
                    UNION ALL
                    (SELECT grand as grand,total10 as received, total2 as credit FROM invoice
                        LEFT JOIN (SELECT invoice, sum(total) as total10 FROM reciept_invoice GROUP BY invoice) as t1 ON invoice.id=t1.invoice
                        LEFT JOIN (SELECT invoice, sum(total) as total2 FROM credit_note GROUP BY invoice) as t2 ON invoice.id=t2.invoice
                    WHERE invoice.grand > 0 AND invoice.status = 'Partially' AND invoice.customer='$cust_id' $staff_sql1 AND STR_TO_DATE(invoice.`date`, '%d/%m/%Y') $cal_opn_date)
                    ) result
                    ) results
                    ";
        $query_opn = mysqli_query($conn,$sql_opn);
        $result_opn = mysqli_fetch_array($query_opn);
        $open_bal = $result_opn['opn_balance'];
        

    for ($i = $duration-1; $i >= 0; $i--) 
    {
    $monthmy = date("m/Y", strtotime ( -$i .'months', strtotime ( $month ) )) ;
    
            
            
        $sql = "
            SELECT (total-(received+credit)) as balance FROM (
                SELECT ROUND(sum(`total`),2) as total,ROUND(sum(`received`),2) as received,ROUND(sum(`credit`),2) as credit FROM (
                    (SELECT sum(i.grand) as total, 0 as received, 0 as credit FROM invoice i
                    WHERE i.grand > 0 AND i.status = '' AND i.customer='$cust_id' $staff_sql AND i.date LIKE '%$monthmy')
                    UNION ALL
                    (SELECT grand as grand,total10 as received, total2 as credit FROM invoice
                        LEFT JOIN (SELECT invoice, sum(total) as total10 FROM reciept_invoice GROUP BY invoice) as t1 ON invoice.id=t1.invoice
                        LEFT JOIN (SELECT invoice, sum(total) as total2 FROM credit_note GROUP BY invoice) as t2 ON invoice.id=t2.invoice
                    WHERE invoice.grand > 0 AND invoice.status = 'Partially' AND invoice.customer='$cust_id' $staff_sql1 AND invoice.date LIKE '%$monthmy')
                    ) result
                    ) results 
                    
                    ";
                    
// (SELECT grand,total1,total2 FROM invoice
// LEFT JOIN (SELECT invoice, sum(total) as total1 FROM reciept_invoice GROUP BY invoice) as t1 ON invoice.id=t1.invoice
// LEFT JOIN (SELECT invoice, sum(total) as total2 FROM credit_note GROUP BY invoice) as t2 ON invoice.id=t2.invoice
// WHERE invoice.grand > 0 AND invoice.status = 'Partially' AND invoice.customer='1449' AND invoice.date LIKE '%08/2022')
    
        // $sql = "
        //     SELECT (total-received) as balance FROM (
        //         SELECT ROUND(sum(`total`),2) as total,ROUND(sum(`received`),2) as received FROM (
        //             (SELECT sum(i.grand) as total, 0 as received FROM invoice i
        //             WHERE i.grand > 0 AND i.status = '' AND i.customer='$cust_id' $staff_sql AND i.date LIKE '%$monthmy')
        //             UNION ALL
        //             (SELECT inv.grand as total, sum(ri.total) as received FROM invoice inv
        //             LEFT JOIN reciept_invoice ri ON inv.id = ri.invoice 
        //             WHERE inv.grand > 0 AND inv.status = 'Partially' AND inv.customer='$cust_id' $staff_sql1 AND inv.date LIKE '%$monthmy' GROUP BY inv.id)
        //             UNION ALL
        //             (SELECT invc.grand as total, sum(cd.total) as received FROM invoice invc
        //             INNER JOIN credit_note cd ON invc.id = cd.invoice 
        //             WHERE invc.grand > 0 AND invc.status = 'Partially' AND invc.customer='$cust_id' $staff_sql2 AND invc.date LIKE '%$monthmy')
        //             ) result
        //             ) results 
                    
        //             ";
        
        
        
        // echo $sql;
            
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{
	while($row = mysqli_fetch_assoc($result)) 
    {
    $balance[] = $row['balance'];
    // $balance[] = custom_money_format('%!n',$row['balance']);
    $rep = $row['rep'];
    $cust_balance = $cust_balance+$row['balance'];
    $tot_cust_balance = $tot_cust_balance+$row['balance'];
    
    // $tot_cust_balance = $tot_cust_balance+$cust_balance;

?>

<?php
} }

    // for($j=0;$j<$duration;$j++) {
    // $t_balance[$j][$i] = $balance[$j];
    // }
        
}

// $t_balance0 = $t_balance0+$balance[0];
// $t_balance1 = $t_balance1+$balance[1];
// $t_bal = array()


?>

    <tr>
        <td></td>
        <td><?php echo $cust_name;?></td>
        <td><?php echo $credit_limit;?></td>
        <td><?php echo $pterm1;?></td>
        <td><?php echo $pterm2;?></td>
        <?php if($staff == 'all') { ?>
        <td><?php echo $slman;?></td>
        <?php } ?>
        <!--<td style="text-align:right;"><?php echo custom_money_format('%!n',0);?></td>-->
        <td style="text-align:right;"><?php echo custom_money_format('%!n',$open_bal);?></td>
        <?php for($i=0;$i<$duration;$i++) { ?>
        <td style="text-align:right;"><?php if($balance[$i] != NULL){
            $t_balance[$i] = $balance[$i];
            $total[$i]=$total[$i]+$balance[$i];
            echo custom_money_format('%!n',$balance[$i]);
        } else { echo '-';}
        ?></td>
        <?php } ?>
        
        <td style="text-align:right;"><?php echo custom_money_format('%!n',$cust_balance+$open_bal);?></td>
    </tr>
             
    <?php
        $sl=$sl+1; $balance = []; $cust_balance = 0; $open_bal = 0; }
    ?>    
                            
             
        </tbody>
        <tfoot>
                <tr>
                    <td colspan="6"></td>
                <?php if($staff == 'all') { ?>    
                    <td colspan="1"></td>
                <?php }  for($i=0;$i<$duration;$i++) { ?>
                    <td colspan="1" style="text-align:left;"><b><?php echo custom_money_format("%!i",$total[$i]);?></b></td>
                <?php } ?>
                    <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$tot_cust_balance);?></b></td>
                </tr>
        </tfoot>
         
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>