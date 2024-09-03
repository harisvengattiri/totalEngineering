<?php
if(isset($_POST['c_id'])) {
    $type = $_POST['c_id'];
    if ($type != '') {
    include('../config.php');
        if($type == 'Cash'){
            echo '<option value="">ALL</option>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="7">7</option>
                  <option value="14">14</option>
                  <option value="29">29</option>
                  <option value="30">30</option>';
        }
        else if($type == 'Credit'){
            echo '<option value="">ALL</option>
                  <option value="30">30</option>
                  <option value="45">45</option>
                  <option value="60">60</option>
                  <option value="75">75</option>
                  <option value="90">90</option>
                  <option value="120">120</option>
                  <option value="180">180</option>';
        }
    }
    else
    {
        echo  'Not Found';
    }
}
?>