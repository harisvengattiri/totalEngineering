<?php

//  date('01/m/Y', strtotime('-3 month'));

//  date("d/m/Y");
 
 
// $original_date = "2019-03-31";
 
// Creating timestamp from given date
// $timestamp = strtotime($original_date);
 
// Creating new date format from that timestamp
// $new_date = date("d-m-Y", $timestamp);
// $new_date; // Outputs: 31-03-2019
 
 
 
 
// $date1 = "31/01/2020";
// $date = str_replace('/','-',$date1);
// echo $year = date('Y', strtotime($date));

// $month = date('m', strtotime($date));

// $year = date('Y', strtotime($date));
// $year_start = '01/01/'.$year;

// $a=6250231.29;
// $b=6244100.96;

// $c=$a/$b*100;



// $ip=$_SERVER['REMOTE_ADDR'];
// echo $ip;
// $ip_details=json_decode(file_get_contents("http://ipinfo.io/$ip/json"));
// print_r($ip_details);
// echo $ip_details->city;


// $date = DateTime::createFromFormat("Y-m-d", "2012-10-10");
// echo $date->format("Y");

// $idet = 'RCP1256';
// $id=substr($idet, 3);
// echo $id;
?>





<?php
include "config.php";


        // $sql = "SELECT (sum(grand)-(sum(received)+sum(credit))) as balance FROM (
        //             (SELECT t.grand as grand,0 as received,0 as credit FROM test t
        //             WHERE t.cust=8 AND t.status='')
        //             UNION ALL
        //             (SELECT grand as grand, ROUND(`total1`,2) as received, ROUND(`total2`,2) as credit FROM test
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total1 FROM test1 GROUP BY inv) as t1 ON test.id=t1.inv
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total2 FROM test2 GROUP BY inv) as t2 ON test.id=t2.inv
        //             WHERE test.cust=8 AND test.status='Partially')
        //         ) result
        //         ";


            // SET BY REAL MANCON TABLES
                // $sql = "
                // (SELECT i.grand as grand, 0 as received, 0 as credit FROM invoice i
                // WHERE i.grand > 0 AND i.customer=1449 AND i.status = '')
                // UNION ALL
                // (SELECT grand as grand, ROUND(`total10`,2) as received, ROUND(`total2`,2) as credit FROM invoice
                //     LEFT JOIN (SELECT invoice, ROUND(sum(`total`),2) as total10 FROM reciept_invoice GROUP BY invoice) as t1 ON invoice.id=t1.invoice
                //     LEFT JOIN (SELECT invoice, ROUND(sum(`total`),2) as total2 FROM credit_note GROUP BY invoice) as t2 ON invoice.id=t2.invoice
                // WHERE invoice.customer=1449 AND invoice.status='Partially')
                // ";



        // $sql = "SELECT ROUND(`grand`,2) as grand,(ROUND(`received`,2)+ROUND(`credit`,2)) as tot_received FROM (
        //             (SELECT grand as grand, ROUND(`total1`,2) as received, ROUND(`total2`,2) as credit FROM test
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total1 FROM test1 GROUP BY inv) as t1 ON test.id=t1.inv
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total2 FROM test2 GROUP BY inv) as t2 ON test.id=t2.inv
        //                 WHERE test.cust=8)
        //             ) result
        //         ";

        // $sql = "SELECT grand,(received+credit) as tot_received FROM (
        //             (SELECT grand as grand,total1 as received, total2 as credit FROM test
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total1 FROM test1 GROUP BY inv) as t1 ON test.id=t1.inv
        //                 LEFT JOIN (SELECT inv, ROUND(sum(`total`),2) as total2 FROM test2 GROUP BY inv) as t2 ON test.id=t2.inv
        //                 WHERE test.cust=8)
        //             ) result
        //         ";


        // $sql = "
        //     SELECT grand as grand,total1 as received, total2 as credit FROM test
        //     LEFT JOIN (SELECT inv, sum(total) as total1 FROM test1 GROUP BY inv) as t1 ON test.id=t1.inv
        //     LEFT JOIN (SELECT inv, sum(total) as total2 FROM test2 GROUP BY inv) as t2 ON test.id=t2.inv
        //     WHERE test.cust=8
        //         ";

// $query = mysqli_query($conn,$sql);
// while($fetch = mysqli_fetch_array($query)){

// echo $fetch['grand'].'->'.$fetch['received'].'->'.$fetch['credit'].'<br>';
// echo $fetch['grand'].'->'.$fetch['tot_received'].'<br>';

// echo $fetch['balance'].'<br>';


// }


// $test0 = 5;
// $test1 = 10;
// $test2 = 12;

