<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
$vehicle=$_GET["vehicle"];
  ?>
  
  <?php
   $sqlveh="SELECT vehicle from vehicle where id='$vehicle'";
               $queryveh=mysqli_query($conn,$sqlveh);
               $fetchveh=mysqli_fetch_array($queryveh);
               $veh=$fetchveh['vehicle'];
  ?>
  

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?> VEHICLE REPORT </h1>
<table width="100%">
     <tr>
          <td width="62%">
               <h4 style=""><span>Vehicle: <?php echo $veh;?></span>
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
                  Contractor
              </th>
              <th id="hsite">
                  Project
              </th> 
              <th id="hitem">
                  Item
              </th> 
              <th>
                  Quantity
              </th>  
          </tr>
        </thead>
        <tbody>
		<?php
//        $sql = "SELECT * FROM delivery_note where date BETWEEN '$fdate' AND '$tdate' AND vehicle='$vehicle'";
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND vehicle='$vehicle' ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        
             $name1=$row["customer"];
               $sqlcust="SELECT name from customers where id='$name1'";
               $querycust=mysqli_query($conn,$sqlcust);
               $fetchcust=mysqli_fetch_array($querycust);
               $cust=$fetchcust['name'];
             $site=$row["customersite"];
               $sqlsite="SELECT p_name from customer_site where id='$site'";
               $querysite=mysqli_query($conn,$sqlsite);
               $fetchsite=mysqli_fetch_array($querysite);
               $site1=$fetchsite['p_name'];
        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <!--<td><?php echo $veh;?></td>-->
               <td><?php echo $cust;?></td>
               <td id="bsite"><?php echo $site1;?></td>
               
                <td id="bitem">
                    <?php
                     	 $sql1 = "SELECT item FROM delivery_item where delivery_id='$id' and batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                              $item=$row1["item"];
                              $sqlitem="SELECT items from items where id='$item'";
                              $queryitem=mysqli_query($conn,$sqlitem);
                              $fetchitem=mysqli_fetch_array($queryitem);
                              $item1=$fetchitem['items'];
                         echo $item1; echo '<br>';
                         }
                         }
                    ?>
               </td>
               
               <td>
            <?php   
               $sql1 = "SELECT thisquantity FROM delivery_item where delivery_id='$id' and batch!=''";
               $result1 = mysqli_query($conn, $sql1);
               if (mysqli_num_rows($result1) > 0) 
               {
               while($row1 = mysqli_fetch_assoc($result1)) 
               {
                 echo $row1['thisquantity']; echo '<br>';
                 $tquantity=$tquantity+$row1['thisquantity'];
               }}
            ?>
              </td>  
          </tr>
		<?php
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
              <tr>
              <td colspan="4"></td>
              <td colspan="1"><b>Total</b></td>
              <td colspan="1"><b><?php echo $tquantity;?></b></td>
              </tr>
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>