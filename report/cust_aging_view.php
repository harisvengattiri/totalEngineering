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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
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
<script type="text/javascript" class="init">
$(document).ready(function() {
    $('#report').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ]
    } );
} );
</script>
<?php } ?>
<?php
if(isset($_POST))
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
<h2>CUSTOMER AGING REPORT [<?php echo $customer_name;?>]</h2></center>

<table id="report" class="display nowrap" cellspacing="0" width="100%">
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
        <tbody>
             
	<?php
        // $sql = "SELECT invoice,cid,customer,grand,date,period,type FROM (
        //     (SELECT i.id as invoice,i.customer as cid,cs.name as customer,i.grand,i.date,cs.period,cs.cust_type as type FROM invoice i 
        //      LEFT JOIN reciept_invoice ri ON i.id=ri.invoice
        //      LEFT JOIN credit_note cn ON i.id=cn.invoice
        //      JOIN customers cs ON i.customer=cs.id
        //      WHERE i.grand > 0 AND ri.invoice IS NULL AND cn.invoice IS NULL $custsql1)
        //      UNION ALL
        //     (SELECT inv.id as invoice,inv.customer as cid,cs.name as customer,inv.grand,inv.date,cs.period,cs.cust_type as type FROM invoice inv
        //      JOIN reciept_invoice rinv ON inv.id=rinv.invoice
        //      JOIN reciept rpt ON rinv.reciept_id=rpt.id
        //      JOIN customers cs ON inv.customer=cs.id
        //      WHERE rpt.status!='Cleared' $custsql2)
        //     ) xxy GROUP BY invoice ORDER BY STR_TO_DATE(date,'%d/%m/%Y') ASC";
        
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
        if (mysqli_num_rows($result) > 0) {
            $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
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
                <td><?php if($over > 0) { echo sprintf("%04d",$over);}?></td>
            </tr>
		<?php
            $sl++;  
		}
		}
		?>
        </tbody>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>