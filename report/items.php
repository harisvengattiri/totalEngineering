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
$items=$_POST["items"];

             
               $sqlitem="SELECT items from items where id='$items'";
               $queryitem=mysqli_query($conn,$sqlitem);
               $fetchitem=mysqli_fetch_array($queryitem);
               $item=$fetchitem['items'];
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
<center><h1>Mancon Block Factory.</h1>
<h2><?php echo strtoupper($status);?> ITEM ORDER REPORT [<?phpecho $item;?>]</h2>
<h2>Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></h2></center>
<table>
        <thead>
          <tr>
              <th>
                 Sl No
              </th>
              <th>
                  Date
              </th>
              
              <th>
                  Contractor
              </th>
              <th>
                  Site
              </th>
              <th>
                  Quantity
              </th>
              
              
          </tr>
        </thead>
        <tbody>
             
        <?php
//	$sql = "SELECT * FROM sales_order where date BETWEEN '$fdate' AND '$tdate'";
        $sql = "SELECT * FROM sales_order WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')  ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        while($row = mysqli_fetch_assoc($result)) 
        {
        $id=$row['id'];
        $customer=$row['customer'];
        $site=$row['site'];
        
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
        
        $sql1 = "SELECT * FROM order_item where item_id='$id' AND item='$items'";
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) > 0) 
	{
        while($row1 = mysqli_fetch_assoc($result1)) 
        {

        ?>
             
          <tr>
               <td><?php echo $sl;?></td>
               <td><?php echo $row['date'];?></td>
               <!--<td><?php echo $item;?></td>--> 
               <td><?php echo $cust;?></td>
               <td><?php echo $site1;?></td>
               <td><?php echo $row1['quantity'];?></td>
               
          </tr>
		<?php
             $sl=$sl+1;     
        }}
        }}
		?>
        </tbody>
              
      </table>
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>