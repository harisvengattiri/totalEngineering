<?php
include "config.php";


// 'PERIOD UPDATE FROM CREDIT APPLICATION TO CUSTOMERS TABLE';

// $sql = "SELECT company,period FROM `credit_application`";
// $query = mysqli_query($conn,$sql);
// while($result = mysqli_fetch_array($query))
// {
//     $company = $result['company'];
//     $period = $result['period'];
    
//     $sql1 = "UPDATE `customers` SET period='$period' WHERE id='$company' AND period=''";
//     $query1 = mysqli_query($conn,$sql1);
// }


// 'UPDATE SALESMAN TO INVOICE FROM SALESORDER';

    // $sql = "SELECT id,o_r FROM `invoice` WHERE rep=''";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query))
    // {
    //     $inv = $result['id'];
    //     $or = $result['o_r'];
    //         $sqlrep = "SELECT `salesrep` FROM `sales_order` WHERE `order_referance`='$or'";
    //         $queryrep = mysqli_query($conn,$sqlrep);
    //         $resultrep = mysqli_fetch_array($queryrep);
    //         $rep = $resultrep['salesrep'];
        
    //     $sql1 = "UPDATE `invoice` SET `rep`='$rep' WHERE `id`='$inv'";
    //     $query1 = mysqli_query($conn,$sql1);
    // }


// 'MORE TO DO';
// 'STATUS UPDATE BY COMPARING INVOICE AND RECEIPT INVOICE';

    // $sql = "SELECT id,grand FROM `invoice`";
    // $query = mysqli_query($conn,$sql);
    // while($result = mysqli_fetch_array($query))
    // {
    //     $inv = $result['id'];
    //     $amt = $result['grand'];
    //     $amt = number_format($amt, 2, '.', '');
        
    //     $sql1 = "SELECT sum(total) as total FROM `reciept_invoice` WHERE `invoice`='$inv'";
    //     $query1 = mysqli_query($conn,$sql1);
    //     $result1 = mysqli_fetch_array($query1);
    //     $total = $result1['total'];
    //     $total = number_format($total, 2, '.', '');
        
    //     $sql2 = "SELECT sum(total) as total_cdt FROM `credit_note` WHERE `invoice`='$inv'";
    //     $query2 = mysqli_query($conn,$sql2);
    //     $result2 = mysqli_fetch_array($query2);
    //     $total_cdt = $result2['total_cdt'];
    //     $total_cdt = number_format($total_cdt, 2, '.', '');
        
    //     $total_amt = $total+$total_cdt;
        
    //     if($total_amt >= $amt){$status='Paid';}
    //     if($total_amt < $amt){$status='Partially';}
    //     if($total_amt == NULL){$status='';}
        
        // check purpose
        // echo $amt.'<br>'.$total_amt.'<br>'.$status.'<br>';
        
        // $sql3 = "UPDATE `invoice` SET `status` = '$status' WHERE `id`='$inv'";
        // $query3 = mysqli_query($conn,$sql3);
    // }

        // $a = 90;
        // $b = ($a != 90) ? $a : 0 ;
        // echo $b;
        
    // UPDATE TABLES SALESREP BY COMPARING WITH CUSTOMER TABLE
    
        // $sql = "SELECT * FROM `reciept` WHERE rep = ''";
        // $query = mysqli_query($conn,$sql);
        // while($result = mysqli_fetch_array($query))
        // {
        //     $rcp = $result['id'];
        //     $cust = $result['customer'];
            
        //     $sql1 = "SELECT `slmn` FROM `customers` WHERE id='$cust'";
        //     $query1 = mysqli_query($conn,$sql1);
        //     $result1 = mysqli_fetch_array($query1);
        //     $rep = $result1['slmn'];
            
        //     $sql2 = "UPDATE `reciept` SET rep = '$rep' WHERE id='$rcp'";
        //     $query2 = mysqli_query($conn,$sql2);
        // }
    
    echo 'FINISH';
?>