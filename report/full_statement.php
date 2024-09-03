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
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js">
	</script>
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
<title> <?php echo $title." [".$fdate." - ".$tdate."]";?></title>
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

tr:nth-child(even){background-color: #f2f2f2}
</style>
<?php } ?>
<center><h1>WadialRaha Air Conditioning Contracting EST.</h1>
<h2>FULL REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>
<table id="report" class="display nowrap" cellspacing="0" width="100%">
        <thead>
          <tr>
              <th>
                  No
              </th>
              <th>
                  Code
              </th>
	      <th>
                  Date
              </th>
              <th>
                  From
              </th>
              <th>
                  To
              </th>
              <th>
                  Cr
              </th>
	      <th>
                  Dr
              </th>
	      <th>
                  Balance
              </th>
              <th>
                  Notes
              </th>
          </tr>
        </thead>
        <tbody>
		
		<?php
                $total=0;
                $totalcr=0;
                $totaldr=0;
                $staff=$_GET['staff'];
		$sql = "SELECT * FROM (
(SELECT CONCAT('ITFC', id) AS id, fromid, toid, amount, date, notes FROM internal_transfers where id=0)
UNION ALL (SELECT CONCAT('PRC', id) AS id, purchaser, shop, amount, date, notes FROM purchases)
UNION ALL (SELECT CONCAT('OXP', id) AS id, purchaser, shop, amount, date, CONCAT(particulars,' - ',notes) as notes FROM office_expenses)
UNION ALL (SELECT CONCAT('VXP', id) AS id, purchaser, shop, amount, date, CONCAT(purpose,' - ',notes) as notes  FROM vehicle_expenses)
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments)
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, CONCAT(status,' ',notes) AS notes FROM work_invoices where paid>0)
UNION ALL (SELECT CONCAT('ITFD', id) AS id, fromid, toid, amount, date, notes FROM internal_transfers where id=0)
)results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $idet=$row["id"];
              $purchase_customer="";
              $vehicle_name="";
              if (substr($idet, 0, 4) === 'ITFC')
              {
              $cr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 4));
              $dr=0;
              }


              elseif (substr($idet, 0, 3) === 'PRC')
              {
              $cr=0;
              $dr=$row["amount"];
              $prcid=substr($idet, 3);
              $sql2 = "SELECT * FROM purchases where id=$prcid";
              $result2 = mysqli_query($conn, $sql2);
              if (mysqli_num_rows($result2) > 0) 
	      {
              while($row2 = mysqli_fetch_assoc($result2)) 
              {
              $purchase_work=$row2["work"];
              $purchase_for=$row2["forid"];
                                if ($purchase_work === 'maintenance')
                                {
                                $sql3 = "SELECT name FROM maintenances where id=$purchase_for";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				$purchase_customer=" for [MNT".sprintf("%04d", $purchase_for)."] ".$row3["name"];
				}}}

                                elseif ($purchase_work === 'project')
                                {
                                $sql3 = "SELECT name FROM projects where id=$purchase_for";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				$purchase_customer=" for [PRJ".sprintf("%04d", $purchase_for)."] ".$row3["name"];
				}}}
              }}
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 3) === 'OXP')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 3) === 'VXP')
              {
              $cr=0;
              $dr=$row["amount"];
              $vxpid=substr($idet, 3);
              $sql2 = "SELECT * FROM vehicle_expenses where id=$vxpid";
              $result2 = mysqli_query($conn, $sql2);
              if (mysqli_num_rows($result2) > 0) 
	      {
              while($row2 = mysqli_fetch_assoc($result2)) 
              {
              $vehicle_no=$row2["vehicle"];
                                $sql3 = "SELECT model FROM vehicles where id=$vehicle_no";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				$vehicle_name=" for [VHL".sprintf("%04d", $vehicle_no)."] ".$row3["model"];
				}}
              }}
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 3) === 'PYM')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 3) === 'WRI')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 4) === 'ITFD')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 4));
              }
?>
          <tr>
              <td><?php echo ++$i;?></td>
              <td><?php echo $idet;?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php
                                $fromid=$row["fromid"];
                                if (substr($fromid,0,11) === 'maintenance')
                                {
                                $fid=substr($fromid, 11);
                                $sql3 = "SELECT name FROM maintenances where id=$fid";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				echo "[MNT".sprintf("%04d", substr($fromid, 11))."] ".$row3["name"];
				}}}

                                elseif (substr($fromid,0,7) === 'project')
                                {
                                $fid=substr($fromid, 7);
                                $sql3 = "SELECT name FROM projects where id=$fid";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				echo "[PRJ".sprintf("%04d", substr($fromid, 7))."] ".$row3["name"];
				}}}

                                else
                                {
                                $subsql2 = "SELECT name FROM customers where id=$fromid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}}}
              ?></td>
              <td><?php
              $toid=$row["toid"];
                                $subsql2 = "SELECT name FROM customers where id=$toid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?><?php echo $purchase_customer;?><?php echo $vehicle_name;?></td>

              <?php if($cr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$cr);?> Dhs</td>
              <?php } if($dr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$dr);?> Dhs</td>
              <?php } 
              $total=$total+$cr-$dr;
              $totalcr=$totalcr+$cr;
              $totaldr=$totaldr+$dr;
              ?>
              <td align="right"><?php echo custom_money_format("%!i",$total);?> Dhs</td>
              <td><?php echo $row["notes"];?></td>
          </tr>
		<?php
		}
		}
		?>
          <tr>
              <td><b><?php echo ++$i;?></b></td>
              <td><b></b></td>
              <td><b></b></td>
              <td><b></b></td>
              <td><b>Total</b></td>
              <td align="right"><b><?php echo custom_money_format("%!i",$totalcr);?> Dhs</b></td>
              <td align="right"><b><?php echo custom_money_format("%!i",$totaldr);?> Dhs</b></td>
              <td align="right"><b><?php echo custom_money_format("%!i",$total);?> Dhs</b></td>
              <td><b></b></td>
          </tr>
        </tbody>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>