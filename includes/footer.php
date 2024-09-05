  
  <!-- ############ SWITHCHER START-->
    <div id="switcher">
      <div class="switcher dark-white" id="sw-theme">
        <a href="#" data-ui-toggle-class="active" data-ui-target="#sw-theme" class="dark-white sw-btn">
          <i class="fa fa-gear text-muted"></i>
        </a>
        <div class="box-header">
          <a href="#" class="btn btn-xs rounded primary pull-right">#CybozUI</a>
          <strong>Theme Switcher</strong>
        </div>
        <div class="box-divider"></div>
        <div class="box-body">
          <p id="settingLayout" class="hidden-md-down">
            <label class="md-check m-y-xs" data-target="folded">
              <input type="checkbox">
              <i></i>
              <span>Folded Aside</span>
            </label>
            <label class="m-y-xs pointer" data-ui-fullscreen data-target="fullscreen">
              <span class="fa fa-expand fa-fw m-r-xs"></span>
              <span>Fullscreen Mode</span>
            </label>
          </p>
          <p>Colors:</p>
          <p data-target="color">
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="primary">
              <i class="primary"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="accent">
              <i class="accent"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="warn">
              <i class="warn"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="success">
              <i class="success"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="info">
              <i class="info"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="warning">
              <i class="warning"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-md">
              <input type="radio" name="color" value="danger">
              <i class="danger"></i>
            </label>
          </p>
          <p>Themes:</p>
          <div data-target="bg" class="clearfix">
            <label class="radio radio-inline m-a-0 ui-check ui-check-lg">
              <input type="radio" name="theme" value="">
              <i class="light"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-lg">
              <input type="radio" name="theme" value="grey">
              <i class="grey"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-lg">
              <input type="radio" name="theme" value="dark">
              <i class="dark"></i>
            </label>
            <label class="radio radio-inline m-a-0 ui-check ui-check-color ui-check-lg">
              <input type="radio" name="theme" value="black">
              <i class="black"></i>
            </label>
          </div>
        </div>
      </div>
    </div>
  <!-- ############ SWITHCHER END-->

<!-- ############ LAYOUT END-->
  </div>
   <script type="text/javascript">
 $(document).ready(function() {
  $("#customer,#customer2").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getsite",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#site,#site2").html(resp);
        }
      });
    } else {
      $("#state").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
<script type="text/javascript">
 $(document).ready(function() {
  $("#customer").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"get_qtn",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#qtn").html(resp);
        }
      });
    } else {
      $("#qtn").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
<!-- build:js scripts/app.min.js -->
<!-- jQuery -->

  <script src="<?php echo BASEURL; ?>/libs/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->

  <script src="<?php echo BASEURL; ?>/libs/tether/dist/js/tether.min.js"></script>
  <script src="<?php echo BASEURL; ?>/libs/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
  	
  <script src="<?php echo BASEURL; ?>/libs/jQuery-Storage-API/jquery.storageapi.min.js"></script>
  <script src="<?php echo BASEURL; ?>/libs/PACE/pace.min.js"></script>
  <!--<script src="<?php // echo BASEURL; ?>/libs/jquery-pjax/jquery.pjax.js"></script>-->
  <script src="<?php echo BASEURL; ?>/libs/blockUI/jquery.blockUI.js"></script>
  <script src="<?php echo BASEURL; ?>/libs/jscroll/jquery.jscroll.min.js"></script>
  
  <script src="<?php echo BASEURL; ?>/scripts/config.lazyload.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-load.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-jp.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-include.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-device.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-form.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-modal.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-nav.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-list.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-screenfull.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-scroll-to.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-toggle-class.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ui-taburl.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/app.js"></script>
  <script src="<?php echo BASEURL; ?>/scripts/ajax.js"></script>
  <script type="text/javascript">
 $(document).ready(function() {
  $("#country").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getstates",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#state").html(resp);
        }
      });
    } else {
      $("#state").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

<script type="text/javascript">
 $(document).ready(function() {
  $("#site,#site2").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getpo",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#po,#po2").html(resp);
        }
      });
    } else {
      $("#po").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

 <script type="text/javascript">
 $(document).ready(function() {
  $("#getall").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getall",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#all").html(resp);
        }
      });
    } else {
      $("#state").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
 <script type="text/javascript">
 $(document).ready(function() {
  $("#getinvoice").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getinvoice",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#invoice").html(resp);
        }
      });
    } else {
      $("#state").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
 <script type="text/javascript">
 $(document).ready(function() {
  $("#cust").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getdn",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#dn").html(resp);
        }
      });
    } else {
      $("#dn").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

 <script type="text/javascript">
 $(document).ready(function() {
  $("#icustomer").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"get_invoice",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#invoice").html(resp);
        }
      });
    } else {
      $("#invoice").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>

 <script type="text/javascript">
 $(document).ready(function() {
  $("#catSelect").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"getsubcat",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#subcatsSelect").html(resp);
        }
      });
    } else {
      $("#subcatsSelect").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>
<script type="text/javascript">
 $(document).ready(function() {
  $("#custrefund").change(function() {
    var country_id = $(this).val();
    if(country_id != "") {
      $.ajax({
        url:"get_rfd_rcp",
        data:{c_id:country_id},
        type:'POST',
        success:function(response) {
          var resp = $.trim(response);
          $("#rcprfd").html(resp);
        }
      });
    } else {
      $("#rcprfd").html("<option value=''>------- Select --------</option>");
    }
  });
});
</script>


<!-- endbuild -->
</body>
<?php
// mysqli_close($conn);
// $conn->close();
// $conn = null;
// mysqli_close($conn_backup);
// $conn_backup->close();
// $conn_backup = null;
?>
</html>