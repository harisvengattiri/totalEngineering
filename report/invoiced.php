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
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
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
<h2>INVOICED REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>

<table id="report" class="display nowrap" cellspacing="0" width="100%">
        <thead>
          <tr>
              <th>
                   Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  Invoice
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Customer Site
              </th>
              <th>
                  Purchase Order
              </th>
              <th>
                  Quantity
              </th>
               <th>
                  Invoice Amount
              </th>
              
              
          </tr>
        </thead>
        <tbody>
             
	<?php
        $sql = "SELECT * FROM invoice WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
        $result = mysqli_query($conn, $sql);
        $sl=1;
        if (mysqli_num_rows($result) > 0) {
             $tqnt=0;
             $tgrand=0;
        while($row = mysqli_fetch_assoc($result)) {
        ?>  
          <tr>
             <td><?php echo $sl;?></td>
             <td><?php echo $row["date"];?></td>
             <td><?php echo sprintf('%06d',$row['id']);?></td>
             <td><?php
                  $customer=$row['customer'];
                  $sqlcust="SELECT name from customers where id='$customer'";
                  $querycust=mysqli_query($conn,$sqlcust);
                  $fetchcust=mysqli_fetch_array($querycust);
                  echo $cust=$fetchcust['name'];
             ?></td>
             <td><?php
             $site=$row['site']; 
             $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               echo $site1=$fetchsite['p_name'];?></td>
             
             <td>PO|<?php echo $or=$row['o_r'];?></td>
             <td><?php
                    $sqlqnt="SELECT sum(quantity) AS qnt FROM order_item where o_r='$or'";
                    $queryqnt=mysqli_query($conn,$sqlqnt);
                    $fetchqnt=mysqli_fetch_array($queryqnt);
                    echo $qnt=$fetchqnt['qnt'];
                    $tqnt=$tqnt+$qnt;
             ?></td>
             <td><?php
               echo $row['grand'];
               $tgrand=$tgrand+$row['grand'];
             ?></td>
          </tr>
		<?php
                $sl++;  
		}
		}
		?>
        </tbody>
        <tfoot>
        <tr>
               <td colspan="6"></td>
               <td><b><?php echo $tqnt;?></b></td>
               <td><b><?php echo $tgrand;?></b><td>
        </tr>
        </tfoot>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>