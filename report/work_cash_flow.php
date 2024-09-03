<?php include "../config.php";?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$work=$_POST["work"];
$type_code=substr($work, 0, 3);
if($type_code=="MNT")
{
$work_type="maintenance";
$work_table="maintenances";
}
else if($type_code=="PRJ")
{
$work_type="project";
$work_table="projects";
}
$work_no=substr($work, 3);
}
if($work=="all")
{
$workname="All Works";
}
elseif($work=="allmnt")
{
$workname="All Maintenances";
}
elseif($work=="allprj")
{
$workname="All Projects";
}
else
{
        $sql2 = "SELECT name FROM $work_table where id=$work_no";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
				$workname=$row2["name"];
				}} 
                                $workname=" [".$type_code.sprintf("%04d", $work_no)."] ".$workname;
}
?>
<title> <?php echo $title;?></title>
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
<center><h1>WadialRaha Air Conditioning Contracting EST.</h1>
<h2>WORK CASH FLOW REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]
<br/><?php echo $workname;?></h2></center>
<table>
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
?>

<?php
if($work=="all")
{		$sql = "SELECT * FROM (
(SELECT CONCAT('PRC', id) AS id, purchaser as fromid, shop as toid, amount, date, notes FROM purchases)
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments)
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, notes FROM work_invoices WHERE paid>0)) results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
}
elseif($work=="allmnt")
{		$sql = "SELECT * FROM (
(SELECT CONCAT('PRC', id) AS id, purchaser as fromid, shop as toid, amount, date, notes FROM purchases WHERE work='maintenance')
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments WHERE wtype='maintenance')
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, notes FROM work_invoices WHERE paid>0 AND wtype='maintenance')) results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
}
elseif($work=="allprj")
{		$sql = "SELECT * FROM (
(SELECT CONCAT('PRC', id) AS id, purchaser as fromid, shop as toid, amount, date, notes FROM purchases WHERE work='project')
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments WHERE wtype='project')
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, notes FROM work_invoices WHERE paid>0 AND wtype='project')) results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
}
else
{		$sql = "SELECT * FROM (
(SELECT CONCAT('PRC', id) AS id, purchaser as fromid, shop as toid, amount, date, notes FROM purchases WHERE work='$work_type' and forid=$work_no)
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments WHERE wtype='$work_type' and work=$work_no)
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, notes FROM work_invoices WHERE paid>0 and  wtype='$work_type' and work=$work_no)
) results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
}
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
        </tbody>
              <td colspan="4"><b></b></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $totalcr;?> Dhs</b></td>
              <td colspan="1"><b><?php echo $totaldr;?> Dhs</b></td>
              <td colspan="2"><b><?php echo $total;?> Dhs</b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>