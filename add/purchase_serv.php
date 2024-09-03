
<div>
<?php
$field_name=$_POST['fname'];
?>

      <input type="text" name="text" id="<?php echo "txt".$field_name."";?>" value="<?php echo $field_name;?>" />
       
         &nbsp;
         
        
        <input type="text"  class="pname_list form-control input-sm" name="p_name[]" id="<?php echo "pname".$field_name."";?>" value="<?php echo $field_name;?>" class="typeahead" required/>
       
         
     
         
         &nbsp;<a href="javascript:void(0);" style="color:red;" class="remCF">&#10006;</a>
       </div>