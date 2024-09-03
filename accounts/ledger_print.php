<?php include "../config.php";?>
<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['userid']))
{
   header("Location:$baseurl/login/");
}
?>

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">-->
<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>/report/css/style.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">


	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js">
	</script>
<!--	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js">
	</script>-->
        <script type="text/javascript" language="javascript" src="js/pagination.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
<!--	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js">
	</script>-->
        <script type="text/javascript" language="javascript" src="js/pdf.js"></script>
        
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js">
	</script>
	
<style>
    table.dataTable tbody td {
    word-break: break-word;
    vertical-align: top;
    /*width:10%;*/
}
</style>	
	
<script type="text/javascript" class="init">
$(document).ready(function() {
    var table = $('#report').DataTable( {
        autoWidth: false, 
        paging: true,
        dom: 'Bfrtip',
        // buttons: ['copy', 'csv', 'excel'],
        // buttons: ['copy', 'csv', 'excel', 'pdf'],
        // "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        //         $("td:first", nRow).html(iDisplayIndex +1);
        //       return nRow;
        // },
        buttons: [
            'copy', 'csv', 'excel',
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                footer: true
            }
        ],
        order: [[1, 'asc']],
        "columnDefs": [
            { "width": "30%", "targets": 1 }
          ]
    } );
} );
</script>

<?php
if(isset($_POST))
{
$fdate = $_POST['fdate'];
    $start_date = '01/01/2018';
    $inception = 'Since Inception';
    $show_fdate = ($fdate != NULL) ? $fdate : $inception;
    $fdate = ($fdate != NULL) ? $fdate : $start_date;
$tdate = $_POST['tdate'];
}

$dateTime = DateTime::createFromFormat('d/m/Y', $fdate);
$year = $dateTime->format('Y');
?>
<title>TRIAL BALANCE[<?php echo $year;?>]</title>
<center><h1>MANCON BLOCK FACTORY.</h1>
<h2>TRIAL BALANCE [<?php echo $year;?>]</h2>
</center>

