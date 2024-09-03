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
