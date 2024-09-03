<?php
  include "../config.php";
  error_reporting(0);
  ?>
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
$sr=$_POST["sales_rep"];

               $sqlrep="SELECT name from customers where id='$sr'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
}
?>
<!--<title> <?php //echo $title;?></title>-->

<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page { size: auto;  margin: 0mm; }
           
         /*p.bodyText {font-family:georgia, times, serif;}*/
      }
      @media screen, print {
           
         /*p.bodyText {font-size:10pt}*/
      }
  
</style>


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

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<!--<center><h1>Mancon Block Factory.</h1>-->
<center> <h1 style="margin-bottom: -3%;"><?php echo strtoupper($status);?> ORDER REPORT </h1></center>
<h3 style="float:left;">Sales Man: <?php echo $rep;?></h3>
<h3 style="float:right;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h3>

<table>
        <thead id="thead">
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  P.O
              </th>
<!--              <th>
                  Sales Rep
              </th>-->
              <th>
                  Contractor
              </th>
              <th>
                  Project
              </th> 
              <th>
                  Project Value
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
//		$sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate' AND salesrep='$sr'";
                $sql = "SELECT * FROM sales_order WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND salesrep='$sr' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $total=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
        $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        $site=$row["site"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
         $grand=$row["grand_total"];
         $total=$total+$row["grand_total"];
               
               ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo $row['order_referance'];?></td>
               <!--<td><?php echo $rep;?></td>-->
               <td><?php echo $cust;?></td>
               <td><?php echo $site1;?></td>
               <td><?php echo $grand;?></td>
               
          </tr>
		<?php
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
              <td colspan="4"></td>
              <td colspan="1" style="text-align:right;"><b>TOTAL</b></td>
              <td colspan="1"><b><?php echo $total;?></b></td>
      </table>
<?php
if(isset($_POST['print']))
{ ?>
<body onload="window.print()">
<?php } ?>