// for($i=0;$i<3;$i++){
//     echo $test.$i;
// }


    // $sql = "SELECT id,name FROM customers WHERE `type` = 'Company'";
    // $query = mysqli_query($conn,$sql);
    // while($fetch = mysqli_fetch_array($query)) {
    //     $cid = $fetch['id'];
    //         $sql_po = "SELECT id,salesrep FROM `sales_order` WHERE `customer` = '$cid' ORDER BY id DESC LIMIT 1";
    //         $query_po = mysqli_query($conn,$sql_po);
    //         while($fetch_po = mysqli_fetch_array($query_po)) {
    //             $po_rep = $fetch_po['salesrep'];
    //         }
    //         $sql_cst = "UPDATE `customers123` SET `slmn` = '$po_rep' WHERE `id`= '$cid'";
    //         $query_cst = mysqli_query($conn,$sql_cst);
    // }
    
    
            // $sql_cust = "SELECT cs.id as cust_id,cs.name as cust_name,cs_slmn.name as slman,
            //      cs.cust_type as pterm1,cs.period as pterm2,cda.credit as cdt1,cda.credit1 as cdt2
            //      FROM `invoice` i
            //      INNER JOIN `customers` cs ON i.customer = cs.id
            //      INNER JOIN `customers` cs_slmn ON cs.slmn = cs_slmn.id
            //      LEFT JOIN `credit_application` cda ON i.customer = cda.company
            //      WHERE i.status != 'Paid' $date_sql $staff_sql $type_sql $period_sql AND cs.id=1 GROUP BY i.customer ORDER BY cs.period";
            // $query_cust = mysqli_query($conn,$sql_cust);
            // while($fetch_cust = mysqli_fetch_array($query_cust))
            // {
            //     echo $cust_name = $fetch_cust['cust_name'];
            //     echo '<br>'. $cust_name = $fetch_cust['slman'];
            // }
            
            
            
        // $sql = "SELECT * FROM delivery_note WHERE customer = '2715' AND invoiced = 'yes'"  ;
        // $query = mysqli_query($conn,$sql);
        // while( $result = mysqli_fetch_array($query)) {
            
        //     $dn = $result['id'];
        //     $dn_amt = $result['total'];
            
        //     $sql1 = "SELECT sum(total) as invoiced FROM invoice_item WHERE dn='$dn'";
        //     $query1 = mysqli_query($conn,$sql1);
        //     $result1 = mysqli_fetch_array($query1);
        //     $inv_amt = $result1['invoiced'];
            
        //     if($dn_amt > $inv_amt) {
        //       echo $dn. '----' . $dn_amt. '----'. $inv_amt .'<br>';
        //     }
        // }
        
        // $sql = "SELECT * FROM invoice WHERE customer = '2757'"  ;
        // $query = mysqli_query($conn,$sql);
        // while( $result = mysqli_fetch_array($query)) {
            
        //     $inv = $result['id'];
        //     $inv_tot = $result['grand'];
            
        //     $sql1 = "SELECT sum(total) as receipted FROM reciept_invoice WHERE invoice='$inv'";
        //     $query1 = mysqli_query($conn,$sql1);
        //     $result1 = mysqli_fetch_array($query1);
        //     $rcp_amt = $result1['receipted'];
            
        //     if($inv_tot < $rcp_amt) {
        //       echo $inv. '----' . $inv_tot. '----'. $rcp_amt .'<br>';
        //     }
        // }
        
        
// UPDATE PARENT ID LATER AFTER FIXING SUBCATEGORY

    // $sql = "SELECT * FROM `petty_item` WHERE `find1`=''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    //     $id = $result['id'];
    //     $sub = $result['sub'];

    //     $sql1 = "SELECT `parent` FROM `expense_subcategories` WHERE `id`='$sub'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $parent_id = $result1['parent'];

    //     $sql2 = "UPDATE `petty_item` SET `type`='$parent_id',`find1`='1' WHERE `id`='$id'";
    //     $query2 = mysqli_query($conn,$sql2);
    // }


    // $sql = "SELECT * FROM `petty_item`";
    // $sql = "SELECT * FROM `petty_item` WHERE `find`=''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {

    //     $id = $result['id'];
    //     $sub = $result['sub'];

    //     $sql1 = "SELECT `category` FROM `expense_subcategories_old` WHERE `id`='$sub'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $sub_old_name = $result1['category'];

    //     echo $sub_old_name.'<br>';

        // $sql2 = "SELECT `id` FROM `expense_subcategories` WHERE `category`='$sub_old_name'";
        // $query2 = mysqli_query($conn,$sql2);
        // if(mysqli_num_rows($query2) > 0) {
        //     $result2 = mysqli_fetch_array($query2);
        //     $sub_id_new = $result2['id'];

        //     $sql3 = "UPDATE `petty_item` SET `find`='1' WHERE `id`='$id'";
        //     $query3 = mysqli_query($conn,$sql3);
        // }
    // }
    
// UPDATING NEW JV AND JV_ITEMS USING OLD JOUNAL TABLE

// $sql = "SELECT * FROM `journal`";
// $query = mysqli_query($conn,$sql);
// while($result = mysqli_fetch_array($query)) {

//     $id = $result['id'];

//     $crdt_cat = $result['crdt_cat'];
//     $crdt_sub = $result['crdt_sub'];
//     $crdt_amount = $result['crdt_amount'];
//     $crdt_vat = $result['crdt_vat'];
//     $crdt_total = $result['crdt_total'];

//     $debt_cat = $result['debt_cat'];
//     $debt_sub = $result['debt_sub'];
//     $debt_amount = $result['debt_amount'];
//     $debt_vat = $result['debt_vat'];
//     $debt_total = $result['debt_total'];

