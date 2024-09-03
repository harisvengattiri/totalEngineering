<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('config.php');
    $sql1="SELECT customersite,lpo FROM delivery_note WHERE customersite='".$country."' GROUP BY lpo";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
    ?><option value=''>ALL</option><?php
       while($row=$result->fetch_assoc()) {
        	
          echo "<option value='" . $row['lpo'] . "'>" . $row['lpo'] . "</option>";
        
	} }
    }
    else
    {
        echo  'fdhg';
    }
}
?>