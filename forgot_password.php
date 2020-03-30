<?php # Script 18.10 - forgot_password.php
// This page allows a user to reset their password, if forgotten.
require('includes/config.inc.php');
$page_title = 'Forgot Your Password';
include('includes/header.html');
require('includes/usagelog.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQL);

	// Assume nothing:
	$uid = FALSE;
	$e=test_input($_POST['email']);

	// Validate the email address...
	if (!empty($e)) {

		// Check for the existence of that email address...
		$q = 'SELECT user_id, first_name FROM users WHERE email="'.  mysqli_real_escape_string($dbc, $_POST['email']) . '"';
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (mysqli_num_rows($r) == 1) { // Retrieve the user ID:
			list($uid, $fn) = mysqli_fetch_array($r, MYSQLI_NUM);
		} else { // No database match made.
			echo '<p class="error">The submitted email address does not match those on file!</p>';
		}

	} else { // No email!
		echo '<p class="error">You forgot to enter your email address!</p>';
	} // End of empty($_POST['email']) IF.

	if ($uid) { // If everything's OK.

		// Create a new, random password:
		$p = substr(md5(uniqid(rand(), true)), 3, 15);
		$version = explode('.', PHP_VERSION);
		if ($version[0] == 7) {
			$ph = password_hash($p,PASSWORD_DEFAULT);
		} else {
			$ph = md5($p);
		}

		// Update the database:
		$q = "UPDATE users SET pass='$ph', force_pass_change='Y', pass_change_date = NOW(),locked='N',invalid_login_count='0',update_date = NOW() WHERE user_id=$uid LIMIT 1";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Send an email:
			$body = '<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>Your Password Reset Request</title>
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<style>
			 #main {
				padding: 5px;
				padding-left:  15px;
				padding-right: 15px;
				background-color: #ffffff;
				border-radius: 0 0 5px 5px;
			}
			
			h3 {
				font-family: Georgia, serif;
				text-align: center;
				color: #0468ab;
				font-size: 24px;
			}
			</style>
			</head>
			<body>
			<div id="main">
			<img src="'.BASE_URL.IMAGES_SUBDIR.SITE_LOGO_FILE.'" class="media-object" style="width:128px">
			<h3>Women In Licensing</h3>
			<h3>Support and Succeed together!!</h3>
			<p><b>Dear '.$fn.'</b></p>
			<p>Your password to log into the "Women In Licensing" website has been temporarily changed to <b>'.$p.'</b>. 
			Please log in using this password and this email address. Then you will be taken to the change password screen where
			you can choose your new password.</p>
			<p>Thank you</p>
			<p>Sincerely</p>
			<br>
			<p>WIL Team</p>
			</div>
			</body>
			</html>';
			$to = $e; // note the comma

			// Subject
			$subject = 'Reset Password.';

			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			// Additional headers
			$headers[] = 'To: <'.$to.'>';
			$headers[] = 'From: WIL <'.AUTO_EMAIL_SENDER.'>';
//			$headers[] = 'Cc: birthdayarchive@example.com';
//			$headers[] = 'Bcc: birthdaycheck@example.com';
//			mail($_POST['email'], 'Your temporary password.', $body, 'From: '.AUTO_EMAIL_SENDER);
			// Mail it
			mail($to, $subject, $body, implode("\r\n", $headers));
			// Print a message and wrap up:
			echo '<p>Your password has been changed. You will receive the new, temporary password at the email address with 
			which you registered. Once you have logged in with this password, you will be taken to the "Change Password" screen 
			where you can choose your new password.</p>';
//			echo $body;
			mysqli_close($dbc);
			include('includes/footer.html');
			exit(); // Stop the script.

		} else { // If it did not run OK.
			echo '<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>';
		}

	} else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>

<h1>Forgot Your Password</h1>
<br>
<p>Enter your email address below and your password will be reset.</p>
<br>
<form class="form-horizontal" action="forgot_password.php" method="post">
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="email">Email Address:</label>
	<div class="col-sm-3">
		<input type="email" class="form-control" name="email" size="40" maxlength="60" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" placeholder="Enter your Email id">
	</div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
	   <button name="submit" type="submit" class="btn btn-primary" >Reset Password</button>
	   <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
    </div>
</div>	
</form>

<?php include('includes/footer.html'); ?>
