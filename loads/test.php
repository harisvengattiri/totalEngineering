<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<button id="loadFunctionBtn">Load Function</button>
<div>
    <input id="Balance">
</div>

<script>
$(document).ready(function() {
  $('#loadFunctionBtn').click(function() {
    $.ajax({
      url: 'function.php',
      type: 'POST',
      data: { functionName: 'your_function_name' },
      success: function(response) {
        var resp = $.trim(response);
        alert(resp);
        $("#Balance").html(resp);
      },
      error: function(xhr, status, error) {
        // Handle errors
        console.error(error);
      }
    });
  });
});
</script>

<?php
if(isset($_POST['functionName'])) {
  $functionName = $_POST['functionName'];
  
  if($functionName === 'your_function_name') {
    // Call your function here
    your_function_name();
  }
}

// Define your function
function your_function_name() {
  // Function code goes here
  echo "Function executed!";
}
?>