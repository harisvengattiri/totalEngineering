<?php
if(isset($_POST['cat_id'])) {
    $cat = $_POST['cat_id'];
    if ($cat != '') {
    include('../config.php');
    $sql = "SELECT * FROM expense_subcategories WHERE parent='".$cat."'";
    $result=$conn->query($sql);
        echo "<option value=''>Select</option>";
    if($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['category'] . "</option>";
	} }
    }
    else
    {
        echo  'error';
    }
}
?>