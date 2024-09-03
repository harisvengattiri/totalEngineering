<?php
  include "../config.php";
  error_reporting(0);
  
$fdate=$_GET["fd"];
$tdate=$_GET["td"];
  ?>

<h1 style="text-align:center;margin-bottom:-10px;"><?php echo strtoupper($status);?> DELIVERY REPORT </h1>
<table width="100%">
     <tr>
          <td width="62%">
               <!--<h4 style=""><span>Batch No: <?php echo $lot;?></span><br><span>COC No: <?php echo $coc;?></span></h4>-->
          </td>
          <td width="38%"><span> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span></td>
     </tr>     
</table>

<table id="report" class="display nowrap" cellspacing="0" width="100%">

<!--<table>-->
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
              <th id="hcust">
                  Contractor
              </th>
              <th id="hsite">
                  Project
              </th>
              <th id="hitem" width="20%">
                  Item
              </th>
              
              <th id="hquan">
                  Qty
              </th>
              <th id="hprice">
                  Price
              </th>
              <th>
                  Amount
              </th>
                    
          </tr>
        </thead>
        <tbody>
		<?php
//		$sql = "SELECT * FROM delivery_note where date BETWEEN '$fdate' AND '$tdate' AND driver='$driver'";
                $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
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
               

        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td><?php echo sprintf('%06d',$id);?></td>
               <!--<td><?php echo $driver;?></td>-->
               <td id="bcust"><?php echo $cust;?></td>
               <td id="bsite"><?php echo $site1;?></td>
               
               <td id="bitem" width="20%">
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
//                         echo $item1; echo',<br>';
                         echo wordwrap($item1, 15, "\n", true).'<br>';
                         }
                         }
                    ?>
               </td>
               
               
               <td id="bquan">
                    <?php
                     	 $sql1 = "SELECT thisquantity FROM delivery_item where delivery_id='$id' and batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         echo $row1['thisquantity']; echo'<br>';
                         }
                         }
                    ?>
               </td>
               
               <td id="bprice">
                   <?php
                     	 $sql1 = "SELECT price FROM delivery_item where delivery_id='$id' and batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         {
                         $price1=$row1['price'];
                         $price = str_replace(' ', '', $price1);
                         echo $price.'<br>';
                         }
                         }
                    ?>
               </td>
               
               <td>
                    <?php
                     	 $sql1 = "SELECT thisquantity,price FROM delivery_item where delivery_id='$id' and batch!=''";
                         $result1 = mysqli_query($conn, $sql1);
                         if (mysqli_num_rows($result1) > 0) 
                         {
                         $amount=0;
                         while($row1 = mysqli_fetch_assoc($result1)) 
                         { 
                         $quan= $row1['thisquantity'];
                         $price= $row1['price'];
                         $amt=$quan*$price;
                         $amount=$amount+$amt;
                         }
                         }
                         echo $amount;
                    ?>
               </td>
               
          </tr>
		<?php
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
      </table>
<!--<div align="center"><img src="../images/footer01.png"></div>-->
<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>