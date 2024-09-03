<?php include "../config.php";?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
if($fdate=='')
{
$fdate="01/01/2017";
}
if($tdate=='')
{
$tdate=date('d/m/Y');
}
$cdate = substr($fdate, 3, 3).substr($fdate, 0, 3).substr($fdate, 6, 4);
$cdate = date('d/m/Y', strtotime($cdate .' -1 day'));
$bank=74;
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
<h2>BANK CASH FLOW REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>
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

		$sql = "SELECT * FROM (
(SELECT amount,date FROM internal_transfers WHERE toid='$bank')
UNION ALL (SELECT amount,date FROM payments WHERE reciever='$bank' or method!='cash')
UNION ALL (SELECT paid,duedate FROM work_invoices WHERE (paid>0 AND collector='$bank') OR (paid>0 AND method!='cash'))
)results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('', '%d/%m/%Y') AND STR_TO_DATE('$cdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $amount=$row["amount"];
              $opening_balance1=$opening_balance1+$amount;
		}}

		$sql = "SELECT * FROM (
(SELECT amount,date FROM purchases WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT amount,date FROM office_expenses WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT amount,date FROM vehicle_expenses WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT amount,date FROM internal_transfers WHERE fromid='$bank')
)results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('', '%d/%m/%Y') AND STR_TO_DATE('$cdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $amount=$row["amount"];
              $opening_balance2=$opening_balance2+$amount;
		}}
                
                $total=$opening_balance1-$opening_balance2;
                $totalcr=$opening_balance1;
                $totaldr=$opening_balance2;
?>


<tr>
<td><?php echo ++$i;?></td>
<td>OPEN</td>
<td><?php echo $cdate;?></td>
<td>Opening Balance</td>
<td>RAK Bank</td>
<td><?php echo custom_money_format("%!i",$totalcr);?> Dhs</td>
<td><?php echo custom_money_format("%!i",$totaldr);?> Dhs</td>
<td><?php echo custom_money_format("%!i",$total);?> Dhs</td>
<td>Opening Balance</td>
</tr>

<?php

		$sql = "SELECT * FROM (
(SELECT CONCAT('ITFC', id) AS id, fromid, toid, amount, date, notes FROM internal_transfers WHERE toid='$bank')
UNION ALL (SELECT CONCAT('PRC', id) AS id, purchaser, shop, amount, date, notes FROM purchases WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT CONCAT('OXP', id) AS id, purchaser, shop, amount, date, notes FROM office_expenses WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT CONCAT('VXP', id) AS id, purchaser, shop, amount, date, notes FROM vehicle_expenses WHERE purchaser='$bank' or method!='cash')
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments WHERE reciever='$bank' or method!='cash')
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, CONCAT(status,' ',notes) AS notes FROM work_invoices WHERE (paid>0 AND collector='$bank') OR (paid>0 AND method!='cash'))
UNION ALL (SELECT CONCAT('ITFD', id) AS id, fromid, toid, amount, date, notes FROM internal_transfers WHERE fromid='$bank')
)results WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $idet=$row["id"];
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
              ?></td>

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
              <td colspan="1"><b><?php echo custom_money_format("%!i",$totalcr);?> Dhs</b></td>
              <td colspan="1"><b><?php echo custom_money_format("%!i",$totaldr);?> Dhs</b></td>
              <td colspan="2"><b><?php echo custom_money_format("%!i",$total);?> Dhs</b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>