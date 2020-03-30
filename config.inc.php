<?php # config.inc.php
/* This script:
 * - define constants and settings
 * - dictates how errors are handled
 * - defines useful functions
 */

// Document who created this site, when, why, etc.


// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
define('LIVE', FALSE);

// Tech Support contact address:
define('TECH_SUPPORT_EMAIL', 'skoujalgi@exultancy.com');

// Site Admin contact address:

//---------------- Activate Sadhana's email id when pages are working!
//define('ADMIN_EMAIL', 'Sadhana.Chitale@nyulangone.org');
//---------------------
define('ADMIN_EMAIL', 'skoujalgi@exultancy.com');

// Sender e-mail address:
define('AUTO_EMAIL_SENDER', 'webmaster@WIL.com');


// Site URL (base for all redirections):
define('BASE_URL', 'http://localhost/~shivaleela/WIL/html/');

// Documents Directory:
define('DOCUMENTS_SUBDIR', 'documents/');

// Images Directory:
define('IMAGES_SUBDIR', 'images/');

// Site Logo File:
define('SITE_LOGO_FILE', 'WILlogo.png');


// Location of the MySQL connection script:
define('MYSQL', '/Users/shivaleela/Sites/WIL/mysqli_connect.php');

// Flag variable to display events on home Page: 11/27
//*************************************define('DISPLAYEVENTS', 'TRUE');

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set('America/New_York');

//Flag variable for showing the Upcoming events: ----Added on 12/09/2019---------
define('UPCOMING_EVENTS', FALSE);

	// Flag variables for Usage Log:
define('WRITE_LOG', FALSE);
// Flag to turn Timeout checking on or off
define('CHECK_TIMEOUT', TRUE);
define('TIMEOUT_IN_MINUTES',15); // Please use 2 digit minutes so 5 should be 05 MAX 60
// ************ SETTINGS ************ //
// ********************************** //


// ****************************************** //
// ************ ERROR MANAGEMENT ************ //

// Create the error handler:
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Build the error message:
	$message = "An error occurred in script '$e_file' on line $e_line: $e_message\n";

	// Add the date and time:
	$message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n";

	if (!LIVE) { // Development (print the error).

		// Show the error message:
		echo '<div class="error">' . nl2br($message);

		// Add the variables and a backtrace:
		//commented the line below to make it work at Exutlancy server
		//echo '<pre>' . print_r ($e_vars, 1) . "\n";
		
		debug_print_backtrace();
		echo '</pre></div>';

	} else { // Don't show the error:

		// Send an email to the admin:
		$body = '<!DOCTYPE html>
		<html lang="en">
		<head>
		<title>Error in DSM-5 ASRS Web App</title>
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
		<h3>DSM-5 Adult Self-Report Scale (ASRS) Screener</h3>
		<h3>An Online Adult ADHD Screener</h3>
		<br>
		<p></P>
		<p>The following error occured in the DSM-5 ASRS Online Adult ADHD Screener website:</p>
		<br>
		<br><p>'.$message . "\n" . print_r ($e_vars, 1).'
		</p><br>
		<p>Please address this error as soon as possible.</p>
		<p>Thank you</p>
		<p>Sincerely</p>
		<br>
		<p>ADHDinAdults Team</p>
		</div>
		</body>
		</html>';

		// Subject
		$subject = 'DSM5-ASRS Website Error.';

		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';

		// Additional headers
		$headers[] = 'To: <'.TECH_SUPPORT_EMAIL.'>';
		$headers[] = 'From: ADHDinAdults <'.AUTO_EMAIL_SENDER.'>';
//			$headers[] = 'Cc: birthdayarchive@example.com';
//			$headers[] = 'Bcc: birthdaycheck@example.com';
//			mail($_POST['email'], 'Your temporary password.', $body, 'From: '.AUTO_EMAIL_SENDER);
		// Mail it
		mail(TECH_SUPPORT_EMAIL, $subject, $body, implode("\r\n", $headers));
		// Print a message and wrap up:

		// Only print an error message if the error isn't a notice:
		if ($e_number != E_NOTICE) {
			echo '<div class="error">A system error occurred. We apologize for the inconvenience.</div><br>';
		}
	} // End of !LIVE IF.

} // End of my_error_handler() definition.

// Use my error handler:
set_error_handler('my_error_handler');

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
	$data = htmlspecialchars($data);
	if ($data==null)
	$data='';
    return $data;
}
