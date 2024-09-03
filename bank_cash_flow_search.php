<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
        <?php
        $bank=$_GET["id"];
        $sql2 = "SELECT name, op_bal FROM customers where id=$bank";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
				$bankname=$row2["name"];
				$opening_balance=$row2["op_bal"];
        $opening_balance = ($opening_balance != NULL) ? $opening_balance : 0;
				}}
                                
         $fdate = $_POST['date1'];
         $tdate = $_POST['date2'];
         
         if(isset($_GET['dt1']))
         {
            $fdate = $_GET['dt1'];
            $tdate = $_GET['dt2']; 
         }
                                
        ?>
    <span style="float: left;"><h2>Bank Cash Flow Statement [<?php echo $bankname;?>] [<?php echo $fdate;?> - <?php echo $tdate;?>]</h2></span> 
    </div><br/>
    <div class="box-body">
	<span style="float: left;"></span>
    <span style="float: right;">Search: <input id="filter" type="text" class="form-control form-control-sm input-sm w-auto inline m-r"/></span>
    </div>
    <div>
      <table class="table m-b-none" data-ui-jp="footable" data-filter="#filter" data-page-size="500">
        <thead>
          <tr>
              <th data-toggle="true">
                  Code
              </th>
	      <th>
                  Date
              </th>
              <th width="20%">
                  From
              </th>
              <th width="20%">
                  To
              </th>
              <th>
                 Payment Method
              </th>
              <th>
                  Credit
              </th>
	      <th>
                  Debit
              </th>
	      <th>
                  Balance
              </th>
              <th data-hide="all">
                  Notes
              </th>
          </tr>
        </thead>
        <tbody>
		<?php
                $opening_balance=0+$opening_balance;
                $total=$opening_balance;
                $totalcr=$opening_balance;
                $totaldr=0;
?>


<tr>
<td>OPEN</td>
<td>31/09/2018</td>
<td>Opening Balance</td>
<td><?php echo $bankname;?></td>
<td></td>
<td align="right"><?php echo custom_money_format("%!i",$totalcr);?></td>
<td></td>
<td align="right"><?php echo custom_money_format("%!i",$total);?></td>
<td>Opening Balance</td>
</tr>

<?php
		$sql = "SELECT * FROM (
(SELECT CONCAT('RPT', id) AS id, customer as fromid, inward as toid, amount, clearance_date as date, cheque_no as notes, pmethod as mode FROM reciept WHERE STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND inward='$bank' AND status='Cleared')
UNION ALL 
(SELECT CONCAT('PVR', id) AS id, inward as purchaser, name as shop, amount, clearance_date as date, checkno as notes, pmethod as mode FROM voucher WHERE STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND inward='$bank' AND status='Cleared')
UNION ALL
(SELECT CONCAT('MSC', id) AS id, customer as fromid, bank as toid, total, date as date, particulars as notes, method as mode FROM miscellaneous WHERE STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND bank='$bank')
UNION ALL
  (SELECT CONCAT('PTY', id) AS id, bank as purchaser,staff as toid, amount, date as date, NULL as notes, pmethod as mode FROM petty_voucher WHERE bank='$bank' AND STR_TO_DATE(petty_voucher.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
UNION ALL
  (SELECT CONCAT('RFD', id) AS id, bank as purchaser,customer as toid, amount, date as date, NULL as notes, pmethod as mode FROM refund WHERE bank='$bank' AND STR_TO_DATE(refund.date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
)results ORDER BY STR_TO_DATE(date, '%d/%m/%Y')";


        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
		  
        while($row = mysqli_fetch_assoc($result)) {

              $idet=$row["id"];
             
              if (substr($idet, 0, 3) === 'PVR')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }

              elseif (substr($idet, 0, 3) === 'RPT')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }
              
              elseif (substr($idet, 0, 3) === 'MSC')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }
              
              elseif (substr($idet, 0, 3) === 'PTY')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }
              
              elseif (substr($idet, 0, 3) === 'RFD')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


?>
          <tr>
              <td><?php echo $idet;?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php
                $fromid=$row["fromid"];
                $subsql2 = "SELECT name FROM customers where id=$fromid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}}
              ?></td>
              <td><?php
              $toid=$row["toid"];
                                $subsql2 = "SELECT name FROM customers where id=$toid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}} 
              ?></td>
              <td><?php echo ucfirst($row["mode"]);?></td>
              <?php if($cr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$cr);?></td>
              <?php } if($dr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$dr);?></td>
              <?php } 
              $total=$total+$cr-$dr;
              $totalcr=$totalcr+$cr;
              $totaldr=$totaldr+$dr;
              ?>
              <td align="right"><?php echo custom_money_format("%!i",$total);?></td>
              <td><?php echo $row["notes"];?></td>
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
      

      <table width="100%" align="right">
          <tr>
              <td align="right" width="65%"><b>Total:</b></td>
              <td align="right" width="5%"></td>
              <td align="center" width="10%"><b><?php echo custom_money_format("%!i",$totalcr);?> Dhs</b></td>
              <td align="center" width="10%"><b><?php echo custom_money_format("%!i",$totaldr);?> Dhs</b></td>
              <td align="center" width="10%"><b><?php echo custom_money_format("%!i",$total);?> Dhs</b></td>
          </tr>
        </tfoot>
      </table>
<br/><br/>
    </div>
  </div>
</div>

<!-- ############ PAGE END-->

    </div>
  </div>
  <!-- / -->

<?php include "includes/footer.php";?>
