<?php include "../config.php";?>
<?php
error_reporting(0);
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
    $date_sql = "STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2016', '%d/%m/%Y') AND STR_TO_DATE('$current', '%d/%m/%Y')";
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
        $staff_sql = "AND slman_id='$staff'";
}
if($cust_type != '') { $type_sql = "AND cust_type='$cust_type'";} else { $type_sql = '';}
if($period != '') { $period_sql = "AND period='$period'";} else { $period_sql = '';}
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
$sl = 1;
$tot_opening = 0;
$tot_cust_balance = 0;

// Fetch customer data in one query
$sql_cust = "
    SELECT cust_id, cust_name, slman_name AS slman, cust_type, period, IFNULL(cdt1 + cdt2, 0) AS credit_limit
    FROM `view_aging`
    WHERE $date_sql $staff_sql $type_sql $period_sql
    GROUP BY customer 
    ORDER BY period
";
$query_cust = mysqli_query($conn, $sql_cust);

// Prepare to collect all customer IDs
$cust_ids = [];
while ($fetch_cust = mysqli_fetch_array($query_cust)) {
    $cust_ids[] = $fetch_cust['cust_id'];
}

// Fetch opening balances in one query
$cust_id_list = implode(',', array_map('intval', $cust_ids));
$sql_opn = "
    SELECT customer, ROUND(SUM(grand) - (SUM(total_receipted) + SUM(total_credited)), 2) AS opn_balance
    FROM `view_aging`
    WHERE grand > 0 AND customer IN ($cust_id_list) AND STR_TO_DATE(`date`, '%d/%m/%Y') $cal_opn_date
    GROUP BY customer
";
$query_opn = mysqli_query($conn, $sql_opn);
$opening_balances = [];
while ($row = mysqli_fetch_assoc($query_opn)) {
    $opening_balances[$row['customer']] = $row['opn_balance'] ?? 0;
}

// Fetch monthly balances in one query
$balances = [];
for ($i = $duration - 1; $i >= 0; $i--) {
    $monthmy = date("m/Y", strtotime("-$i months", strtotime($month)));
    $sql = "
        SELECT customer, ROUND(SUM(grand) - (SUM(total_receipted) + SUM(total_credited)), 2) AS balance
        FROM `view_aging`
        WHERE grand > 0 AND customer IN ($cust_id_list) AND date LIKE '%$monthmy'
        GROUP BY customer
    ";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $balances[$row['customer']][$monthmy] = $row['balance'] ?? 0;
    }
}

// Reset the query pointer and process each customer
mysqli_data_seek($query_cust, 0);
while ($fetch_cust = mysqli_fetch_array($query_cust)) {
    $cust_name = $fetch_cust['cust_name'];
    $cust_id = $fetch_cust['cust_id'];
    $slman = $fetch_cust['slman'];
    $credit_limit = $fetch_cust['credit_limit'];
    $pterm1 = $fetch_cust['cust_type'];
    $pterm2 = $fetch_cust['period'];

    $open_bal = $opening_balances[$cust_id] ?? 0;
    $tot_opening += $open_bal;

    $cust_balance = 0;
    ?>
    <tr>
        <td></td>
        <td><?php echo $cust_name; ?></td>
        <td><?php echo $credit_limit; ?></td>
        <td><?php echo $pterm1; ?></td>
        <td><?php echo $pterm2; ?></td>
        <?php if ($staff == 'all') { ?>
            <td><?php echo $slman; ?></td>
        <?php } ?>
        <td style="text-align:right;"><?php echo custom_money_format('%!n', $open_bal); ?></td>
        <?php
        for ($i = $duration - 1; $i >= 0; $i--) {
            $monthmy = date("m/Y", strtotime("-$i months", strtotime($month)));
            $balance_value = $balances[$cust_id][$monthmy] ?? 0;
            $cust_balance += $balance_value;
            ?>
            <td style="text-align:right;"><?php
                if($balance_value == 0) {
                    echo '-';
                } else {
                    echo custom_money_format('%!n', $balance_value);
                }
                $total[$i] = ($total[$i] ?? 0) + $balance_value;
                ?></td>
        <?php } ?>
        <td style="text-align:right;">
            <?php 
            $total_balance = $cust_balance + $open_bal;
            echo custom_money_format('%!n', $total_balance);
            $tot_cust_balance += $total_balance;
            ?>
        </td>
    </tr>
    <?php
    $sl++;
}
?>
   
                            
             
        </tbody>
        <tfoot>
                <tr>
                    <td colspan="5"></td>
                    <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$tot_opening);?></b></td>
                <?php if($staff == 'all') { ?>    
                    <td colspan="1"></td>
                <?php }  for ($i = $duration - 1; $i >= 0; $i--) { ?>
                    <td colspan="1" style="text-align:right;"><b><?php echo custom_money_format("%!i",$total[$i]);?></b></td>
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