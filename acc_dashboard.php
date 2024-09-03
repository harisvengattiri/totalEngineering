<?php include "config.php";?>
<?php include "includes/menu.php";?>
<div class="app-body">
<?php
$status="NULL";
// if(isset($_POST['submit']))
// {
//     $entry = $_POST['entry'];
    
//     $redirectPage = '';
//     switch ($entry) {
//     case '1': $redirectPage = 'receipt'; $type = 1; break;
//     case '2': $redirectPage = 'journel'; $type = 1; break;
//     case '3': $redirectPage = 'prepaid'; $type = 1; break;
//     case '4': $redirectPage = 'crdt_note'; $type = 1; break;
//     case '5': $redirectPage = 'crdt_note'; $type = 1; break;
//     case '6': $redirectPage = 'settings'; $type = 2; break;
//     case '7': $redirectPage = 'settings'; $type = 2; break;
//     default:  $redirectPage = '';
//     }
//     if($type == 1) {
//     echo '<script type="text/javascript">
//             window.location.href = "'.$baseurl.'/add/'.$redirectPage.'";
//           </script>';
//     } else {
//     echo '<script type="text/javascript">
//             window.location.href = "'.$baseurl.'/'.$redirectPage.'";
//           </script>';
//     }
// }
?>
<!-- ############ PAGE START-->
<div class="padding">
  <div class="row">
    <div class="col-md-12">
	<?php if($status=="success") {?>
	<p><a class="list-group-item b-l-success">
          <span class="pull-right text-success"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label success pos-rlt m-r-xs">
		  <b class="arrow right b-success pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-success">Your Submission was Successfull!</span>
    </a></p>
	<?php } else if($status=="failed") { ?>
	<p><a class="list-group-item b-l-danger">
          <span class="pull-right text-danger"><i class="fa fa-circle text-xs"></i></span>
          <span class="label rounded label danger pos-rlt m-r-xs">
		  <b class="arrow right b-danger pull-in"></b><i class="ion-checkmark"></i></span>
		  <span class="text-danger">Your Submission was Failed!</span>
    </a></p>
	<?php }?>
      <div class="box">
        <div class="box-header">
          <h2>Overview of Accounts</h2>
          </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
            
          <!--<form role="form" action="<?php // echo $baseurl;?>/acc_dashboard" method="post">-->
            
          <!--  <div class="form-group row">-->
          <!--      <label for="name" class="col-sm-1 form-control-label">Select Entry</label>-->
          <!--      <div class="col-sm-4">-->
          <!--          <select name="entry" required class="form-control select2" data-ui-jp="select2" data-ui-options="{theme: 'bootstrap'}">-->
          <!--              <option value="">Select Entry</option>-->
          <!--              <option value="1">Receipt Voucher</option>-->
          <!--              <option value="2">Journal Voucher</option>-->
          <!--              <option value="3">Payment Voucher </option>-->
          <!--              <option value="4">Credit Note</option>-->
          <!--              <option value="5">Debit Note</option>-->
          <!--              <option value="6">Create New Account</option>-->
          <!--              <option value="7">Modify Account Name</option>-->
          <!--          </select>-->
          <!--      </div>-->
          <!--  </div>-->
              
          <!--  <div class="form-group row m-t-md">-->
          <!--    <div align="right" class="col-sm-offset-2 col-sm-6">-->
          <!--      <button name="submit" type="submit" class="btn btn-sm btn-outline rounded b-success text-success">Manage</button>-->
          <!--    </div>-->
          <!--  </div>-->
            
          <!--</form>-->
          
          
          
          
          
        <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="<?php echo $list_count;?>">
        <thead>
          <tr>
              <th data-toggle="true">
                 Serial No
              </th>
	          <th>
                  Customer
              </th>
	          <th>
                  Date
              </th>
              <th>
                  Amount
              </th>
              <!--<th>-->
              <!--    Actions-->
              <!--</th>-->
          </tr>
        </thead>
        <tbody>
		<?php
        $sql ="SELECT * FROM (
                    (SELECT CONCAT('INV', id) AS id, time,customer AS cust, o_r AS po, invoice.date AS adate, invoice.site AS site, lpo AS lpo, NULL as c_date, NULL as ref, invoice.grand as credit FROM invoice WHERE invoice.date != '')
                    UNION ALL
                    (SELECT CONCAT('RCP', id) AS id, time,customer AS cust, NULL AS po, reciept.clearance_date AS adate, reciept.bank AS site, reciept.cheque_no AS lpo, duedate as c_date, ref as ref,reciept.amount as credit FROM reciept WHERE status= 'Cleared')
                    UNION ALL
                    (SELECT CONCAT('CDT', id) AS id, time,customer AS cust, NULL AS po, credit_note.date AS adate, NULL AS site, invoice AS lpo, NULL as c_date, NULL as ref,credit_note.total as credit FROM credit_note)
                    UNION ALL
                    (SELECT CONCAT('RFD', id) AS id, time,customer AS cust, NULL AS po, refund.date AS adate, remarks AS site, NULL AS lpo, NULL as c_date, NULL as ref,refund.amount as credit FROM refund)
                    UNION ALL
                    (SELECT CONCAT('MSC', id) AS id, time,customer AS cust, NULL AS po, miscellaneous.date AS adate, particulars AS site, NULL AS lpo, NULL as c_date, NULL as ref,miscellaneous.total as credit FROM miscellaneous)
                    ) results ORDER BY CONCAT(STR_TO_DATE(adate, '%d/%m/%Y'), ' ', time) DESC LIMIT 50";
                    
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {
            $idet = $row["id"];
            $cust = $row["cust"];
                $sql_cust = "SELECT `name` FROM `customers` WHERE `id`='$cust'";
                $query_cust = mysqli_query($conn,$sql_cust);
                $result_cust = mysqli_fetch_array($query_cust);
                $customer = $result_cust['name'];
            $amount = $row["credit"];
        ?>
          <tr>
              <!--<td><?php // echo $idet;?></td>-->
              <td><?php echo $idet = substr($idet, 0, 3).'|'.sprintf("%06d", substr($idet, 3));?></td>
              <td><?php echo $customer;?></td>
              <td><?php echo $row["adate"];?></td>
              <td style="text-align: right;"><?php echo custom_money_format('%!i', $amount);?></td>
	      
              <!--<td>-->
              <!--<a href="<?php // echo $baseurl; ?>/view/receipt?id=<?php // echo $row["id"];?>"><button class="btn btn-xs btn-icon warn"><i class="fa fa-search-plus"></i></button></a>-->
              <!--</td>-->
          </tr>
		<?php
		}
		}
		?>
        </tbody>
        <tfoot class="hide-if-no-paging">
          <tr>
              <td colspan="5" class="text-center">
                  <ul class="pagination"></ul>
              </td>
          </tr>
        </tfoot>
      </table>
          
          
          
          
          
          
          
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->
<?php include "includes/footer.php";?>