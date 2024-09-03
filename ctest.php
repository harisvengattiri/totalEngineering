<?php include "config.php";?>

        <table>
<?php
		$sql = "SELECT * FROM credit_application ORDER BY id DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
             $sl=1;
        while($row = mysqli_fetch_assoc($result)) {
            
             $company=$row["company"]; 
             $sqlcust="SELECT name FROM customers where id='$company'";
             $querycust=mysqli_query($conn,$sqlcust);
             $fetchcust=mysqli_fetch_array($querycust);
             $cust=$fetchcust['name'];
            
             $rep=$row["rep"];
             $sqlrep="SELECT name FROM customers where id='$rep'";
             $queryrep=mysqli_query($conn,$sqlrep);
             $fetchrep=mysqli_fetch_array($queryrep);
             $rep1=$fetchrep['name'];
             
             
        
                  $sqlinvo="SELECT sum(delivery_item.amt) AS grand FROM delivery_note LEFT JOIN delivery_item ON delivery_note.id=delivery_item.delivery_id WHERE delivery_note.customer = $company";
                  $queryinvo=mysqli_query($conn,$sqlinvo);
                  $fetchinvo=mysqli_fetch_array($queryinvo);
                  $invoamt=$fetchinvo['grand'];
                  
                  
                  $sqlrpt="SELECT sum(amount) AS amount from reciept where customer=$company";
                  $queryrpt=mysqli_query($conn,$sqlrpt);
                  $fetchrpt=mysqli_fetch_array($queryrpt);
                  $amountrpt=$fetchrpt['amount'];
                  
                  $current_bal=$invoamt-$amountrpt;
             
             $status=$row["status"];
             if($status == '') {$status='Pending';}
             if($status=="Approved")
                    {
                    $color="success";
                    }
                    elseif($status=="Rejected")
                    {
                    $color="danger";
                    }
                    else
                    {
                    $color="warning";
                    }
                    
                    
        ?>
          <tr>
              
              <td><?php echo $sl;?></td>
              <td>CDT<?php echo sprintf("%04d", $row["id"]);?></td>
              <td><?php echo $cust;?></td>
              <td><?php echo $row["d_name1"];?></td>
              <td><?php echo $row["bank"];?></td>
              <td><?php echo $rep1;?></td>
              <td><?php echo $row["credit"];?></td>
              <td><?php echo custom_money_format('%!i', $invoamt);?></td>
              <td><?php echo $current_bal;?></td>
              <td><?php echo $row["mode"];?><br/><?php echo $row["period"];?></td>
          </tr>
		<?php
                  $sl=$sl+1;
		}
		}
		?>
          </table>