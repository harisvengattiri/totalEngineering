<?php ob_start();?>
<?php error_reporting(E_ERROR | E_PARSE);?>
<?php include "../config.php";?>
<?php include "../includes/menu.php";?>

<div class="row">
   <div class="col-md-2">
   </div>
   <div class="col-md-6">
      <p>Nestable</p>
    <br><br>
    <br>
    <?php echo $uid = rand(1000,9999).date('Ymdhisa');?>
   </div>
</div>
<?php include "../includes/footer.php";?>