//     $voucher = $result['voucher'];
//     $date = $result['date'];
//     $year = $result['year'];
//     $inv = $result['inv'];
//     $note = $result['note'];
//     $time = $result['time'];

//     $sql1 = "INSERT INTO `jv`(`id`, `voucher`, `date`, `year`, `inv`, `note`, `time`)
//              VALUES ('$id','$voucher','$date','$year','$inv','$note','$time')";
//     $query1 = mysqli_query($conn,$sql1);

//     $sql2 = "INSERT INTO `jv_items`(`id`, `jv`, `date`, `type`, `cat`, `sub`, `amount`, `vat`, `total`)
//              VALUES ('','$id','$date','credit','$crdt_cat','$crdt_sub','$crdt_amount','$crdt_vat','$crdt_total')";
//     $query2 = mysqli_query($conn,$sql2);
    
//     $sql3 = "INSERT INTO `jv_items`(`id`, `jv`, `date`, `type`, `cat`, `sub`, `amount`, `vat`, `total`)
//              VALUES ('','$id','$date','debit','$debt_cat','$debt_sub','$debt_amount','$debt_vat','$debt_total')";
//     $query3 = mysqli_query($conn,$sql3); 
// }


    // $sql = "SELECT * FROM `credit_note` WHERE `vat` > 0";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    
    // $credit_note = $result['id'];
    // $date = $result['date'];
    // $vat = $result['vat'];

    //         $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
    //                           VALUES ('','CNT','$credit_note','$date','39','','$vat')";
    //         $conn->query($sql_adtnl_vat);
            
    // }
    
    
    // $sql = "SELECT * FROM `credit_note` WHERE `type` = ''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {

    //     $id = $result['id'];
        
    //     $sql1 = "SELECT * FROM `credit_note_items` WHERE `cr_id`='$id' AND `amt` > 0";
    //     $query1 = mysqli_query($conn,$sql1);
    //     while($result1 = mysqli_fetch_array($query1)) {
            
    //         $price = $result1['price'];
    //         $adjust = $result1['adjust'];
            
    //         if($price != $adjust) {
    //             $sql2 = "UPDATE `credit_note` SET `type` = '2' WHERE `id`='$id'";
    //             $query2 = mysqli_query($conn,$sql2);
    //         }
    //     }
    // }
    
    
    // UPDATING TABLE PV WITH LATEST CAT OF SUBCAT

    // $sql = "SELECT * FROM `pv` WHERE `subcategory` != ''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {

    //     $pv_id = $result['id'];
    //     $cat = $result['category'];
    //     $sub = $result['subcategory'];

    //     $sql1 = "SELECT `parent` FROM `expense_subcategories` WHERE `id`='$sub'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $new_cat = $result1['parent'];

    //     if($cat != $new_cat) {
    //         echo $pv_id.'<br>';
    //     }
    // }
    
    
    // $sql = "SELECT invoice FROM `reciept_invoice` WHERE `reciept_id`>17430";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    //     $inv = $result['invoice'];
        
    //     $sql1 = "SELECT grand,status FROM `invoice` WHERE `id`='$inv'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $grand = $result1['grand'];
    //     $status = $result1['status'];
        
    //     $sql2 = "SELECT sum(total) AS rcp FROM `reciept_invoice` WHERE `invoice`='$inv'";
    //     $query2 = mysqli_query($conn,$sql2);
    //     $result2 = mysqli_fetch_array($query2);
    //     $rcp = $result2['rcp'];
        
    //     $sql3 = "SELECT sum(total) AS crd FROM `credit_note` WHERE `invoice`='$inv'";
    //     $query3 = mysqli_query($conn,$sql3);
    //     $result3 = mysqli_fetch_array($query3);
    //     $crd = $result3['crd'];
        
    //     $receipted = $rcp+$crd;
        
    //     if($grand == $receipted) {
    //         $status = 'Paid';
    //     } else if($grand > $receipted) {
    //         $status = 'Partially';
    //     }
        
    //     $sql4 = "UPDATE `invoice` SET `status`='$status' WHERE `id`='$inv'";
    //     $query4 = mysqli_query($conn,$sql4);
        
    //     echo $inv.'---'.$grand.'-'.$rcp.'-'.$crd.'---'.$status.'<br>';
    // }
    
    
    
    
    
// BILLS RECEIVABLES USING Y-m-d FORMAT  
//     $start_date = DateTime::createFromFormat('d/m/Y', '01/01/2023')->format('Y-m-d');
//     $end_date = DateTime::createFromFormat('d/m/Y', '31/01/2023')->format('Y-m-d');
    
//     $sql = "
    
//     SELECT coalesce(sum(amount),0) as TotalAmount
//     FROM test123 AS T1
//     WHERE
//     date >= '$start_date' AND date <= '$end_date'
//     AND 
//     (
//      section = 'INV' OR 
//     (section = 'RCP' AND
//     EXISTS (
//     SELECT 1
//     FROM test123 AS T2
//     WHERE
//       T2.section = 'INV'
//       AND T2.invoice = T1.invoice
//       AND T2.date >= '$start_date'
//       AND T2.date <= '$end_date'
//   ))
//   )
  
