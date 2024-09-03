<?php include "config.php";?>
<?php include "includes/menu.php";?>

<div class="app-body">
<!-- ############ PAGE START-->
<div class="padding">
  <div class="box">
    <div class="box-header">
        <?php
        $mnt=$_GET['mnt'];
        $sql2 = "SELECT name FROM maintenances where id=$mnt";
				$result2 = mysqli_query($conn, $sql2);
				if (mysqli_num_rows($result2) > 0) 
				{
				while($row2 = mysqli_fetch_assoc($result2)) 
				{
				$maintenancename=$row2["name"];
				}} 
        ?>
	<span style="float: left;"><h2>Maintenance Cash Flow Statement [<?php echo $maintenancename;?>]</h2></span> 
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
                $staff=$_GET['staff'];
		$sql = "SELECT * FROM (
(SELECT CONCAT('PRC', id) AS id, purchaser, shop, amount, date, notes FROM purchases WHERE work='maintenance' and forid=$mnt)
UNION ALL (SELECT CONCAT('PYM', id) AS id, CONCAT(wtype,work) AS purchaser, reciever, amount, date, notes FROM payments WHERE wtype='maintenance' and work=$mnt)
UNION ALL (SELECT CONCAT('WRI', id) AS id, CONCAT(wtype,work) AS purchaser, collector, paid, duedate, notes FROM work_invoices WHERE wtype='maintenance' and work=$mnt and paid>0)
)results ORDER BY STR_TO_DATE(date, '%d/%m/%Y')";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) 
		{
        while($row = mysqli_fetch_assoc($result)) {

              $idet=$row["id"];
              if (substr($idet, 0, 3) === 'PRC')
              {
              $cr=0;
              $dr=$row["amount"];
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }

              elseif (substr($idet, 0, 3) === 'PYM')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }

              elseif (substr($idet, 0, 3) === 'WRI')
              {
              $cr=$row["amount"];
              $dr=0;
              $idet=substr($idet, 0, 3).sprintf("%04d", substr($idet, 3));
              }
?>
          <tr>
              <td><?php echo $idet;?></td>
              <td><?php echo $row["date"];?></td>
              <td><?php
                                $fromid=$row["purchaser"];
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
              $toid=$row["shop"];
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
