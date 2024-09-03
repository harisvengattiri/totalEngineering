<?php
  include "../config.php";
  error_reporting(0);
  
  $fdate=$_GET["fd"];
  $tdate=$_GET["td"];
  ?>
<h2 style="text-align:center;margin-bottom:-10px;">DAILY DELIVERY REPORT</h2>
<table align="center" width="90%">
     <tr>
          <td width="50%">
              <?php $today=date('d/m/Y');?>
          </td>
          <td width="50%" style="text-align: right;"><span style="font-size:15px;"> From:<?php echo $fdate;?> - To:<?php echo $tdate;?></span></td>
     </tr>     
</table>


<table width="90%" align="center" class="table m-b-none" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
               <th>
                  Date
              </th>
              <th>
                  Delivery
              </th>
              <th>
                  Sales Order
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Site
              </th>
              <th>
                  LPO
              </th>
              <th>
                  Vehicle
              </th>
              <th>
                  Driver
              </th>
              <th>
                  Amount
              </th>   
          </tr>
        </thead>
        
        <tbody>
		<?php
        $sql = "SELECT * FROM delivery_note WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') ORDER BY STR_TO_DATE(date, '%d/%m/%Y') ASC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
	{
        $sl=1;
        $grand=0;
        $tquantity=0;
        while($row = mysqli_fetch_assoc($result)) {
        $id=$row['id'];
        $company=$row['customer'];
            $sqlcust="SELECT name from customers where id='$company'";
            $querycust=mysqli_query($conn,$sqlcust);
            $fetchcust=mysqli_fetch_array($querycust);
            $cust=$fetchcust['name'];
        $site_id=$row['customersite'];
            $sqlsite="SELECT p_name from customer_site where id='$site_id'";
            $querysite=mysqli_query($conn,$sqlsite);
            $fetchsite=mysqli_fetch_array($querysite);
            $site=$fetchsite['p_name'];
        $dvr=$row['driver'];
            $sqldvr="SELECT name from customers where id='$dvr'";
            $querydvr=mysqli_query($conn,$sqldvr);
            $fetchdvr=mysqli_fetch_array($querydvr);
            $driver=$fetchdvr['name'];
        $veh=$row['vehicle'];
            $sqlveh="SELECT vehicle from vehicle where id='$veh'";
            $queryveh=mysqli_query($conn,$sqlveh);
            $fetchveh=mysqli_fetch_array($queryveh);
            $vehicle=$fetchveh['vehicle'];
        ?>
          <tr>
               <td><?php echo $sl;?></td> 
               <td><?php echo $row['date'];?></td>
               <td>DN|<?php echo sprintf('%06d',$id);?></td>
               <td><?php echo $row['order_referance'];?></td>
               <td><?php echo $cust;?></td>
               <td><?php echo $site;?></td>
               <td><?php echo $row['lpo'];?></td>
               <td><?php echo $vehicle;?></td>
               <td><?php echo $driver;?></td>
               <td><?php echo $row['total'];?></td>
          </tr>
		<?php
                  $grand=$grand+$row['total'];
                  $sl=$sl+1;
		}
		}
		?>
        </tbody>
        <tr>
        <td colspan="8"><b></b></td>
        <td colspan="1"><b>Grand Total</b></td>
        <td colspan="1"><b><?php echo custom_money_format("%!i", $grand);?></b></td>
        </tr>
</table>


<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>