//     ";
//     $query = mysqli_query($conn,$sql);
//     $result = mysqli_fetch_array($query);
//     $total_amount = $result['TotalAmount'];
//     echo $total_amount;
    



// --------------------------------------------------------------------------------------------------------------------------    
    // UPDATING IN 4 STEPS FOR ADDITIONAL TABLE FOR RECEIVABLES
    
    // STEP 1 UPDATE INVOICE TO IT
    // START
    // $sql = "SELECT * FROM `invoice`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
 
    //     $invoice = $result['id'];
    //     $date = $result['date'];
    //     $grand = $result['grand'];
        
    //     $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
    //                       VALUES ('','INV','$invoice','$invoice','$date','65','','$grand')";
    //     $conn->query($sql_adtnl_inv);
    // }
    // STOP
    
    // STEP 2 UPDATE RECEIPT INNER TABLE TO IT
    // START
    // $sql = "SELECT * FROM `reciept_invoice`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $reciept_id = $result['reciept_id'];
    //     $invoice = $result['invoice'];
    //     $pdate = $result['date'];
    //     $adjust = $result['adjust'];
    //     $total = $result['total'];
        
    //         $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
    //                           VALUES ('','RCP','$reciept_id','$invoice','','65','','-$total')";
    //         $conn->query($sql_adtnl_inv);
    //         if($adjust > 0) {
    //             $sql_adtnl_dis = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
    //                               VALUES ('','DIS','$reciept_id','$invoice','$pdate','43','','$adjust')";
    //             $conn->query($sql_adtnl_dis);
    //         }
    // }
    // STOP
    
    // STEP 3 UPDATE CREDIT NOTE TABLE TO IT
    // START
    // $sql = "SELECT * FROM `credit_note`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $crdt = $result['id'];
    //     $invoice = $result['invoice'];
    //     $date = $result['date'];
    //     $total = $result['total'];
        
    //         $sql_adtnl_inv = "INSERT INTO `additionalRcp`(`id`, `section`, `entry_id`, `invoice`, `date`, `cat`, `sub`, `amount`)
    //                           VALUES ('','CNT','$crdt','$invoice','$date','65','','-$total')";
    //         $conn->query($sql_adtnl_inv);
    // }
    // STOP
    
    // STEP 4 UPDATE CLEARANCE DATE FROM RECEIPT TO IT
    // START
    // $sql = "SELECT `id`,`entry_id` FROM `additionalRcp` WHERE `section`='RCP' AND `cat`='65' AND `date`=''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $id = $result['id'];
    //     $rcp = $result['entry_id'];
        
    //     $sql1 = "SELECT `clearance_date` FROM `reciept` WHERE `id`='$rcp'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $cdate = $result1['clearance_date'];
        
    //     $sql2 = "UPDATE `additionalRcp` SET `date`='$cdate' WHERE `id`='$id'";
    //     $query2 = mysqli_query($conn,$sql2);
    // }
    // STOP
// --------------------------------------------------------------------------------------------------------------------------
       
       
       
       
    // $sql = "SELECT `reciept_id` AS rcp FROM `reciept_invoice`";
    // $query = mysqli_query($conn,$sql);
    // $cal=0;
    // while($result = mysqli_fetch_array($query)) {
        
    //     $rcp = $result['rcp'];
        
    //     $sql1 = "SELECT `clearance_date` FROM `reciept` WHERE `id`='$rcp'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $cdate = $result1['clearance_date'];
        
    //     if($cdate == NULL) {$cal++;}
    //     echo $cal;
    // }
    
    
    
    // SELECT coalesce(sum(amount),0) as TotalAmount FROM test123 AS T1
    // WHERE
    // STR_TO_DATE(date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // AND 
    // (section = 'INV' OR 
    // (section = 'RCP' AND
    // EXISTS (
    // SELECT 1 FROM test123 AS T2 WHERE T2.section = 'INV' AND T2.invoice = T1.invoice
    //   AND STR_TO_DATE(T2.date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') 
    //   AND STR_TO_DATE(T2.date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // )) OR
    // (section = 'CNT' AND
    // EXISTS (
    // SELECT 1 FROM test123 AS T3 WHERE T3.section = 'INV' AND T3.invoice = T1.invoice
    //   AND STR_TO_DATE(T3.date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') 
    //   AND STR_TO_DATE(T3.date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // ))
    // )
    

    // $start_date = '01/01/2023';
    // $end_date = '30/02/2023';
    // $sql = "
    // SELECT coalesce(sum(amount),0) as TotalAmount FROM test123 AS T1
    // WHERE
    // STR_TO_DATE(date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // AND 
    // (section = 'INV' OR 
    // (section = 'RCP' AND
    // EXISTS (
    // SELECT 1 FROM test123 AS T2 WHERE T2.section = 'INV' AND T2.invoice = T1.invoice
    //   AND STR_TO_DATE(T2.date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') 
    //   AND STR_TO_DATE(T2.date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // )) OR
    // (section = 'CNT' AND
    // EXISTS (
    // SELECT 1 FROM test123 AS T3 WHERE T3.section = 'INV' AND T3.invoice = T1.invoice
    //   AND STR_TO_DATE(T3.date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') 
    //   AND STR_TO_DATE(T3.date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // ))
    // )
    // ";
    // $query = mysqli_query($conn,$sql);
    // $result = mysqli_fetch_array($query);
    // $total_amount = $result['TotalAmount'];
    // echo $total_amount;
    
    
    
    // $start_date = '01/01/2023';
    // $end_date = '30/02/2023';
    // $sql = "
    // SELECT coalesce(sum(amount),0) as TotalAmount FROM test123 AS T1
    // WHERE STR_TO_DATE(date, '%d/%m/%Y') >= STR_TO_DATE('$start_date', '%d/%m/%Y') AND STR_TO_DATE(date, '%d/%m/%Y') <= STR_TO_DATE('$end_date', '%d/%m/%Y')
    // AND 
    // (section = 'INV' OR 
    // (section = 'RCP' AND EXISTS ( SELECT 1 FROM test123 AS T2 WHERE T2.section = 'INV' AND T2.invoice = T1.invoice)) OR
    // (section = 'CNT' AND EXISTS ( SELECT 1 FROM test123 AS T3 WHERE T3.section = 'INV' AND T3.invoice = T1.invoice)))";
    // $query = mysqli_query($conn,$sql);
    // $result = mysqli_fetch_array($query);
    // $total_amount = $result['TotalAmount'];
    // echo $total_amount;
    
    // $sql = "SELECT `date`,`id` FROM `additionalRcp123`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $id = $result['id'];
    //     $inputDate = $result['date'];
        
    //     if($inputDate != NULL){
    //     $convertedDate = date('Y-m-d', strtotime(str_replace('/', '-', $inputDate)));
        
    //     $sql1 = "UPDATE `additionalRcp123` SET `date`='$convertedDate',`sub`='1' WHERE `id`='$id'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     }
        
    // }
    
