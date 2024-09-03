<?php
error_reporting(0);
     include('config.php');
     $date1=date("d/m/Y h:i:s a");    
     $ip=$_SERVER['REMOTE_ADDR'];
     $ip_details=json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
     $ip_city=$ip_details->city;
     $ip_region=$ip_details->region;
     $ip_country=$ip_details->country;
     $ip_location="$ip_city, $ip_region, $ip_country";

     $username = mysqli_real_escape_string($conn, $_POST['username']);
     $password = mysqli_real_escape_string($conn, $_POST['password']);
     $pwd = md5($password);

     $sql = "select * from users where username='$username' and pwd='$pwd'";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) > 0) 
				{
                    session_start();
				         while($row = mysqli_fetch_assoc($result)) 
				         {
				         $_SESSION["userid"] = $row['id'];
				         $_SESSION["username"] = $row['username'];
				         $_SESSION["role"] = $row['role'];
				         $_SESSION["time"] = time();
				         }
				$sql = "INSERT INTO login_log (ip, time, location, username, status) 
				        values ('$ip', '$date1', '$ip_location', '$username', 'success')";
                                $result=$conn->query($sql);
				header("Location:".BASEURL."/?folded=false");
				}
				else
				{
				$sql = "INSERT INTO login_log (ip, time, location, username, status) 
				        values ('$ip', '$date1', '$ip_location', '$username', 'failed')";
                                $result=$conn->query($sql);
                                session_destroy();
				header("Location:".BASEURL."/login/?status=failed");
				}
?>