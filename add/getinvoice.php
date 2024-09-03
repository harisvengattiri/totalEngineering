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
             <br>
               

               <?php
                 $sql="SELECT * from order_item where item_id=$id";
                 $result = mysqli_query($conn, $sql);
                 if (mysqli_num_rows($result) > 0) 
				{
                                $subtotal=0;
				while($row = mysqli_fetch_assoc($result)) 
				{
                                     $item1=$row['item'];
                                     $item = mysqli_real_escape_string($conn,$row['item']);
                                     $quantityreq=$row['quantity'];
                                      $unit=$row['unit'];
                                      $total=$row['total'];
                                      $subtotal=$subtotal+$total;
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
                                                   $date=$row1['pdate'];
                                                   $batch=$row1['batch'];
                                                   }
                                               ?>
             
               <div class="form-group row">
              
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="item[]" value="<?php echo $item1;?>" id="endd" placeholder="Item">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" value="<?php echo $date;?>" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantityreq;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Unit Price</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="unit[]" value="<?php echo $unit;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="total[]" value="<?php echo $total;?>" id="endd" placeholder="Quantity" readonly>
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
          
                    
               
                                <?php }
                                
                                    else { ?>
      
              <div class="form-group row">
              
              <div class="col-sm-2">
                   <input type="text" class="form-control" name="item[]" value="<?php echo $item1;?>" id="endd" placeholder="Item">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Date</label>
              <div class="col-sm-2">
                <input type="text" class="form-control" name="pdate[]" id="endd">
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Quantity</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="reqquantity[]" value="<?php echo $quantityreq;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <label for="endd" align="right" class="col-sm-1 form-control-label">Unit Price</label>
              <div class="col-sm-1">
                <input type="text" class="form-control" name="unit[]" value="<?php echo $unit;?>" id="endd" placeholder="Quantity" readonly>
              </div>
               <div class="col-sm-2">
                    <input type="text" class="form-control" name="total[]" value="<?php echo $total;?>" id="endd" placeholder="Quantity" readonly>
              </div>
                
               </div>
                                    <?php
                                     }
                                    ?>
                                <br>
                               <?php }} ?>
                                
                                
                                <?php 
                                 $vat = (5 / 100) * $subtotal;
                                 $grandtotal=$vat+$subtotal;
                                  ?>
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">Sub total</label>
                                   <div class="col-sm-3">
                                    <input type="text" class="form-control" name="subtotal" value="<?php echo $subtotal;?>" id="endd" placeholder="Quantity" readonly>
                                    </div>           
                                </div>
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">VAT Tax [5%]</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="vat" value="<?php echo $vat;?>" id="endd" placeholder="Quantity" readonly>
              </div>           
                                </div>
                                <div class="form-group row">
                                 <label for="endd" align="right" class="col-sm-8 form-control-label">Grand total</label>
              <div class="col-sm-3">
                   <input type="text" class="form-control" name="grand" value="<?php echo $grandtotal;?>" id="endd" placeholder="Quantity" readonly>
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