// SELECT coalesce(sum(amount),0) as TotalAmount FROM additionalRcp123 AS T1
//     WHERE
//     date BETWEEN '2023-01-01' AND '2023-09-30'
    
//     AND 
//     (section = 'INV' OR 
//     (section = 'RCP' AND
//     EXISTS (
//     SELECT 1 FROM additionalRcp123 AS T2 WHERE T2.section = 'INV' AND T2.invoice = T1.invoice AND T2.date BETWEEN '2023-01-01' AND '2023-09-30'
//     ))
//     )
    
    
    
    
    // $sql = "SELECT `invoice`,`amount` FROM `additionalRcp123` WHERE `section` = 'INV' AND `date` BETWEEN '2023-01-01' AND '2023-09-31'";
    // $query = mysqli_query($conn,$sql);
    // $receivables=0;
    // while($result = mysqli_fetch_array($query)) {
        
    //     $invoice = $result['invoice'];
    //     $invoicedAmount = $result['amount'];
    //     $invoicedAmount = ($invoicedAmount != NULL) ? $invoicedAmount : 0;
        
    //         $sqlReceipt = "SELECT sum(amount) AS receiptedAmount FROM `additionalRcp123` WHERE `section` != 'INV' AND `invoice` = '$invoice'
    //                       AND `date` BETWEEN '2023-01-01' AND '2023-09-31'";
    //         $queryReceipt = mysqli_query($conn,$sqlReceipt);
    //         $resultReceipt = mysqli_fetch_array($queryReceipt);
    //         $receiptedAmount = $resultReceipt['receiptedAmount'];
    //         $receiptedAmount = ($receiptedAmount != NULL) ? $receiptedAmount : 0;
            
    //     $receivables = ($receivables+$invoicedAmount+$receiptedAmount);
    // }
    
    
    // $sql = "SELECT `invoice`,`amount` FROM `additionalRcp` WHERE `section` = 'INV' AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2023', '%d/%m/%Y') AND STR_TO_DATE('31/05/2023', '%d/%m/%Y')";
    // $query = mysqli_query($conn,$sql);
    // $receivables=0;
    // while($result = mysqli_fetch_array($query)) {
        
    //     $invoice = $result['invoice'];
    //     $invoicedAmount = $result['amount'];
    //     $invoicedAmount = ($invoicedAmount != NULL) ? $invoicedAmount : 0;
        
    //         $sqlReceipt = "SELECT sum(amount) AS receiptedAmount FROM `additionalRcp` WHERE `section` != 'INV' AND `invoice` = '$invoice' AND STR_TO_DATE(`date`, '%d/%m/%Y') BETWEEN STR_TO_DATE('01/01/2023', '%d/%m/%Y') AND STR_TO_DATE('31/05/2023', '%d/%m/%Y')";
    //         $queryReceipt = mysqli_query($conn,$sqlReceipt);
    //         $resultReceipt = mysqli_fetch_array($queryReceipt);
    //         $receiptedAmount = $resultReceipt['receiptedAmount'];
    //         $receiptedAmount = ($receiptedAmount != NULL) ? $receiptedAmount : 0;
            
    //     $receivables = ($receivables+$invoicedAmount+$receiptedAmount);
    // }
    
    
    // $sql = "SELECT * FROM `petty_item` WHERE `petty`='92'";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $date = $result['date'];
    //     $staff = $result['staff'];
    //     $type = $result['type'];
    //     $description = $result['description'];
    //     $amount = $result['amount'];
    //     $vat = $result['vat'];
    //     $total = $result['total'];
    
    //     $sql1 = "
    //     INSERT INTO `petty_item_new`(`id`, `petty`, `vch`, `date`, `staff`, `type`, `sub`, `description`, `amount`, `vat`, `total`)
    //     VALUES
    //     ('','92','','$date','$staff','$type','','$description','$amount','$vat','$total')
    //     ";
    //     $query1 = mysqli_query($conn,$sql1);
    // }
    
