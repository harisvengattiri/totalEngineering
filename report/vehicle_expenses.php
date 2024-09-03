<?php include "../config.php";?>
<?php
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>
<?php
if(isset($_POST))
{
$fdate=$_POST["fdate"];
$tdate=$_POST["tdate"];
$vehicle=$_POST["vehicle"];
}
if($vehicle>0)
{
                                $sql2 = "SELECT id,number,model FROM vehicles where id=$vehicle";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
				$vehicle_name= "VHL".sprintf("%04d", $row2["id"])." [".$row2["number"]."] ".$row2["model"];
				}} 
}
else
{

				$vehicle_name= "All Vehicles";
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
<h2>VEHICLE EXPENSES REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]
<br/><?php echo $vehicle_name;?></h2></center>
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
                  Vehicle
              </th>
              <th>
                  Category
              </th>
              <th>
                  Purchaser
              </th>
              <th>
                  Shop
              </th>
              <th>
                  Date
              </th>
              <th>
                  Amount
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
  if($vehicle>0)
{
		$sql = "SELECT * FROM vehicle_expenses WHERE vehicle=$vehicle AND STR_TO_DATE(date, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') 
                        ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC ";
}
  else
{
		$sql = "SELECT * FROM vehicle_expenses WHERE STR_TO_DATE(date, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') 
                        ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC ";
}            
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td><?php echo ++$i;?></td>
              <td>VXP<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php
                                $vehicle=$row["vehicle"];
                                $subsql2 = "SELECT id,number,model FROM vehicles where id=$vehicle";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo "VHL".sprintf("%04d", $subrow2["id"])." [".$subrow2["number"]."]<br/>".$subrow2["model"];
				}} 
              ?></td>
              <td><?php echo $row["purpose"];?></td>
              <td><?php
                                $purchaser=$row["purchaser"];
                                $subsql2 = "SELECT name FROM customers where id=$purchaser";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php
                                $shop=$row["shop"];
                                $subsql3 = "SELECT name FROM customers where id=$shop";
				$subresult3 = mysqli_query($conn, $subsql3);
				if (mysqli_num_rows($subresult3) > 0) 
				{
				while($subrow3 = mysqli_fetch_assoc($subresult3)) 
				{
				echo $subrow3["name"];
				}} 
              ?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["method"];?></td>
              <td><?php echo $row["notes"];?></td>
          </tr>
		<?php
                $total=$total+$row["amount"];
		}
		}
		?>
        </tbody>
              <td colspan="5"></td>
              <td colspan="2"><b>Total</b></td>
              <td colspan="2"><b><?php echo $total;?> Dhs</b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>