<?php
  include "../config.php";
  error_reporting(0);

$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$driver=$_GET["driver"];
  ?>
  
  <?php
$sqldri="SELECT name from customers where id='$driver'";
               $querydri=mysqli_query($conn,$sqldri);
               $fetchdri=mysqli_fetch_array($querydri);
               $driver1=$fetchdri['name'];
  ?>
  

<h1 style="text-align:center;margin-bottom:-10px;"><?php // echo strtoupper($status);?> DRIVER REPORT </h1>
<table width="100%">
     <tr>
          <td width="62%">
               <h4 style=""><span>Driver: <?php echo $driver1;?></span>
                    <!--<br><span>COC No: <?php echo $coc;?></span>-->
               </h4>
          </td>
          <td width="38%"><span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table width="100%">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Date
              </th>
              <th>
                  DNO
              </th>
              <th>
                  Contractor
              </th>
              <th>
                  Location
              </th>
              <th>
                  Amount
              </th>
              
                      
          </tr>
        </thead>
        <tbody>
		<?php
//		$sql = "SELECT * FROM delivery_note where date BETWEEN '$fdate' AND '$tdate' AND driver='$driver'";
                $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND driver='$driver' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $tfair=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
        $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
        $site=$row["customersite"];
               $sqlsite="SELECT p_name,location from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
               $loc=$fetchsite['location'];
               
               $sqlfair="SELECT fair,location from fair where id='$loc'";
               $queryfair=mysqli_query($conn,$sqlfair);
               $fetchfair=mysqli_fetch_array($queryfair);
               $fair=$fetchfair['fair'];
               $fair = ($fair != NULL) ? $fair : 0;
               $loc1=$fetchfair['location'];

        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf("%06d",$id);?></td>
               <!--<td><?php echo $driver;?></td>-->
               <td><?php echo $cust;?></td>
               <!--<td><?php echo $site1;?></td>-->
               <td><?php echo $loc1;?></td>
               <td><?php echo $fair;?></td>
               
               
          </tr>
		<?php
                  $sl=$sl+1;
                  $tfair=$tfair+$fair;
		}
		}
		?>
        </tbody>
        <tr>
             <td></td><td></td><td></td><td></td><td><b>Total</b></td>
             <td><b><?php echo $tfair;?></b></td>
        </tr>
<!--              <td colspan="4"></td>
              <td colspan="2"><b>Total</b></td>
              <td colspan="1"><b><?php //echo $totaldue;?> Dhs</b></td>
              <td colspan="1"><b><?php //echo $totalpaid;?> Dhs</b></td>
              <td colspan="1"><b>Balance</b></td>
              <td colspan="1"><b><?php //echo $totaldue-$totalpaid;?> Dhs</b></td>-->
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>