//     SELECT inv_test.id,inv_test.date,inv_test.amount,sum(inv_rcp_test.amount) FROM inv_test
// LEFT JOIN inv_rcp_test ON inv_test.id=inv_rcp_test.invoice
// WHERE STR_TO_DATE(inv_test.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('01/10/2020','%d/%m/%Y') AND STR_TO_DATE('15/10/2020','%d/%m/%Y')
// 	AND STR_TO_DATE(inv_rcp_test.date,'%d/%m/%Y') BETWEEN STR_TO_DATE('01/10/2020','%d/%m/%Y') AND STR_TO_DATE('15/10/2020','%d/%m/%Y')
// GROUP BY inv_rcp_test.invoice


// SELECT invoice, MAX(id) AS latest_id, amount
// FROM your_table
// GROUP BY invoice, amount;



// TO DO NEXT
    // $sql = "SELECT id,invoice FROM `additionalRcp` WHERE `invoice_date`='' AND `section`='CNT'";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $id = $result['id'];
    //     $invoice = $result['invoice'];
        
    //         $sql_inv_date = "SELECT `date` FROM `invoice` WHERE `id`='$invoice'";
    //         $query_inv_date = mysqli_query($conn,$sql_inv_date);
    //         $result_inv_date = mysqli_fetch_array($query_inv_date);
    //         $invoice_date = $result_inv_date['date'];
        
    //     $sql1 = "UPDATE `additionalRcp` SET `invoice_date`='$invoice_date' WHERE `id`='$id' AND `invoice`='$invoice'";
    //     $query1 = mysqli_query($conn,$sql1);
    // }
    
    // $sql = "SELECT id,invoice FROM `reciept_invoice` WHERE `invoice_date`=''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $id = $result['id'];
    //     $invoice = $result['invoice'];
        
    //         $sql_inv_date = "SELECT `date` FROM `invoice` WHERE `id`='$invoice'";
    //         $query_inv_date = mysqli_query($conn,$sql_inv_date);
    //         $result_inv_date = mysqli_fetch_array($query_inv_date);
    //         $invoice_date = $result_inv_date['date'];
        
    //     $sql1 = "UPDATE `reciept_invoice` SET `invoice_date`='$invoice_date' WHERE `id`='$id' AND `invoice`='$invoice'";
    //     $query1 = mysqli_query($conn,$sql1);
    // }
    
    
    // UPDATION OF JOURANAL WITH OLD PETTY CASH
    
    // set_time_limit(0);
    
    // $sql = "SELECT * FROM `petty_cash_new`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    
    //     $petty = $result['id'];
    //     $date = $result['date'];
    //         $dateTime = DateTime::createFromFormat('d/m/Y', $date);
    //         $yr = $dateTime->format('y');
        
    //             $sqlvou="SELECT voucher from jv WHERE year='$yr' ORDER BY voucher DESC LIMIT 1";
    //             $querycust=mysqli_query($conn,$sqlvou);
    //             $fetchcust=mysqli_fetch_array($querycust);
    //             $voucher=$fetchcust['voucher'];
    //             $vou=$voucher+1;
        
    //         $sql1 = "INSERT INTO `jv`(`id`, `voucher`, `date`, `year`, `inv`, `note`, `time`)
    //                 VALUES ('','$vou','$date','$yr','','Petty Cash Old','')";
    //         $query1 = mysqli_query($conn,$sql1);
    //         $last_id = $conn->insert_id;
        
    //         $sql2 = "SELECT * FROM `petty_item_new` WHERE `petty`='$petty'";
    //         $query2 = mysqli_query($conn,$sql2);
    //         while($result2 = mysqli_fetch_array($query2)) {
                
    //             $cat = $result2['type'];
    //             $sub = $result2['sub'];
    //             $des = $result2['description'];
                
    //             $amount = $result2['amount'];
    //             $vat = $result2['vat'];
    //             $vat = ($vat != NULL) ? $vat : 0;
    //             $total = $result2['total'];
                
    //             $sql3 = "INSERT INTO `jv_items`(`id`, `jv`, `date`, `type`, `note`, `cat`, `sub`, `amount`, `vat`, `total`)
    //                      VALUES ('','$last_id','$date','debit','$des','$cat','$sub','$amount','$vat','$total')";
    //             $query3 = mysqli_query($conn,$sql3);
                
    //                 if($vat != 0) {
    //                     $sql_adtnl_vat = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
    //                                       VALUES ('','JNL','$last_id','$date','39','','$vat')";
    //                     $conn->query($sql_adtnl_vat);
    //                 }
    //         }
            
    //     $c_amount = $result['amount'];
    //     $c_vat = $result['vat'];
    //     $c_vat = ($c_vat != NULL) ? $c_vat : 0;
    //     $c_total = $result['total'];
            
    //         $sql4 = "INSERT INTO `jv_items`(`id`, `jv`, `date`, `type`, `note`, `cat`, `sub`, `amount`, `vat`, `total`)
    //                      VALUES ('','$last_id','$date','credit','Petty Cash','69','','$c_amount','$c_vat','$c_total')";
    //         $query4 = mysqli_query($conn,$sql4);
                
    //             if($c_vat != 0) {
    //                 $sql_adtnl_vat1 = "INSERT INTO `additionalAcc`(`id`, `section`, `entry_id`, `date`, `cat`, `sub`, `amount`)
    //                                   VALUES ('','JNL','$last_id','$date','27','','$c_vat')";
    //                 $conn->query($sql_adtnl_vat1);
    //             }
    // }
    
    
    // set_time_limit(0);
    
    // $sql = "SELECT * FROM `jv` WHERE `note` = 'Petty Cash Old'";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $jv = $result['id'];
        
    //     $sql1 = "UPDATE `jv_items` SET `amount`=`total`,`vat`='0' WHERE `jv`='$jv' AND `type`='credit' AND `cat`='69'";
    //     $query1 = mysqli_query($conn,$sql1);
        
    //     $sql2 = "DELETE FROM `additionalAcc` WHERE `section`='JNL' AND `entry_id`='$jv' AND `cat`='27'";
    //     $query2 = mysqli_query($conn,$sql2);
    // }
    
    
    // UPDATING TABLE JOURNAL WITH LATEST CAT OF SUBCAT
    
    // $sql = "SELECT * FROM `jv_items` WHERE `sub` != ''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {

    //     $jv_id = $result['id'];
    //     $cat = $result['cat'];
    //     $sub = $result['sub'];

    //     $sql1 = "SELECT `parent` FROM `expense_subcategories` WHERE `id`='$sub'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $new_cat = $result1['parent'];

    //     if($cat != $new_cat) {
    //         echo $jv_id.'<br>';
    //     }
    // }
    
 
    
    // $sql = "SELECT * FROM `jv_items` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') > STR_TO_DATE('01/06/2023', '%d/%m/%Y') AND STR_TO_DATE(`date`, '%d/%m/%Y') < STR_TO_DATE('31/06/2023', '%d/%m/%Y')";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $cat = $result['cat'];
    //     $jv = $result['jv'];
    //     $type = $result['type'];
    //     if($type == 'credit'){$type='Cr';}else{$type='Dr';}
        
    //     $sql1 = "SELECT * FROM `expense_categories` WHERE `id`='$cat'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $entry = $result1['entry'];
        
    //     if($entry == $type) {
    //         echo $jv.'<br>';
    //     }
    // }
    
    
    // TO SURAIJ
    
    // SQL TO TRY OUT
    // SELECT * FROM `jv_items` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') < STR_TO_DATE('01/01/2022', '%d/%m/%Y') AND 
    // ROUND((amount), 2)+ROUND((vat), 2) != ROUND((total), 2)
    
    // $sql = "SELECT * FROM `jv` WHERE STR_TO_DATE(`date`, '%d/%m/%Y') < STR_TO_DATE('01/01/2024', '%d/%m/%Y')";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    //     $jv = $result['id'];
        
    //     $sql1 = "SELECT sum(amount) AS cd_amt,sum(vat) AS cd_vat FROM `jv_items` WHERE `jv`='$jv' AND `type`='credit'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $cd_amt = $result1['cd_amt'];
    //         $cd_amt = ($cd_amt != NULL) ? $cd_amt : 0;
    //     $cd_vat = $result1['cd_vat'];
    //         $cd_vat = ($cd_vat != NULL) ? $cd_vat : 0;
    //     $cd_amount = $cd_amt+$cd_vat;
        
    //     $cd_amount = round($cd_amount, 2);
    //     $cd_amount = ($cd_amount != NULL) ? $cd_amount : 0;
        
    //     $sql2 = "SELECT sum(amount) AS db_amt,sum(vat) AS db_vat FROM `jv_items` WHERE `jv`='$jv' AND `type`='debit'";
    //     $query2 = mysqli_query($conn,$sql2);
    //     $result2 = mysqli_fetch_array($query2);
    //     $db_amt = $result2['db_amt'];
    //         $db_amt = ($db_amt != NULL) ? $db_amt : 0;
    //     $db_vat = $result2['db_vat'];
    //         $db_vat = ($db_vat != NULL) ? $db_vat : 0;
        
    //     $db_amount = $db_amt+$db_vat;
        
    //     $db_amount = round($db_amount, 2);
    //     $db_amount = ($db_amount != NULL) ? $db_amount : 0;
        
    //     if($cd_amount != $db_amount) {
    //         $diff = $cd_amount-$db_amount;
    //         $diff = round($diff, 2);
    //         $difference[] = $diff;
    //         echo $jv.'<br>';
    //     }
    // }
