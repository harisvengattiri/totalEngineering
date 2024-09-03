<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
        <?php
        $shop=$_GET['cid'];
        $sql2 = "SELECT name FROM customers where id=$shop";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
				$shopname=$row2["name"];
				}} 
        ?>
	<span style="float: left;"><h2>Cash Flow Statement [<?php echo $shopname;?>]</h2></span> 
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
              <th>
                  From
              </th>
              <th>
                  To
              </th>
              <th>
                  Cr
              </th>
	      <th>
                  Dr
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
                $total=0;
                $totalcr=0;
                $totaldr=0;
                $receiver=$_GET['cid'];
		$sql = "SELECT * FROM (
(SELECT CONCAT('CST', id) AS id, fromid, toid, amount, date, notes FROM credit_settlements WHERE toid='$receiver')
UNION ALL (SELECT CONCAT('PRC', id) AS id, purchaser, shop, amount, date, CONCAT(status, notes) AS notes FROM purchases where shop=$receiver)
UNION ALL (SELECT CONCAT('OXP', id) AS id, purchaser, shop, amount, date, notes FROM office_expenses where shop=$receiver)
UNION ALL (SELECT CONCAT('VXP', id) AS id, purchaser, shop, amount, date, notes FROM vehicle_expenses where shop=$receiver)
)results ORDER BY STR_TO_DATE(date, '%d/%m/%Y')";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $idet=$row["id"];
              $notes=$row["notes"];

              if (substr($idet, 0, 3) === 'PRC')
              {
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              if (substr($notes, 0, 1) === 'c')
              {
              $cr=$row["amount"];
              $dr=0;
              $notes=substr($notes, 6);
              }
              else
              {
              $cr=$row["amount"];
              $dr=$row["amount"];
              $notes=substr($notes, 5);
              }
              }


              elseif (substr($idet, 0, 3) === 'OXP')
              {
              $cr=$row["amount"];
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }


              elseif (substr($idet, 0, 3) === 'VXP')
              {
              $cr=$row["amount"];
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }



              elseif (substr($idet, 0, 3) === 'CST')
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
                                if (substr($fromid,0,11) === 'maintenance')
                                {
                                $fid=substr($fromid, 11);
                                $sql3 = "SELECT name FROM maintenances where id=$fid";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				echo "[MNT".sprintf("%04d", substr($fromid, 11))."] ".$row3["name"];
				}}}

                                elseif (substr($fromid,0,7) === 'project')
                                {
                                $fid=substr($fromid, 7);
                                $sql3 = "SELECT name FROM projects where id=$fid";
				$result3 = mysqli_query($conn, $sql3);
				if (mysqli_num_rows($result3) > 0) 
				{
				while($row3 = mysqli_fetch_assoc($result3)) 
				{
				echo "[PRJ".sprintf("%04d", substr($fromid, 7))."] ".$row3["name"];
				}}}

                                else
                                {
                                $subsql2 = "SELECT name FROM customers where id=$fromid";
				$subresult2 = mysqli_query($conn, $subsql2);
				if (mysqli_num_rows($subresult2) > 0) 
				{
				while($subrow2 = mysqli_fetch_assoc($subresult2)) 
				{
				echo $subrow2["name"];
				}}}
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

              <?php if($cr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$cr);?> Dhs</td>
              <?php } if($dr==0) {?>
              <td align="right"></td>
              <?php } else {?>
              <td align="right"><?php echo custom_money_format("%!i",$dr);?> Dhs</td>
              <?php } 
              $total=$total+$cr-$dr;
              $totalcr=$totalcr+$cr;
              $totaldr=$totaldr+$dr;
              ?>
              <td align="right"><?php echo custom_money_format("%!i",$total);?> Dhs</td>
              <td><?php echo $notes;?></td>
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
