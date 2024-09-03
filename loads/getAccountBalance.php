<?php
include('../config.php');
if(isset($_POST)) {
    
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];
    
    if($subcategory == NULL) {
        $sql_calc1 = "SELECT sum(total) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$category'";
        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category'";
        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category'";
        $sql_calc4 = "SELECT sum(total) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$category'";
        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category'";
    } else {
        $sql_calc1 = "SELECT sum(total) AS amt1 FROM `jv_items` WHERE `type`='debit' AND `cat`='$category' AND `sub`='$subcategory'";
        $sql_calc2 = "SELECT sum(grand) AS amt2 FROM `pv` WHERE `category`='$category' AND `subcategory`='$subcategory'";
        $sql_calc3 = "SELECT sum(dbt_amt) AS amt3 FROM `debitnote` WHERE `cat`='$category' AND `sub`='$subcategory'";
        $sql_calc4 = "SELECT sum(total) AS amt4 FROM `jv_items` WHERE `type`='credit' AND `cat`='$category' AND `sub`='$subcategory'";
        $sql_calc5 = "SELECT sum(op_bal) AS amt5 FROM `expense_subcategories` WHERE `parent`='$category' AND `id`='$subcategory'";
    }
        $query_calc1 = mysqli_query($conn,$sql_calc1);
        $result_calc1 = mysqli_fetch_array($query_calc1);
        $amt1 = $result_calc1['amt1'];
        $amt1 = ($amt1 != NULL) ? $amt1 : 0;
        $query_calc2 = mysqli_query($conn,$sql_calc2);
        $result_calc2 = mysqli_fetch_array($query_calc2);
        $amt2 = $result_calc2['amt2'];
        $amt2 = ($amt2 != NULL) ? $amt2 : 0;
        $query_calc3 = mysqli_query($conn,$sql_calc3);
        $result_calc3 = mysqli_fetch_array($query_calc3);
        $amt3 = $result_calc3['amt3'];
        $amt3 = ($amt3 != NULL) ? $amt3 : 0;
        $query_calc4 = mysqli_query($conn,$sql_calc4);
        $result_calc4 = mysqli_fetch_array($query_calc4);
        $amt4 = $result_calc4['amt4'];
        $amt4 = ($amt4 != NULL) ? $amt4 : 0;
        $query_calc5 = mysqli_query($conn,$sql_calc5);
        $result_calc5 = mysqli_fetch_array($query_calc5);
        $amt5 = $result_calc5['amt5'];
        $amt5 = ($amt5 != NULL) ? $amt5 : 0;
                    
        $bal_amt = ($amt1+$amt2+$amt3) - ($amt4+$amt5);
        
    echo $bal_amt;
}
?>