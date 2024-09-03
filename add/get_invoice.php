<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql="SELECT id,grand FROM invoice WHERE customer='".$country."'";
    $result=$conn->query($sql);   
    if($result->num_rows > 0){
    echo "<option value=''>Proceed with out invoice</option>";
       while($row=$result->fetch_assoc()) {
           $id = $row['id'];
           $grand = $row['grand'];

           $sql1="SELECT sum(total) AS total FROM reciept_invoice WHERE invoice='$id'";
           $query1 = mysqli_query($conn,$sql1);
           $result1=mysqli_fetch_array($query1);
           $total_rcp = $result1['total'];

           $sql2="SELECT sum(total) AS total FROM credit_note WHERE invoice='$id'";
           $query2 = mysqli_query($conn,$sql2);
           $result2=mysqli_fetch_array($query2);
           $total_cdt = $result2['total'];
           
           $total = $total_rcp + $total_cdt;
           
           if($total < $grand){
            echo "<option value='" . $id . "'>" ."INV|". $id . "</option>";
           }
	} }
}
}
?>