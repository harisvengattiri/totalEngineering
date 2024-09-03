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
            $lpo=$row['lpo'];
        	
//       echo "<option value='" . $row['site'] . "'>" . $row['site'] . "</option>";
          ?>
<!--<input type="text" class="form-control" name="driver" value="<?php echo $row[site];?>">-->
<?php
        
	} }
       ?> 
             <br>
               <hr><hr>

               <?php
                 $sql="SELECT * from order_item where item_id=$id";
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $item1=$row['item'];
                                     $item = mysqli_real_escape_string($conn,$row['item']);
                                     $quantityreq=$row['quantity'];
                                     $price=$row['unit'];
                                     $sql1="SELECT * FROM batches_lots WHERE item='".$item."'";
//                                     $sql2="SELECT SUM(quantity) AS qnt FROM batches_lots WHERE item='".$item."'";
                                   $result1=$conn->query($sql1);
//                                   $result2=$conn->query($sql2);
//                                   $row2=$result2->fetch_assoc();
//                                   $qnt=$row2['qnt'];
                                   if($result1->num_rows > 0){
//                                    $quantity=0;
                                        while($row1=$result1->fetch_assoc()) 
                                                {
                                                   $coc=$row1['COC_No'];
                                                   $quantity=$row1['quantity'];
                                                   $pdate=$row1['pdate'];
                                                   $batch=$row1['batch'];
                                                   
                                               ?>
             
               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-2">
                   <input name="price[]" value="<?php echo $price;?> "hidden="hidden">
                   <input name="lpo" value="<?php echo $lpo;?> "hidden="hidden">
                   <input type="text" class="form-control" name="item[]" value="<?php echo $item1;?>" id="endd" placeholder="Item">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Order Quantity</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantityreq;?>" id="endd" placeholder="Quantity">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">From this Batch</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="thisquantity[]" placeholder="Quantity From this Batch" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">LOT Stock Available</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="Quan" value="<?php echo $quantity;?>" id="endd" readonly>
              </div>
               
               </div>
               
               <div class="form-group row">
<!--               <label for="endd" class="col-sm-1 form-control-label">Bundle</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="bundle[]" id="endd" placeholder="Bundle">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" value="<?php echo $pdate;?>" id="endd">
              </div>-->
               <label for="endd" align="right" class="col-sm-1 form-control-label">COC No</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="coc[]" value="<?php echo $coc;?>" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Batch No</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="batch[]" value="<?php echo $batch;?>" id="endd">
              </div>
               </div>
           <style>
               hr { 
                display: block;
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                margin-left: auto;
                margin-right: auto;
                border-style: inset;
                border-width: 1px;
                border-top: 1px solid rgba(14, 4, 4, 0.74);
                } 
          </style>
          <hr>
                    
               
                                <?php }}
                                
                                    else { ?>
      
               <div class="form-group row">
               <label for="endd" class="col-sm-1 form-control-label">Item</label>
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="item[]" value="<?php echo $item1;?>"  id="endd" placeholder="Item">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Order Quantity</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantityreq;?>" id="endd" placeholder="Quantity">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">From this Batch</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="thisquantity[]" placeholder="Quantity From this Batch" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">LOT Stock Available</label>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="Quan" id="endd" readonly>
              </div>
               
               </div>
               
               <div class="form-group row">
<!--               <label for="endd" class="col-sm-1 form-control-label">Bundle</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="bundle[]" id="endd" placeholder="Bundle">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" id="endd">
              </div>-->
               <label for="endd" align="right" class="col-sm-1 form-control-label">COC No</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="coc[]" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Batch No</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="batch[]" id="endd">
              </div>
               </div>
                   <hr>                      
                                    <?php                    
                                         
                                         
                                          }
                                    ?>
                                <hr><br><br>
                               <?php }} ?>



               
    <?php
          }
    else
    {
        echo  'fdhg';
    }
}
?>