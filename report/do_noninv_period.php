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
if(!empty($_POST))
{
    $fdate = $_POST['fdate'];
    $tdate = $_POST['tdate'];
    $cust_type = $_POST['cust_type'];
}
    if($cust_type != NULL){
        $cust_type_sql = "AND cs.cust_type='$cust_type'";
    } else {
        $cust_type_sql = '';
    }
?>
<style type = "text/css">
   
      @media screen {
         /*p.bodyText {font-family:verdana, arial, sans-serif;}*/
      }

      @media print {
           @page {margin: 0mm;}
           #hlpo,#blpo{width:100px;word-break: break-all;}
           #hsite,#bsite{width:200px;word-break: break-all;}
           #hitem,#bitem{width:200px;word-break: break-all;}
           
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
<h2 style="text-align:center;margin-bottom:1px;"><?php // echo strtoupper($status);?>NON INVOICED DO REPORT<br>
 <?php
 if($cust_type != NULL){
 echo '[ '.$cust_type.' Customers ]';
 }
 ?> 
</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <span style="font-size:15px;"></span>
          </td>
          <td width="50%" style="text-align:right"><span style="font-size:15px;"> Date: <?php echo $fdate;?> - <?php echo $tdate;?></span></td>
     </tr>     
</table>
      
<?php
    // $sql = "SELECT * FROM delivery_note WHERE invoiced != 'yes' AND STR_TO_DATE(date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')";
    
    $sql = "SELECT dn.date,dn.customersite,cs.name as customer,dn.order_referance,dn.id
            FROM delivery_note dn JOIN customers cs ON dn.customer=cs.id
            WHERE dn.invoiced != 'yes' AND dn.total > 0 $cust_type_sql AND STR_TO_DATE(dn.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
            ORDER BY cs.name";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) 
	{ $sl=1;
?>
 <table id="tbl1" align="center" style="width:96%;margin-top: 25px;">
        <thead>
          <tr>
              <th>
                   Sl
              </th>
              <th>
                   Date
              </th>
              <th>
                   Customer
              </th>
              <th>
                   Customer Site
              </th>
              <th>
                   Sales Order
              </th>
              <th>
                   Delivery Note
              </th>
          </tr>
        </thead>
        <tbody style="font-size:11px;">
        
             
        <?php  
        while($row = mysqli_fetch_assoc($result)) 
        {
            
            $site = $row['customersite'];
            
            // $sqlcust="SELECT name from customers where id='$cust'";
            // $querycust=mysqli_query($conn,$sqlcust);
            // $fetchcust=mysqli_fetch_array($querycust);
            // $customer=$fetchcust['name'];
            $customer = $row['customer'];
            
            $sqlsite="SELECT p_name from customer_site where id='$site'";
            $querysite=mysqli_query($conn,$sqlsite);
            $fetchsite=mysqli_fetch_array($querysite);
            $cust_site=$fetchsite['p_name'];
        ?>     
            
            <tr>
                <td><?php echo $sl;?></td>
                <td><?php echo $row['date'];?></td>
                <td><?php echo $customer;?></td>
                <td><?php echo $cust_site;?></td>
                <td><?php echo $row['order_referance'];?></td>
                <td><?php echo $row['id'];?></td>
             </tr>
             
        <?php 
            $sl=$sl+1;
        }
        ?>
       </tbody>
 </table>
 <?php } ?>
      

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>