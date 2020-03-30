<?php
require('includes/config.inc.php');

// Start output buffering:
ob_start();

// Initialize a session:
session_start();

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
    header("Location: $url");
 //   echo 'Quitting the Proce route.php'.$url.' User ID = '.$_SESSION['user_id'];
    exit(); // Quit the script.
}
require('responseclass.php');
$response = new Response;
$response->setStatus("");
$response->setMessage("");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Need the database connection:
	require(MYSQL);

    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, FALSE);
 //   $trimmed = array_map('test_input', $input);

	// Trim all the incoming data:
 
    $message_type   =test_input($input->message_type);
    $message_subtype=test_input($input->message_subtype);
    $user_object = $input->object;
    $first_name     =test_input($user_object->first_name);
    $last_name      =test_input($user_object->last_name);
    $email          =test_input($user_object->email);
    $password1      =test_input($user_object->password1);
    $password2      =test_input($user_object->password2);
	$oid            =test_input($user_object->org_id);
	$otype			=test_input($user_object->org_type);
	$oname			=test_input($user_object->org_name);
	$olevel=0;
	if (isset($_SESSION['user_level']) && $_SESSION['user_level'] > 2) {
		if ($otype == 'IT Support') {
			$olevel = 4;
		} else if ($otype == 'ADHD In Adults') {
			$olevel = 3;
		} else if ($otype == 'NYU') {
			$olevel = 2;
		} else if ($otype == 'Provider') {
			$olevel = 1;
		}
	}
