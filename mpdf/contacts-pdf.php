<?php
  include "../config.php";
  error_reporting(0);
  ?>

<h2 style="text-align:center;margin-bottom:1px;"><?php echo strtoupper($status);?>COMPANY CUSTOMERS</h2>
<table align="center" style="width:90%;">
     <tr>
          <td width="50%">
               <!--<span style="font-size:15px;">Supplier: <?php echo $cust;?></span>-->
          </td>
          <td width="50%" style="text-align:right">
               <!--<span style="font-size:15px;"> Date: From <?php echo $fdate;?> - To <?php echo $tdate;?></span>-->
          </td>
     </tr>     
</table>

<table id="tbl1" align="center" style="width:90%;">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Customer
              </th>
              <th>
                  Contact Person
              </th>
              <th>
                  Land Line
              </th>
              <th>
                  Mobile
              </th>
              <th>
                  Mail
              </th>              
          </tr>
        </thead>
        <tbody style="font-size:11px;">
		<?php
 
         $sql = "SELECT * FROM customers WHERE type='Company' ORDER BY name";
         
                      
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0)
		{ 
                $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
               <td style="width:5%;"><?php echo $sl;?></td>
               
               <td><?php echo $name = $row['name']; ?></td>
               <td><?php echo $row['person'];?></td>
               <td><?php echo $row['phone'];?></td>
               <td><?php echo $row['mobile'];?></td>
               <td><?php
                 $email = $row['email'];
                 echo wordwrap($email,30,"<br>\n", true);
               ?></td>
               
               
          </tr>
		<?php
		 $sl++; } }
                ?>
        </tbody>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>