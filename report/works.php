<?php include "../config.php";?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
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
<h2>WORK REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>
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
                  Name 
              </th>
              <th>
                  Work 
              </th>
              <th>
                  Subdivision 
              </th>
              <th>
                  Main Staff
              </th>
              <th>
                  Date
              </th>
	      <th>
                  Status
              </th>
              <th>
                  Notes
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
              
		$sql = "SELECT * FROM works WHERE STR_TO_DATE(date, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') 
                        ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC ";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td><?php echo ++$i;?></td>
              <td>DWR<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $row["wname"];?></td>
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
				?></td>
              <td><?php echo $row["division"];?></td>
              <td><?php
                                $staff=$row["staff"];
                                $subsql2 = "SELECT name FROM customers where id=$staff";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td>No Status</td>
              <td><?php echo $row["notes"];?></td>
          </tr>
		<?php
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