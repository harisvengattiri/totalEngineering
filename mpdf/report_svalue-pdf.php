<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$sr=$_GET["sales_rep"];
  ?>
  
  <?php
$sqlrep="SELECT name from customers where id='$sr'";
               $queryrep=mysqli_query($conn,$sqlrep);
               $fetchrep=mysqli_fetch_array($queryrep);
               $rep=$fetchrep['name'];
  ?>
  

<h2 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?>ORDER REPORT</h2>
<table align="center" width="90%">
     <tr>
          <td width="62%">
               <h4 style=""><span>SalesMan: <?php echo $rep;?></span>
                    <!--<br><span>COC No: <?php echo $coc;?></span>-->
               </h4>
          </td>
          <td width="38%"><span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table width="100%">
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
                    <tr>
                    <td colspan="4"></td>
                    <td colspan="1" style="text-align:right;"><b>TOTAL</b></td>
                    <td colspan="1"><b><?php echo $total;?></b></td>
                    </tr>
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>