//  echo array_sum($difference);


    // $sql = "SELECT * FROM `additionalAcc` WHERE STR_TO_DATE(date,'%d/%m/%Y') > STR_TO_DATE('01/10/2023', '%d/%m/%Y') AND
    //         STR_TO_DATE(date,'%d/%m/%Y') < STR_TO_DATE('08/02/2024', '%d/%m/%Y') AND `section` = 'TRP';";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
        
    //     $inv = $result['entry_id'];
        
    //     $sql1 = "SELECT * FROM `invoice` WHERE `id`='$inv' AND `date` != ''";
    //     $query1 = mysqli_query($conn,$sql1);
    //     if(mysqli_num_rows($query1) == 0) {
    //         echo $inv.'<br>';
    //     }
    // }
    
    // $sql = "SELECT * FROM `invoice` WHERE `transport` > 0";
    // $query = mysqli_query($conn,$sql);
    //     while($result = mysqli_fetch_array($query)) {
    //         $inv = $result['id'];

    //         $sql1 = "SELECT * FROM `additionalAcc` WHERE `section`='TRP' AND `entry_id`='$inv'";
    //         $query1 = mysqli_query($conn,$sql1);
    //             if(mysqli_num_rows($query1) == 0) {
    //                 echo $inv.'<br>';
    //             }
    //     }

    // $sql = "SELECT inv.id AS invoice FROM `invoice` inv INNER JOIN additionalRcp rcp
    //         ON inv.id = rcp.entry_id
    //         WHERE rcp.section='INV' AND inv.grand != rcp.amount";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query)) {
    //     $inv = $result['invoice'];
    //     echo $inv.'<br>';
    // }
    
        // $sql = "SELECT * FROM `customers`";
        // $query = mysqli_query($conn,$sql);
        // $sl = 1;
        // while($result = mysqli_fetch_array($query)) {
        //     $sl = 1;
        //     $customer = $result['id'];

        //     $sql1 = "SELECT * FROM `customer_site` WHERE `customer`='$customer' AND `uid`=0";
        //     $query1 = mysqli_query($conn,$sql1);
        //     while($result1 = mysqli_fetch_array($query1)) {

        //         $site = $result1['id'];

        //         $sql2 = "UPDATE `customer_site` SET `uid`='$sl' WHERE `customer`='$customer' AND `uid`=0 AND `id`='$site'";
        //         $query2 = mysqli_query($conn,$sql2);
        //     $sl++;    
        //     }
        // }
        
        
        $sql = "CREATE VIEW last_activities AS
            SELECT *
            FROM activity_log
            ORDER BY id DESC
            LIMIT 30";

        $sql = "CREATE VIEW view_aging_report AS
                    SELECT invoice.*,COALESCE(t1.tr, 0) AS total_receipted,COALESCE(t2.tc, 0) AS total_credited
                    FROM invoice
                    LEFT JOIN (SELECT invoice, sum(total) as tr FROM reciept_invoice GROUP BY invoice) as t1 ON invoice.id=t1.invoice
                    LEFT JOIN (SELECT invoice, sum(total) as tc FROM credit_note GROUP BY invoice) as t2 ON invoice.id=t2.invoice
                ";

        $sql = "CREATE VIEW view_aging_customers AS
                SELECT cs.*,cs_slmn.name as slman,i.date as date,i.customer,i.status as payment_status,
                       COALESCE(NULLIF(cda.credit, ''), 0) AS cdt1,COALESCE(NULLIF(cda.credit1, ''), 0) AS cdt2
                FROM `invoice` i
                INNER JOIN `customers` cs ON i.customer = cs.id
                INNER JOIN `customers` cs_slmn ON cs.slmn = cs_slmn.id
                LEFT JOIN `credit_application` cda ON i.customer = cda.company
                ";
        
        $sql = "CREATE VIEW view_aging AS
                SELECT cs.id AS cust_id,cs.name AS cust_name,cs.cust_type,cs.period,
                      cs_slmn.name as slman_name,cs_slmn.id as slman_id,i.date as date,i.customer,i.id as invoice,i.status as payment_status,i.grand,
                      COALESCE(t1.tr, 0) AS total_receipted,COALESCE(t2.tc, 0) AS total_credited,
                      COALESCE(NULLIF(cda.credit, ''), 0) AS cdt1,COALESCE(NULLIF(cda.credit1, ''), 0) AS cdt2
                FROM `invoice` i
                LEFT JOIN (SELECT invoice, ROUND(SUM(total), 2) as tr FROM reciept_invoice GROUP BY invoice) as t1 ON i.id=t1.invoice
                LEFT JOIN (SELECT invoice, ROUND(SUM(total), 2) as tc FROM credit_note GROUP BY invoice) as t2 ON i.id=t2.invoice
                INNER JOIN `customers` cs ON i.customer = cs.id
                INNER JOIN `customers` cs_slmn ON cs.slmn = cs_slmn.id
                LEFT JOIN `credit_application` cda ON i.customer = cda.company
                WHERE i.status != 'Paid'
                ";


echo '<br>'.'FINISH 000';
?>