<?php
// finding RECEIVABLE OR LIABILITY
            $sql_check_receivable = "
                    SELECT id,amount FROM (
                        SELECT CONCAT('INO', id) AS id, ROUND(SUM(op_bal), 2) AS amount FROM `customers` WHERE `type`='Company' AND STR_TO_DATE(`op_date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('INV', id) AS id, ROUND(SUM(grand), 2) AS amount FROM `invoice` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RCP', id) AS id, ROUND(SUM(grand), 2) AS amount FROM `reciept` WHERE STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''
                        UNION ALL
                        SELECT CONCAT('CNT', id) AS id, ROUND(SUM(total), 2) AS amount FROM `credit_note` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                        UNION ALL
                        SELECT CONCAT('RFD', id) AS id, ROUND(SUM(amount), 2) AS amount FROM `refund` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
                    ) result";
            $query_check_receivable = mysqli_query($conn,$sql_check_receivable);
            $cr = $dr = 0;
            while($result_check_receivable = mysqli_fetch_array($query_check_receivable)) {
                
                $id = $result_check_receivable['id'];
                $receivable = $result_check_receivable['amount'];
                
                if (substr($id, 0, 3) === 'INO') {
                    $cr = $cr + $receivable;
                } else if(substr($id, 0, 3) === 'INV') {
                    $cr = $cr + $receivable;
                } else if (substr($id, 0, 3) === 'RCP') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'CNT') {
                    $dr = $dr + $receivable;
                } else if (substr($id, 0, 3) === 'RFD') {
                    $cr = $cr + $receivable;
                }
                
                $receivables = $cr-$dr;
            }

// finding PDC in Hand

    $sqlPdc = "SELECT ROUND(SUM(amount), 2) AS pdcAmount FROM `reciept` WHERE
              STR_TO_DATE(`pdate`, '%d/%m/%Y') BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y')
              AND STR_TO_DATE(`clearance_date`, '%d/%m/%Y') NOT BETWEEN STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y') AND `pdate`!=''";
    $queryPdc = mysqli_query($conn,$sqlPdc);
    $resultPdc = mysqli_fetch_array($queryPdc);
    $pdcInHand = $resultPdc['pdcAmount'];
?>


<table id="report" class="display nowrap" cellspacing="0" width="100%">
        <thead>
          <tr>
              <th>
                  Sl No
              </th>
              <th>
                  Account ID
              </th>
              <th style="text-align:center;">
                  Particular
              </th>
              <th colspan="2" style="text-align:center;">
                  Opening <?php echo $year;?>
              </th>
              <th>
                  Debit
              </th>
              <th>
                  Credit
              </th>
          </tr>
          <tr>
              <th colspan="3"></th>
              <th>Debit</th>
              <th>Credit</th>
              <th colspan="2"></th>
          </tr>
        </thead>
        <tbody>
             

   
   
   
           <?php
            $sql_main_cat = "SELECT * FROM `expense_main_categories`";
            $query_main_cat = mysqli_query($conn,$sql_main_cat);
            $cnt = $tot_main = 0;
            while($result_main_cat = mysqli_fetch_array($query_main_cat)) {
            $cnt++;
            $main_cat = $result_main_cat['id'];
            $mainUid = $result_main_cat['uid'];
            $main_cat_name = $result_main_cat['tag'];
            if ($main_cat == 1 || $main_cat == 2 || $main_cat == 7 || $main_cat == 8) {
                $entry_type = "Dr";
            } elseif ($main_cat == 3 || $main_cat == 4 || $main_cat == 5 || $main_cat == 6) {
                $entry_type = "Cr";
            }
            
                $sql_calc = "SELECT id as cat FROM `expense_categories` WHERE `type` = '$main_cat'";
                $query_calc = mysqli_query($conn,$sql_calc);
                while($result_calc = mysqli_fetch_array($query_calc)) {
                    $categ = $result_calc['cat'];
                    $sql_amt_main = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `category` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(grand) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `cat` = '$categ' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                
                                    UNION ALL
                                    (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(amount) as cd_amt, '' as db_amt FROM `refund` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT sum(-amount) as cd_amt, '' as db_amt FROM `credit_note` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    ";
                                    
                                    if($categ == '39' || $categ == '43') {
                                        $sql_amt_main .= "UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `additionalAcc` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    } else {
                                        $sql_amt_main .="UNION ALL
                                        (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$categ' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    }
                
                            $sql_amt_main .= ") result";
                                
                                // Specially Designed by Developer
                                    $sql_opnCat = "SELECT sum(amount) as opnCat FROM `account_openings` WHERE `cat` = '$categ' AND `year`='$year'";
                                    $queryopnCat = mysqli_query($conn,$sql_opnCat);
                                    $resultopnCat = mysqli_fetch_array($queryopnCat);
                                    $opnCat = $resultopnCat['opnCat'];
                                
                                $opnCat = ($opnCat != NULL) ? $opnCat : 0;

                        $query_amt_main = mysqli_query($conn,$sql_amt_main);
                        $result_amt_main = mysqli_fetch_array($query_amt_main);
                        $main_amt = $result_amt_main['amount'];
                            if($entry_type == 'Cr') {$main_amt = -($main_amt);}
                            // if($categ == '43' || $categ == '39') {$main_amt = -($main_amt);}
                            
                            $main_amt = $main_amt+$opnCat;
                            
                        $main_amt = ($main_amt != NULL) ? $main_amt : 0;
                        
                        if($entry_type == 'Dr') {
                            if($categ == '65') {
                                $main_amt = $main_amt+$receivables;
                            } else if($categ == '66') {
                                $main_amt = $main_amt+$pdcInHand;
                            }
                        }
                        
                        
                $tot_main = $tot_main + $main_amt;
                }
        ?>
            <tr>
              <td><b><?php echo $cnt;?></b></td>
              <td><b><?php echo $mainUid;?></b></td>
              <td style="font-size:18px;"><b><?php echo $main_cat_name;?></b></td>
              <td></td><td></td>
              <?php if($entry_type == 'Dr') { ?>
                <td style="text-align:right;"><b><?php echo custom_money_format('%!i', $tot_main);$main_amt=$tot_main=$opnCat=0;?></b></td>
                <td></td>
              <?php } else if($entry_type == 'Cr') { ?>
                <td></td>
                <td style="text-align:right;"><b><?php echo custom_money_format('%!i', $tot_main);$main_amt=$tot_main=$opnCat=0;?></b></td>
              <?php } ?>
              
            </tr>
            
            <?php 
                $sql_cat = "SELECT * FROM `expense_categories` WHERE `type`='$main_cat' ORDER BY `uid`";
                $query_cat = mysqli_query($conn,$sql_cat);
                while($result_cat = mysqli_fetch_array($query_cat)) {
                    $cat = $result_cat['id'];
                    $catUid = $result_cat['uid'];
                    $cat_name = $result_cat['tag'];
                    $entry_type = $result_cat['entry'];
                    
                        $sql_amt_cat = "SELECT ROUND(SUM(db_amt), 2) - ROUND(SUM(cd_amt), 2) AS amount FROM (
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `pv` WHERE `category` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                
                                    UNION ALL
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `pv` WHERE `bank` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `reciept` WHERE `cat` = '$cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(total), 2) as cd_amt, '' as db_amt FROM `invoice` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `refund` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    UNION ALL
                                    (SELECT ROUND(SUM(-amount), 2) as cd_amt, '' as db_amt FROM `credit_note` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                    
                                    ";
                                    
                                    if($cat == '39' || $cat == '43') {
                                        $sql_amt_cat .= "UNION ALL
                                        (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    } else {
                                        $sql_amt_cat .="UNION ALL
                                        (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `cat` = '$cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                    }
                    
                            $sql_amt_cat .= ") result";
                        
                                
                                // Specially Designed by Developer
                                    $sql_opnCat = "SELECT ROUND(SUM(amount), 2) as opnCat FROM `account_openings` WHERE `cat` = '$cat' AND `year`='$year'";
                                    $queryopnCat = mysqli_query($conn,$sql_opnCat);
                                    $resultopnCat = mysqli_fetch_array($queryopnCat);
                                    $opnCat = $resultopnCat['opnCat'];
                                    
                                $opnCat = ($opnCat != NULL) ? $opnCat : 0;

                        $query_amt_cat = mysqli_query($conn,$sql_amt_cat);
                        $result_amt_cat = mysqli_fetch_array($query_amt_cat);
                        $cat_amt = $result_amt_cat['amount'];
                            if($entry_type == 'Cr') {$cat_amt = -($cat_amt);}
                            // if($cat == '43' || $cat == '39') {$cat_amt = -($cat_amt);}
                            
                            $cat_amt = $cat_amt+$opnCat;
                            
                        $cat_amt = ($cat_amt != NULL) ? $cat_amt : 0;
                        
                        if($entry_type == 'Dr') {
                            if($cat == '65') {
                                $cat_amt = $cat_amt+$receivables;
                            } else if($cat == '66') {
                                $cat_amt = $cat_amt+$pdcInHand;
                            }
                            $tot_debit[] = $cat_amt;
                        } else if($entry_type == 'Cr') {
                            $tot_credit[] = $cat_amt;
                        }
            ?>
            
                <tr>
                  <td><b></b></td>
                  <td><b><?php echo $catUid;?></b></td>
                  <td style="font-size:18px;"><?php echo $cat_name;?></td>
                  <td></td><td></td>
                  <?php if($entry_type == 'Dr') { ?>
                      <td style="font-size:18px;text-align:right;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                      <td></td>
                  <?php } else if($entry_type == 'Cr') { ?>
                      <td></td>
                      <td style="font-size:18px;text-align:right;"><?php echo custom_money_format('%!i', $cat_amt);?></td>
                  <?php } ?>
                </tr>
                
                
                
<!--SUB SECTION STARTS HERE -->                
                <?php 
                    $sql_sub_cat = "SELECT * FROM `expense_subcategories` WHERE `parent`='$cat' ORDER BY `uid`";
                    $query_sub_cat = mysqli_query($conn,$sql_sub_cat);
                    while($result_sub_cat = mysqli_fetch_array($query_sub_cat)) {
                        $sub_cat = $result_sub_cat['id'];
                        $subcatUid = $result_sub_cat['uid'];
                        $sub_cat_name = $result_sub_cat['category'];
                        
                            $sql_amt_sub = "SELECT sum(db_amt)-sum(cd_amt) AS amount FROM (
                                        (SELECT sum(amount) as cd_amt, '' as db_amt FROM `jv_items` WHERE `type` = 'credit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(amount) as db_amt FROM `jv_items` WHERE `type` = 'debit' AND `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(grand) as db_amt FROM `pv` WHERE `subcategory` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(grand) as cd_amt, '' as db_amt FROM `pv` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT '' as cd_amt, sum(amount) as db_amt FROM `reciept` WHERE `inward` = '$sub_cat' AND STR_TO_DATE(clearance_date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                        
                                        UNION ALL
                                        (SELECT sum(total) as cd_amt, '' as db_amt FROM `invoice` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(amount) as cd_amt, '' as db_amt FROM `refund` WHERE `bank` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        UNION ALL
                                        (SELECT sum(-amount) as cd_amt, '' as db_amt FROM `credit_note` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))
                                        
                                        ";
                                        
                                        if($sub_cat == '176') {
                                            $sql_amt_sub .= "UNION ALL
                                            (SELECT '' as cd_amt, ROUND(SUM(amount), 2) as db_amt FROM `additionalAcc` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                        } else {
                                            $sql_amt_sub .="UNION ALL
                                            (SELECT ROUND(SUM(amount), 2) as cd_amt, '' as db_amt FROM `additionalAcc` WHERE `sub` = '$sub_cat' AND STR_TO_DATE(date, '%d/%m/%Y') BETWEEN STR_TO_DATE('$fdate', '%d/%m/%Y') AND STR_TO_DATE('$tdate', '%d/%m/%Y'))";
                                        }
                        
                        
                            $sql_amt_sub .= ") result";
                                
                                // Specially Designed by Developer
                                    $sql_opnSub = "SELECT sum(amount) as opnSub FROM `account_openings` WHERE `sub` = '$sub_cat' AND `year`='$year'";
                                    $queryopnSub = mysqli_query($conn,$sql_opnSub);
                                    $resultopnSub = mysqli_fetch_array($queryopnSub);
                                    $opnSub = $resultopnSub['opnSub'];
                                
                                $opnSub = ($opnSub != NULL) ? $opnSub : 0;
                            

                            $query_amt_sub = mysqli_query($conn,$sql_amt_sub);
                            $result_amt_sub = mysqli_fetch_array($query_amt_sub);
                            $sub_amt = $result_amt_sub['amount'];
                                if($entry_type == 'Cr') {$sub_amt = -($sub_amt);}
                                // if($sub_cat == '176') {$sub_amt = -($sub_amt);}
                            $sub_amt = $sub_amt+$opnSub;
                            $sub_amt = ($sub_amt != NULL) ? $sub_amt : 0;
                            
                            
                        if($entry_type == 'Dr') {
                            if($sub_cat == '177') {
                                $sub_amt = $sub_amt+$receivables;
                            } else if($sub_cat == '178') {
                                $sub_amt = $sub_amt+$pdcInHand;
                            }
                            $tot_opn_dr[] = $opnSub;
                        } else if($entry_type == 'Cr') {
                            $tot_opn_cr[] = $opnSub;
                        }
                if($sub_amt != 0) {
                ?>
                
                    <tr>
                      <td><b></b></td>
                      <td><b><?php echo $subcatUid;?></b></td>
                      <td style="font-size:18px;color: #bd0000;">&emsp;-> <?php echo $sub_cat_name;?></td>
                      
                      <?php if($entry_type == 'Dr') { ?>
                        <td style="text-align:right;"><b><?php if($opnSub != 0) { echo custom_money_format('%!i', $opnSub);} else { echo '';}?></b></td>
                        <td></td>
                      <?php } else if($entry_type == 'Cr') { ?>
                        <td></td>
                        <td style="text-align:right;"><b><?php if($opnSub != 0) { echo custom_money_format('%!i', $opnSub);} else { echo '';}?></b></td>
                      <?php } ?>
                        
                      <?php if($entry_type == 'Dr') { ?>
                          <td style="font-size:18px;text-align:right;"><?php echo custom_money_format('%!i', $sub_amt);?></td>
                          <td></td>
                      <?php } else if($entry_type == 'Cr') { ?>
                          <td></td>
                          <td style="font-size:18px;text-align:right;"><?php echo custom_money_format('%!i', $sub_amt);?></td>
                      <?php } ?>
                    </tr>
                
                <?php } } ?>
                
<!--SUB SECTION ENDS HERE -->
                
            <?php } ?>
            
        <?php } ?>
        
                <tr>
                    <td></td><td><b>9000</b></td><td style="text-align:center;"><b>TOTAL</b></td>
                    <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_opn_dr));?></b></td>
                    <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_opn_cr));?></b></td>
                    <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_debit));?></b></td>
                    <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_credit));?></b></td>
                </tr>
        
            <!--<tfoot>-->
            <!--    <tr>-->
            <!--        <td colspan="3"></td>-->
            <!--        <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_opn_dr));?></b></td>-->
            <!--        <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_opn_cr));?></b></td>-->
            <!--        <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_debit));?></b></td>-->
            <!--        <td style="text-align:right;"><b><?php echo custom_money_format('%!i', array_sum($tot_credit));?></b></td>-->
            <!--    </tr>-->
            <!--</tfoot>-->
             
        </tbody>
      </table>

<?php
if(isset($_POST['print']))
{?>
<body onload="window.print()">
<?php } ?>