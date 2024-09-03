<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT * FROM batches_lots WHERE batch='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
            
            $id=$row['id'];
            $quantity=$row['quantity'];
	} }
       ?>
       <label for="endd" class="col-sm-2 form-control-label">LOT Stock Available</label>
       <div class="col-sm-2">
       <input type="text" class="form-control" name="quantity" value="<?php echo $quantity;?>" readonly>
       </div>
       <div class="form-group row">
              <label for="endd"  class="col-sm-1 form-control-label">Quantity</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="thisquantity[]" placeholder="Quantity From this Batch" id="endd">
              </div>
               <label for="endd"  class="col-sm-1 form-control-label">COC No</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="coc[]" value="<?php echo $coc;?>" id="endd">
              </div>
      </div>
             
           <?php
          }
    else
    {
        echo  'fdhg';
    }
}
?>