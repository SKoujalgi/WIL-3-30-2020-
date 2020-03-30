<?php # Script 18.9 - logout.php
// This is the logout page for the site.
require('includes/config.inc.php');
$page_title = 'WIL | Logout';
include('includes/header.html');
require('includes/usagelog.php');
// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['first_name'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

} else { // Log out the user.
	require(MYSQL);
	$session_id=$_SESSION['session_id'];
	$event='Closed';
	if (isset($_GET['event'])) {
		$event=test_input($_GET['event']);
	}
	$query = "UPDATE sessions SET end_date=NOW(), end_time=NOW(), status='$event',
	last_update=NOW()
	WHERE id =  $session_id"; 
	$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));

	unset($_SESSION['user_id']);
	unset($_SESSION['first_name']);
	unset($_SESSION['last_name']);
	unset($_SESSION['co_name']);
	unset($_SESSION['u_role']);
	unset($_SESSION['email']);
	unset($_SESSION['work_phone']);
	unset($_SESSION['chapter']);
	unset($_SESSION['force_pass_change']); 
	unset($_SESSION['agreement_accepted']);
	unset($_SESSION['user_level']);
	unset($_SESSION['session_id']);

	session_destroy(); // Destroy the session itself.
	setcookie(session_name(), '', time()-3600); // Destroy the cookie.
/*

    $_SESSION ['user_id'] = NULL;
	$_SESSION = []; // Destroy the variables.
	
	session_destroy(); // Destroy the session itself.
	setcookie(session_name(), '', time()-3600); // Destroy the cookie.
*/	
    ob_end_clean(); // Delete the buffer.
	// Print a customized message:
	//$refferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']:'Not known';
	$url = BASE_URL . "end_session.php?event=$event"; // Define the URL.
	header("Location: $url");
	exit(); // Quit the script.
}



include('includes/footer.html');
?>