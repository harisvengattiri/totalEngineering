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

<?php
function convert_number_to_words($num)
  {

  list($num, $dec) = array_pad(explode(".", $num), 2, null);

  $output = "";
  
  if(substr($num, 0, 1) == "-")
  {
    $output = "negative ";
    $num = ltrim($num, "-");
  }
  else if(substr($num, 0, 1) == "+")
  {
    $output = "positive ";
    $num = ltrim($num, "+");
  }
  if(substr($num, 0, 1) == "0")
  {
    $output .= "zero";
  }
  else
  {
    $num = str_pad($num, 36, "0", STR_PAD_LEFT);
    $group = rtrim(chunk_split($num, 3, " "), " ");
    $groups = explode(" ", $group);

    $groups2 = array();
    foreach($groups as $g) $groups2[] = convertThreeDigit(substr($g, 0, 1), substr($g, 1, 1), substr($g, 2, 1));
    for($z = 0; $z < count($groups2); $z++)
    {
      if($groups2[$z] != "")
      {
        $output .= $groups2[$z].convertGroup(11 - $z).($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1))
          && $groups2[11] != '' && substr($groups[11], 0, 1) == '0' ? " and " : ", ");
      }
    }
      $output = rtrim($output, ", ");
  }
  if($dec > 0)
  {
    $output .= " point";
    for($i = 0; $i < strlen($dec); $i++) $output .= " ".convertDigit(substr($dec, $i, 1));
  }
  return $output;
}

function convertGroup($index)
{
  switch($index)
  {
    case 11: return " decillion";
    case 10: return " nonillion";
    case 9: return " octillion";
    case 8: return " septillion";
    case 7: return " sextillion";
    case 6: return " quintrillion";
    case 5: return " quadrillion";
    case 4: return " trillion";
    case 3: return " billion";
    case 2: return " million";
    case 1: return " thousand";
    case 0: return "";
  }
}

function convertThreeDigit($dig1, $dig2, $dig3)
{
  $output = "";
  if($dig1 == "0" && $dig2 == "0" && $dig3 == "0") return "";
  if($dig1 != "0")
  {
    $output .= convertDigit($dig1)." hundred";
    if($dig2 != "0" || $dig3 != "0") $output .= " and ";
  }
  if($dig2 != "0") $output .= convertTwoDigit($dig2, $dig3);
  else if($dig3 != "0") $output .= convertDigit($dig3);

  return $output;
}

function convertTwoDigit($dig1, $dig2)
{
  if($dig2 == "0")
  {
    switch($dig1)
    {
      case "1": return "ten";
      case "2": return "twenty";
      case "3": return "thirty";
      case "4": return "forty";
      case "5": return "fifty";
      case "6": return "sixty";
      case "7": return "seventy";
      case "8": return "eighty";
      case "9": return "ninety";
    }
  }
  else if($dig1 == "1")
  {
    switch($dig2)
    {
      case "1": return "eleven";
      case "2": return "twelve";
      case "3": return "thirteen";
      case "4": return "fourteen";
      case "5": return "fifteen";
      case "6": return "sixteen";
      case "7": return "seventeen";
      case "8": return "eighteen";
      case "9": return "nineteen";
    }
  }
  else
  {
    $temp = convertDigit($dig2);
    switch($dig1)
    {
      case "2": return "twenty-$temp";
      case "3": return "thirty-$temp";
      case "4": return "forty-$temp";
      case "5": return "fifty-$temp";
      case "6": return "sixty-$temp";
      case "7": return "seventy-$temp";
      case "8": return "eighty-$temp";
      case "9": return "ninety-$temp";
    }
  }
}
      
function convertDigit($digit)
{
  switch($digit)
  {
    case "0": return "zero";
    case "1": return "one";
    case "2": return "two";
    case "3": return "three";
    case "4": return "four";
    case "5": return "five";
    case "6": return "six";
    case "7": return "seven";
    case "8": return "eight";
    case "9": return "nine";
  }
}
