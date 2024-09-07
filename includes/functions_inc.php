<?php

function loadEnv($filePath) {
  if (!file_exists( $filePath)) {
    throw new Exception('environment file is missing');
  }
  $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) {
          continue;
      }

      list($key, $value) = explode('=', $line, 2);
      $key = trim($key);
      $value = trim($value);
      putenv("$key=$value");
      $_ENV[$key] = $value;
      $_SERVER[$key] = $value;
  } 
}

function findMyIPDetails() {
  $ip=$_SERVER['REMOTE_ADDR'];
  if($ip != '::1') {
  $ip_details=json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
  $ip_city=$ip_details->city;
  $ip_region=$ip_details->region;
  $ip_country=$ip_details->country;
    $ip_location="$ip_city, $ip_region, $ip_country";
    return $ip_location;
  } else {
    $ip_location = 'Accessing locally with out IP';
    return $ip_location;
  }
}

function getStatusFromUrl() {

  $status = isset($_GET['status']) ? $_GET['status'] : '';
  return $status;
}

function custom_money_format($format, $number) {
  $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
  $number = floatval($number);
  $value = number_format($number, 2, '.', ',');
  return preg_replace($regex, $value, $format);
}

function displaySubmissionStatus($status) {
  $statusClass = "";
  $iconClass = "ion-checkmark";
  $message = "";

  if ($status == "success") {
      $statusClass = "success";
      $message = "Your Submission was Successful!";
  } else if ($status == "failed") {
      $statusClass = "danger";
      $message = "Your Submission Failed!";
  }

  ?>
  <p>
      <a class="list-group-item b-l-<?php echo $statusClass; ?>">
          <span class="pull-right text-<?php echo $statusClass; ?>">
              <i class="fa fa-circle text-xs"></i>
          </span>
          <span class="label rounded label <?php echo $statusClass; ?> pos-rlt m-r-xs">
              <b class="arrow right b-<?php echo $statusClass; ?> pull-in"></b>
              <i class="<?php echo $iconClass; ?>"></i>
          </span>
          <span class="text-<?php echo $statusClass; ?>">
              <?php echo $message; ?>
          </span>
      </a>
  </p>
  <?php } ?>
