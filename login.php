<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Login to WIL';
include('includes/header.html');
require('includes/usagelog.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQL);
	// trim all incoming data
	$trimmed = array_map('test_input', $_POST);

	// Validate the email address:
	if (!empty($trimmed['email'])) {
		$e = mysqli_real_escape_string($dbc, $trimmed['email']);
	} else {
		$e = FALSE;
		echo '<p class="error">You forgot to enter your email address!</p>';
	}

	// Validate the password:
	if (!empty($trimmed['pass'])) {
		$p = trim($trimmed['pass']);
	} else {
		$p = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}

	// Validate the human answer:
	if (!empty($trimmed['answer'])) {
		$a = trim($trimmed['answer']);
		if ($a != $_SESSION['ans']) {
			echo '<p class="error">The result of calculation '.$a.' entered by you is incorrect.</p>';
//			echo '<p class="error">Entered Answer='.$a.' Correct Answer='.$_SESSION['ans'];
			$a= FALSE;
		} 
	} else {
		$a = FALSE;
		echo '<p class="error">You did not enter the result of calculation. To verify you are not a bot, you must enter it.</p>';
	}

	if ($e && $p && $a ) { // If everything's OK.

		//echo $e;
		//echo @mysqli_num_rows($r);
		// Query the database:
		/*		
		$query = "SELECT user_id, first_name, last_name, user_level, pass, org_id, invalid_login_count, force_pass_change, locked, agreement_accepted 
		FROM user WHERE email='$e' AND active IS NULL";
		*/

		$session = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : $none;
		$userAgent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : $none;
		$remoteAddress=isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] 	: $none;


		$query = "SELECT user_id, first_name, last_name, email, co_name, work_phone, chapter, u_role, pass, invalid_login_count, force_pass_change, locked, agreement_accepted, user_level
				  FROM users WHERE email='$e' AND active IS NULL";	
		$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Fetch the values:
			list($user_id, $first_name, $last_name, $email, $co_name, $work_phone, $belong_to_chapter, $role, $pass, $invalid_login_count, $force_pass_change, $locked, $agreement_accepted, $user_level) = mysqli_fetch_array($r, MYSQLI_NUM);
			mysqli_free_result($r);
			
			// Check the password:
			$version = explode('.', PHP_VERSION);
			if 	(  (($version[0] == 7) && (password_verify($p, $pass))) 
				|| (($version[0] == 5) && (md5($p) == $pass )) 
			 	) {
			/*
			if (password_verify($p, $pass)) {
			*/
			//				$mdate=strtotime("now");
				$userValid=TRUE;
					
				if ($locked == 'Y') {
					echo '<p class="error">Your user id is locked.</p>';
					$userValid=FALSE;
				}else{
					echo '<p class="error">Your user id is not locked.</p>';
					echo $userValid;
				}
				

				if ($userValid) {
				// Store the info in the session:
					$_SESSION['user_id'] = $user_id;
					$_SESSION['first_name'] = $first_name;
					$_SESSION['last_name'] = $last_name;
					$_SESSION['co_name'] = $co_name;
					$_SESSION['u_role'] = $role;
					$_SESSION['email'] = $email;
					$_SESSION['work_phone'] = $work_phone;
					$_SESSION['chapter'] = $belong_to_chapter;
					$_SESSION['force_pass_change'] = $force_pass_change;
					$_SESSION['agreement_accepted'] = $agreement_accepted;
					$_SESSION['user_level'] = $user_level;
					//$_SESSION['user_level'] = 3;
					
					$invalid_login_count = 0;
					$query="UPDATE users as U SET U.invalid_login_count = $invalid_login_count, U.update_date=NOW() WHERE U.user_id = '$user_id'";
					$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
					
					$status = 'Active';
                    $q1="INSERT INTO sessions (session_id, userid, start_date, start_time, last_active, status, creation_date, last_update, remote_address, user_agent)
                     VALUES ('$session', '$user_id', NOW(), NOW(), NOW(),'$status', NOW(), NOW(), '$remoteAddress', '$userAgent')";
                    $r = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
					
					
					if (mysqli_error($dbc) == "" ) {
						$last_row = mysqli_insert_id($dbc);
						$_SESSION['session_id']=$last_row;
						echo $_SESSION['session_id'];
                    } else {
					
                        trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
					}
					
					mysqli_close($dbc);

					if ($agreement_accepted == 'N') {
						$url = BASE_URL . 'acceptagree.php'; // Define the URL.
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.	
					} 
					if ($force_pass_change == 'Y') {
						$url = BASE_URL . 'change_password.php'; // Define the URL.
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}

				// Redirect the user:
					$url = BASE_URL . 'index.php'; // Define the URL.
					ob_end_clean(); // Delete the buffer.
					header("Location: $url");
					exit(); // Quit the script.
				}
			} else {
		//		echo '<p class="error">Invalid Login Count='.$invalid_login_count;
				$invalid_login_count = $invalid_login_count + 1;
				if ($invalid_login_count >= 5) {
					$locked = 'Y';
					echo '<p class="error">Due to multiple invalid login attempts, your user id is now disabled.</p>';
				}
				$query="UPDATE users as U SET U.invalid_login_count = $invalid_login_count, U.locked = '$locked', U.update_date=NOW()
				WHERE U.user_id = '$user_id'";
				$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
				echo '<p class="error">Either the email address and password entered do not match those on file or your account is not yet activated by the administrator.</p>';
				
				$q1="INSERT INTO failedlogins (attempt_date, attempt_time, session_id, userid, remote_address, user_agent, invalid_login_count, lock_status)
				VALUES (NOW(), NOW(),'$session', $user_id,'$remoteAddress','$userAgent',$invalid_login_count,'$locked')";
			    $r = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
				mysqli_close($dbc);
			}   
		} else { // No match was made.
			echo '<p class="error">Either the email address and password entered do not match those on file or you have not yet activated your account.</p>';
			mysqli_close($dbc);	
		}
	} else { // If everything wasn't OK.
		echo '<p class="error">Please try again.</p>';
	}
	derive_question_and_answer();
	$q=$_SESSION['question'];
} // End of SUBMIT conditional. 
else {
	derive_question_and_answer();
	$q=$_SESSION['question'];
}

