<?php include "../config.php";?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$status=$_POST["status"];
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
<h2><?php echo strtoupper($status);?> WORK INVOICES REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>
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
                  Issue Date
              </th>
              <th>
                  Work
              </th>
              <th>
                  Due Date
              </th>
	      <th>
                  Collector
              </th>
              <th>
                  Status
              </th>
	      <th>
                  Total Due
              </th>
	      <th>
                  Amount Paid
              </th>
	      <th>
                  Method
              </th>
              <th>
                  Notes
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
if($status=="All")
{             
		$sql = "SELECT * FROM work_invoices WHERE STR_TO_DATE(duedate, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') 
                        ORDER BY STR_TO_DATE(duedate, '%d/%m/%Y') ASC ";
}
else
{             
		$sql = "SELECT * FROM work_invoices WHERE STR_TO_DATE(duedate, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND status='$status'
                        ORDER BY STR_TO_DATE(duedate, '%d/%m/%Y') ASC ";
}
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td><?php echo ++$i;?></td>
              <td>WRI<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["issuedate"];?></td>
              <td><?php
              if($row["wtype"]=="maintenance")
              {
              echo "MNT".sprintf("%04d", $row["work"]);
              }
              elseif($row["wtype"]=="project")
              {
              echo "PRJ".sprintf("%04d", $row["work"]);
              }
				$id=$row["work"];
                                $work=$row["wtype"];
                                $work=$work."s";
				$subsql = "SELECT name FROM $work where id=$id";
				$subresult = mysqli_query($conn, $subsql);
				if (mysqli_num_rows($subresult) > 0) 
				{
				while($subrow = mysqli_fetch_assoc($subresult)) 
				{
				echo "<br/>[".$subrow["name"]."]";
				}} 
				?>
              </td>
              <td><?php echo $row["duedate"];?></td>
              <td><?php
                                $collector=$row["collector"];
                                $subsql2 = "SELECT name FROM customers where id=$collector";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php echo $row["status"];?></td>
              <td><?php echo 0+$row["due"];?> Dhs</td>
              <td><?php echo 0+$row["paid"];?> Dhs</td>
              <td><?php echo $row["method"];?></td>
              <td><?php echo $row["notes"];?></td>
          </tr>
		<?php
                $totalpaid=0+$totalpaid+$row["paid"];
                $totaldue=0+$totaldue+$row["due"];
		}
		}
		?>
        </tbody>
              <td colspan="4"></td>
              <td colspan="2"><b>Total</b></td>
              <td colspan="1"><b><?php echo $totaldue;?> Dhs</b></td>
              <td colspan="1"><b><?php echo $totalpaid;?> Dhs</b></td>
              <td colspan="1"><b>Balance</b></td>
              <td colspan="1"><b><?php echo $totaldue-$totalpaid;?> Dhs</b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>