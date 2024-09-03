<section class="content">
     <div class="row">
          <section class="col-lg-12 connectedSortable">
               <?php echo form_open_multipart("quotation/insert", array('id' => "frmBanner", 'class' => "form-horizontal")) ?>
               <div class="box box-info">
                    <div class="box-header">
                         <h3 class="box-title">Add Quotation</h3>
                    </div>
                    
                    <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">Customer Name</label>
                         <div class="col-md-5">
                             <input type="text" class="form-control" name="quotation[user_name]" id="user_name" placeholder="Customer Name" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">Company Name</label>
                         <div class="col-md-5">
                              <input type="text" class="form-control" name="quotation[company_name]" id="company_name" placeholder="Company Name" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">TRN No</label>
                         <div class="col-md-5">
                              <input type="text" class="form-control" name="quotation[trn_no]" id="trn_no" placeholder="TRN No" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label">Customer Address</label>
                         <div class="col-md-6">
                              <div class="input-group">
                                   <textarea name="quotation[address]" id="address" rows="4" cols="64" required></textarea>
                              </div>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">Country</label>
                         <div class="col-md-5">
                              <input type="text" class="form-control" name="quotation[country]" id="country" placeholder="Country" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">Email</label>
                         <div class="col-md-5">
                              <input type="email" class="form-control" name="quotation[email]" id="email" placeholder="Email" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label" id="lblBrandTitle">Contact</label>
                         <div class="col-md-5">
                              <input type="text" class="form-control" name="quotation[contact]" id="contact" placeholder="Contact" required/>
                              <span class="help-block">
                                   <!--A block of help text-->
                              </span>
                         </div>
                    </div>
                   
                   
                   
                   <div class="form-group">
                         <label class="col-md-3 control-label">Product</label>
                         <div class="col-md-3" style="margin-right: -96px;">
                              <div class="input-group">                                  
                                <?php                                
                                echo '<select name="prd_id[]" id="prd_id"  class="form-control">';                                
                                foreach ($products as $key => $val) {                                     
                                     echo "<option value='" . $val['prd_id'] . "'>" . $val['prd_brw_no'] . "</option>";
                                }
                                echo "</select>";
                              ?>
                              <span class="help-block"></span> 
                              </div>
                         </div>

                         <label class="col-md-1 control-label">Quantity</label>
                         <div class="col-md-3"  style="margin-right: -57px;">
                              <div class="input-group">
                                  <input type="text" name="qty[]" class="form-control" id="qty" placeholder="Quantity" value="1"/>
                              </div>
                         </div>

                         <div class="box-tools">
                              <a href="javascript:void(0);" 
                                 class="btn btn-info btn-sm" id="btnAddMoreSpecification" data-original-title="Add More">
                                   <i class="fa fa-plus"></i>
                              </a>
                         </div>

                         <div id="divSpecificatiion">

                         </div>
                    </div>
             
                    <div class="box-footer clearfix">
                         <input type="submit" value="Save" class="btn btn-primary" style="float: left !important;"/>
                    </div>
               </div>
               <?php echo form_close() ?>
          </section>
     </div>
</section>
</div>

<script type="text/template" id="temSpecification">
     <div style="padding-top:10px;">
     <label class="col-md-3 control-label">Product</label>
     <div class="col-md-3" style="margin-right: -96px;">
     <div class="input-group">                                  
      <?php                                
        echo '<select name="prd_id[]" id="prd_id"  class="form-control">';                                
        foreach ($products as $key => $val) {                                     
           echo "<option value='" . $val['prd_id'] . "'>" . $val['prd_brw_no'] . "</option>";
        }
        echo "</select>";
        ?>
        <span class="help-block"></span> 
     </div>
     </div>

     <label class="col-md-1 control-label">Quantity</label>
     <div class="col-md-3"  style="margin-right: -57px;">
     <div class="input-group">
     <input type="text" name="qty[]" class="form-control" id="spe_specification_detail" placeholder="Quantity" value="1"/>
     </div>
     </div>

     <div class="box-tools">
     <a href="javascript:void(0);"  class="btn btn-info btn-sm btnRemoveMoreSpecification" data-original-title="Add More">
     <i class="fa fa-times"></i>
     </a>
     </div>
     </div>
</script>