$_SESSION['last_action'] = time();
// ------------This function is to check the human OR  bot --------------

function derive_question_and_answer() {
	$num1 = rand(1,10);
	$num2 = rand(1,10);
	$op = rand(1,2);
	$question = 'Are you a human? Calculate: ';
	$ans = 0;
	if ($op == 1) {
		$ans = $num1 + $num2;
		$question .= $num1.' + '.$num2.' = ';;
	} else {
	 $ans = $num1 * $num2;
	 $question .= $num1.' X '.$num2.' = ';
	}
	$_SESSION['ans'] = $ans;
	$_SESSION['question'] = $question;
}
?>

<!-- Below is the HTML part ---------------------------------->

<h1>Login</h1>
<p>Your browser must allow cookies in order to log in.</p>
<br>
<form class="form-horizontal"  method="post">
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="email">Email Address:</label>
	<div class="col-sm-3">
		<input type="email" class="form-control" name="email" id="email" size="20" maxlength="60" placeholder="Enter your Email id">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="pass">Password:</label>
	<div class="col-sm-3">
		<input type="password" class="form-control" name="pass" id="pass" size="20" placeholder="Enter password to login">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="answer"><?php echo'<span>'.$q.'</span>';?></label>
	<div class="col-sm-3">
		<input type="text" class="form-control" name="answer" id="answer" size="20" placeholder="Enter the result of calculation">
	</div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-6">
	<button name="submit" type="submit" class="btn btn-primary" >LOGIN</button>
	<button type="reset" class="btn btn-primary" value="Reset">Reset</button>
	<br><br> 
	<p>If you are not a member yet, then please    <a href="register.php">Register</a></p>
	<a href="forgot_password.php">forgot your password?</a></p>
	
</div>
</div>	

</form>

<?php include('includes/footer.html'); ?>