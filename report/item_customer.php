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
$item1=$_POST["item"];
             
               $sqlitem="SELECT items from items where id='$item1'";
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

<div style="margin-top: -55px;" id="head" align="center"><img src="../images/header.png"></div>
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>ITEM - CUSTOMER REPORT</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;">Item: <?php echo $item;?></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table align="center" style="width:90%;">
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
                  Quantity
              </th>
              <th>
                  Price
              </th>  
          </tr>
        </thead>
        <tbody>
             
        <?php
        // $sql = "SELECT *,sum(delivery_item.thisquantity) AS qnt FROM delivery_note INNER JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE item='$item1' "
        //         . "AND STR_TO_DATE(delivery_note.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') GROUP BY STR_TO_DATE(delivery_note.date, '%d/%m/%Y'),customer ORDER BY price DESC";
        
        
        $sql = "SELECT dn.date,dn.customer,di.price,sum(di.thisquantity) AS quantity
                FROM delivery_note dn INNER JOIN delivery_item di ON dn.id=di.delivery_id WHERE di.item='$item1'".
                "AND STR_TO_DATE(dn.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                GROUP BY STR_TO_DATE(dn.date, '%d/%m/%Y'),dn.customer ORDER BY di.price DESC";
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	    {
        $sl=1;
        while($row = mysqli_fetch_assoc($result)) 
        {
        $customer=$row['customer'];
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        ?>     
          <tr>
               <td><?php echo $sl;?></td>
               <td><?php echo $row['date'];?></td>
               <td><?php echo $cust;?></td>
               <td><?php echo $row['quantity'];?></td>
               <!--<td><?php // echo $row['thisquantity'];?></td>-->
               <td><?php echo $row['price'];?></td>
          </tr>
		<?php
             $sl = $sl + 1;
        }}
		?>
        </tbody>
              
      </table>
<?php
if(isset($_POST['print']))
{ ?>
<body onload="window.print()">
<?php } ?>