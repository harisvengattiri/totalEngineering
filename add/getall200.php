<?php
if(isset($_POST['c_id'])) {
    $country = $_POST['c_id'];
    if ($country != '') {
    include('../config.php');
    $sql1="SELECT * FROM sales_order WHERE order_referance='".$country."'";
    //echo $sql1;
    $result=$conn->query($sql1);   
    if($result->num_rows > 0){
    
       while($row=$result->fetch_assoc()) {
            
            $id=$row['id'];
            $site=$row['site'];
            $salesrep=$row['salesrep'];
            $customer=$row['customer'];
        	
//       echo "<option value='" . $row['site'] . "'>" . $row['site'] . "</option>";
          ?>
<!--<input type="text" class="form-control" name="driver" value="<?php echo $row[site];?>">-->
<?php
        
	} }
       ?> 
          <div class="form-group row">
              <label for="customer" class="col-sm-2 form-control-label">Customer</label>
              <div class="col-sm-4">
		<select name="customer" class="form-control">
                  <?php 
				$sql = "SELECT name FROM customers ";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
				?>
                                <option value="<?php echo $customer;?>"><?php echo $customer;?></option>
				<option value="<?php echo $row["name"]; ?>"><?php echo $row["name"]?></option>
				<?php 
				}} 
				?>
                </select>		
              </div>
              <label for="startd" align="right" class="col-sm-2 form-control-label">Customer Site</label>
              <div class="col-sm-4">
                <input type="text" class="form-control" name="site" value="<?php echo $site;?>">
              </div>
            </div>
          <div class="form-group row">
              <label for="startd" class="col-sm-2 form-control-label">Vehicle</label>
              <div class="col-sm-4" id="veh">
                   <input type="text" class="form-control" name="vehicle" value="<?php echo $site;?>">
              <!--<select class="form-control" id="veh"></select>-->
              </div>
              <label for="endd" align="right" class="col-sm-2 form-control-label">Driver</label>
              <div class="col-sm-4" id="nam">
              <input type="text" class="form-control" name="driver" value="<?php echo $salesrep;?>">
              </div>
              </div>
             


               <?php
                 $sql="SELECT * from order_item where item_id=$id";
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $item=$row['item'];
                                     $quantity=$row['quantity'];
                                     
               ?>

               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-3">
                   <input type="text" class="form-control" name="Item" value="<?php echo $item;?>" id="endd" placeholder="Item">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Quantity" value="<?php echo $quantity;?>" id="endd" placeholder="Quantity">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Bundle</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Bundle" id="endd" placeholder="Bundle">
              </div>
               </div>
               
               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">COC No</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="COC No" id="endd" placeholder="COC No">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Production Date" id="endd" placeholder="Production Date">
              </div>
               <label for="endd" class="col-sm-1 form-control-label">Batch No</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="Batch No" id="endd" placeholder="Batch No">
              </div>
               </div>
               
                                <?php }} ?>



               
    <?php
          }
    else
    {
        echo  'fdhg';
    }
}
?>