if ($message_type == 'Users' && $message_subtype == 'Add')  {
	// Assume invalid values:
	$fn = $ln = $e = $p = FALSE;

	// Check for a first name:
	if (preg_match('/^[A-Z \'.-]{2,20}$/i', $first_name)) {
		$fn = mysqli_real_escape_string($dbc, $first_name);
	} else {
//		echo '<p class="error">Please enter your first name!</p>';
		$response->setStatus("Error");
		$response->setMessage("Please enter your first name!");
	}

	// Check for a last name:
	if (preg_match('/^[A-Z \'.-]{2,40}$/i', $last_name)) {
		$ln = mysqli_real_escape_string($dbc, $last_name);
	} else {
//		echo '<p class="error">Please enter your last name!</p>';
		$response->setStatus("Error");
		$response->setMessage($response->getMessage()."\nPlease enter your last name!");
	}

	// Check for an email address:
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string($dbc, $email);
	} else {
//		echo '<p class="error">Please enter a valid email address!</p>';
		$response->setStatus("Error");
		$response->setMessage($response->getMessage()."\nPlease enter a valid email address!");
	}

	// Check for a password and match against the confirmed password:
	if (strlen($password1) >= 10) {
		if ($password1 == $password2) {
			$version = explode('.', PHP_VERSION);
			if 	($version[0] == 7) {
				$p = password_hash($password1, PASSWORD_DEFAULT);
			} else {
				$p = md5($password1);
			}
		} else {
//			echo '<p class="error">Your password did not match the confirmed password!</p>';
			$response->setStatus("Error");
			$response->setMessage($response->getMessage()."\nYour password did not match the confirmed password!");
		}
	} else {
//		echo '<p class="error">Please enter a valid password!</p>';
		$response->setStatus("Error");
		$response->setMessage($response->getMessage()."\nPlease enter a valid password!");
	}

	if ($fn && $ln && $e && $p) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT user_id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc, $q); // or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
        if (mysqli_error($dbc) == "" ) {
			if (mysqli_num_rows($r) == 0) { // Available.
				// Create the activation code:
				$a = md5(uniqid(rand(), true));
				$oid = $oid;
				// Add the user to the database:
				$q = "INSERT INTO users (email, pass, first_name, last_name, active, registration_date, org_id, user_level, invalid_login_count, force_pass_change, locked, agreement_accepted, pass_change_date, update_date) 
			      	  VALUES ('$e', '$p', '$fn', '$ln', NULL, NOW(), $oid, $olevel, 0, 'Y','N','N', NOW(), NOW())";
				$r = mysqli_query($dbc, $q); // or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
				if (mysqli_error($dbc) == "" ) {
					if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
						// Send the email:
						$mailSuccess=FALSE;
						if (isset($_SESSION['user_level']) && $_SESSION['user_level'] > 2) {
										// Send an email:
							$body = '<!DOCTYPE html>
									<html lang="en">
									<head>
									<title>DSM-5 ASRS Screener-Your account has been setup</title>
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
									<img src="'.BASE_URL.IMAGES_SUBDIR.SITE_LOGO_FILE.'" class="media-object" style="width:128px" alt="logo">
									<h3>DSM-5 Adult Self-Report Scale (ASRS) Screener</h3>
									<h3>An Online Adult ADHD Screener</h3>
									<p><b>Dear '.$fn.'</b></p>
									<p>Your user account to login into the DSM-5 ASRS Screener website has been created by the administrator. 
									To start using the account, please login at '.BASE_URL.' using the following information: </p>
									<p>First Name:         '.$fn.'</p>
									<p>Last Name:          '.$ln.'</p>
									<p>Organization Name:  '.$oname.'</p>
									<p>Your e-mail id:     '.$e.'</p>
									<p>Temporary Password: '.$password1.'</p>
									<p>Once you login using the above e-mail id and password, you will have to accept the terms and conditions 
									for usage of the site and then you can choose your new password.</p>
									<p>Thank you</p>
									<p>Sincerely</p>
									<br>
									<p>ADHDinAdults Team</p>
									</div>
									</body>
									</html>';
									$to = $e; // note the comma
									// Subject
									$subject = 'DSM-5 ASRS Screener-Your account has been setup';
									$headers[] = 'MIME-Version: 1.0';
									$headers[] = 'Content-type: text/html; charset=iso-8859-1';
									// Additional headers
									$headers[] = 'To: <'.$to.'>';
									$headers[] = 'From: ADHDinAdults <'.AUTO_EMAIL_SENDER.'>';
// 									Mail it
									$mailSuccess=mail($to, $subject, $body, implode("\r\n", $headers));
						}
						$body = "Thank you for registering at ".BASE_URL.". To activate your account, please click on this link:\n\n";
						$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
//						mail($trimmed['email'], 'Registration Confirmation', $body, 'From: webmaster@exultancy.com');
//						echo '<p>The new user has been added. A confirmation email has been sent to the e-mail address of the new user.</p>';
						$response->setStatus("Success");
						if ($mailSuccess) {
							$response->setMessage($response->getMessage()."\nThe new user has been added. A confirmation email has been sent to the e-mail address of the new user.");
						} else {
							$response->setMessage($response->getMessage()."\nThe new user has been added. A confirmation email count not be sent to the e-mail address of the new user.");
						}	
					} else { // If it did not run OK.
//						echo '<p class="error">The user could not be added due to a system error. We apologize for any inconvenience.</p>';
						$response->setStatus("Error");
						$response->setMessage($response->getMessage()."\nThe user could not be added due to a system error. We apologize for any inconvenience.");
					}
				} else {
					$response->setStatus("Error");
					$response->setMessage('Query: '.$q.' MySQL Error: '.mysqli_error($dbc).' Error while adding the user.');
				}
			} else { // The email address is not available.
//				echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
				$response->setStatus("Error");
				$response->setMessage($response->getMessage()."\nThat email address has already been registered. If you have forgotten your password, use the Forgot Password option to have your password sent to you.");
			}
		} else {
			$response->setStatus("Error");
			$response->setMessage('Query: '.$q.' MySQL Error: '.mysqli_error($dbc).' Error while checking whether the user is already registered.');
		}
	} else { // If one of the data tests failed.
//		echo '<p class="error">Please try again.</p>';
		$response->setStatus("Error");
		$response->setMessage($response->getMessage()."\nPlease try again!");
	}

	mysqli_close($dbc);
}

} // End of the main Submit conditional.
  else {
    $response->setStatus("Error");
    $response->setMessage('This operation is not permitted in the Add Users option.');
}
echo json_encode($response); 
?>
