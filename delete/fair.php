<?php include "../config.php"; ?>
<?php
session_start();
if (isset($_SESSION['userid'])) {
  $prj = $_GET['id'];

  $sql_assigned = "SELECT * FROM `customer_site` WHERE `location`='$prj'";
  $query_assigned = mysqli_query($conn,$sql_assigned);
  if (mysqli_num_rows($query_assigned) == 0) {
    $sql = "DELETE FROM fair where id='$prj'";
    if ($conn->query($sql) === TRUE) {
      $date1 = date("d/m/Y h:i:s a");
      $username = $_SESSION['username'];
      $code = "LCN" . $prj;
      $query = mysqli_real_escape_string($conn, $sql);
      $sql = "INSERT INTO activity_log (time, process, code, user, query) values ('$date1', 'delete', '$code', '$username', '$query')";
      $result = mysqli_query($conn, $sql);
      header("Location: $baseurl/fair?status=deleted");
    } else {
      header("Location: $baseurl/fair?status=failed");
    }
  } else {
    header("Location: $baseurl/fair?status=failed");
  }

} else {
  header("Location:$baseurl/login/");
}
?>