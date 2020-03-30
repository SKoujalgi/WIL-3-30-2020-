<?php
$script=isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
if (CHECK_TIMEOUT) {
	$track_timeout_for_this_script=false;
	if (!strpos($script, "retrieveSponsors.php") &&
		!strpos($script, "logout.php") && !strpos($script, "retrieveAdmins.php") && 
		!strpos($script, "retrieveChapters.php") ) {
			$track_timeout_for_this_script=true;
	}
if (isset($_SESSION['user_id']) && $track_timeout_for_this_script) {
echo '<script>
// Set the date were counting down to
var time_out_in_minutes ='.TIMEOUT_IN_MINUTES.';
var now = new Date().getTime();
var countDownDate = now + ( time_out_in_minutes * 60) * 1000;

// Update the count down every 1 second
var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();
    
  // Find the distance between now and the count down date
  var distance = countDownDate - now;
    
  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  var min = ("0" + minutes).slice(-2);
  var sec = ("0" + seconds).slice(-2);
  // Output the result in an element with id="timeout"
  document.getElementById("timeout").innerHTML = "<span class=\"glyphicon glyphicon-user\"></span>  '.$_SESSION["first_name"].' '.$_SESSION["last_name"].'<br>'.'Idle Timer: " + min + ":" + sec;
    
  // If the count down is over, write some text 
  
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("timeout").innerHTML = "";
		window.location="logout.php?event=Timedout";
  }
}, 1000);
</script>';
}
}
class UsageLog {  
	/* Member variables */
	var $sessionid;
	var $userid; 
	var $host;
	var $server;
	var $server_address;
	var $server_port;
	var $script_name;
	var $server_protocol;
	var $server_signature;
	var $origin;
	var $user_agent;
	var $remote_address;
	var $remote_port;
	var $referrer;
	var $request_method;
	var $request_scheme;
	var $request_uri;
    /* Member functions */
	function setUsageLog($session, $userid, $host, 
						$server, $serverAddress, $serverPort,$script, 
						$serverProtocol,$serverSign,
						$origin, $userAgent, $remoteAddress, $remotePort,
						$referrer, $requestMethod, $requestScheme, $requestURI)
	{ 
	   $this->sessionid=$session;
	   $this->userid=$userid;
	   $this->host=$host;
	   $this->server=$server; 
	   $this->server_address=$serverAddress;
	   $this->server_port=$serverPort; 
	   $this->script_name=$script;
	   $this->server_protocol=$serverProtocol;
	   $this->server_signature=$serverSign;
	   $this->origin=$origin;
	   $this->user_agent=$userAgent;
	   $this->remote_address=$remoteAddress; 
	   $this->remote_port=$remotePort;
	   $this->referrer=$referrer;
	   $this->request_method=$requestMethod; 
	   $this->request_scheme=$requestScheme; 
	   $this->request_uri=$requestURI;
    }      
    function getUsageLog() { 
       return $this; 
    }      
}
if (WRITE_LOG) {
$usagelog = new UsageLog;
$none='';
$usagelog->setUsageLog(	isset($_COOKIE['PHPSESSID'])	? $_COOKIE['PHPSESSID'] 	: $none,	
						isset($_SESSION['user_id']) 	? $_SESSION['user_id']		: 0,	
						isset($_SERVER['HTTP_HOST']) 	? $_SERVER['HTTP_HOST']		: $none,
						isset($_SERVER['SERVER_NAME']) 	? $_SERVER['SERVER_NAME']	: $none,
						isset($_SERVER['SERVER_ADDR']) 	? $_SERVER['SERVER_ADDR']	: $none,
						isset($_SERVER['SERVER_PORT']) 	? $_SERVER['SERVER_PORT']	: 0,
						isset($_SERVER['SCRIPT_NAME']) 	? $_SERVER['SCRIPT_NAME']	: $none,
						isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : $none,
						isset($_SERVER['SERVER_SIGNATURE']) ? $_SERVER['SERVER_SIGNATURE'] : $none,
						isset($_SERVER['HTTP_ORIGIN']) 	? $_SERVER['HTTP_ORIGIN']	: $none,
						isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $none,
						isset($_SERVER['REMOTE_ADDR']) 	? $_SERVER['REMOTE_ADDR'] 	: $none,
						isset($_SERVER['REMOTE_PORT']) 	? $_SERVER['REMOTE_PORT']	: 0,
						isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']	: $none,
						isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD']	: $none,
						isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME']	: $none,
						isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI']	: $none);
$url = $url = BASE_URL . 'writeusagelog.php';
$myvars = http_build_query($usagelog);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$url");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
$data = curl_exec($ch);
curl_close($ch);
//echo '<p class="Error">'.$data.' '.WRITE_LOG.'</p>';
}
if (CHECK_TIMEOUT) {
if ($track_timeout_for_this_script) {
	$url = BASE_URL . 'validate_session.php';
	$none='';
	if (   isset($_SESSION['session_id']) 
			&& isset($_SESSION['user_id']) ) {
		$myvars1['live_id']=$_SESSION['session_id'];
		$myvars1['live_userid']=$_SESSION['user_id'];
		$myvars1['live_session']=isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : $none;
		$myvars1['live_useragent']=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $none;
		$myvars1['live_remoteaddress']=isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] 	: $none;
		$myvars1 = http_build_query($myvars1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars1);
		$data = curl_exec($ch);
		curl_close($ch);
		$response=json_decode($data);
//		echo '<p class="error">Session Status: '.$response->status.
//									' Message: '.$response->message.'</p>';									
		if ($response->status != "Active") {
//			if ($response->status == "Error") {
//				echo '<script>window.alert("You will now be logged out due to an error: '.$response->message.'")</script>';
//			}
			
			$url = BASE_URL . "logout.php?event=$response->status"; // Define the URL.
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		} else {
			if (strpos($script, "login.php")) {
				echo "<p class=\"error\">You are already logged-in as ".$_SESSION['first_name']." ".$_SESSION['last_name']."</p>";
				include('includes/footer.html'); // Include the HTML footer.
				exit(); // Quit the script.
			} 
			if (strpos($script,"forgot_password.php")) {
				echo "<p class=\"error\">You are already logged-in as ".$_SESSION['first_name']." ".$_SESSION['last_name'].
				"<br>If you remember your password, please use the Change Password option.<br>Otherwise, first logout and then use  
				the Forgot Password option</p>";
				include('includes/footer.html'); // Include the HTML footer.
				exit(); // Quit the script.
			}
		}
	} 
}
}
?>