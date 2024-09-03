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
<h2>PURCHASE REPORT [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></center>
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
                  Purchased for
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
                  Status
              </th>
	      <th>
                  Method
              </th>
	      <th>
                  Amount
              </th>
              <th>
                  Notes
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
              
		$sql = "SELECT * FROM purchases WHERE STR_TO_DATE(date, '%d/%m/%Y') 
                        BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') 
                        ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC ";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
              <td><?php echo ++$i;?></td>
              <td>PRC<?php echo sprintf("%04d", $row["id"]);?></td>
	      <td><?php
              if($row["work"]=="maintenance")
              {
              echo "MNT".sprintf("%04d", $row["forid"]);
              }
              elseif($row["work"]=="project")
              {
              echo "PRJ".sprintf("%04d", $row["forid"]);
              }
				$id=$row["forid"];
                                $work=$row["work"];
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
              <td><?php echo substr($row["date"],0,10);?><br/><?php echo substr($row["date"],11,8);?></td>
              <td><?php echo ucfirst($row["status"]);?></td>
              <td><?php echo ucfirst($row["method"]);?></td>
              <td><?php echo $row["amount"];?> Dhs</td>
              <td><?php echo $row["notes"];?></td>
          </tr>
		<?php
                $total=$total+$row["amount"];
		}
		}
		?>
        </tbody>
              <td colspan="6"></td>
              <td colspan="2"><b>Total</b></td>
              <td colspan="2"><b><?php echo $total;?> Dhs</b></td>
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>