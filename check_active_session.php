<?php
require('usagelog.php');
if (isset($_SESSION['user_id']) && $_SESSION['agreement_accepted'] == 'N') {
	$url = BASE_URL . 'acceptagree.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
};
if (isset($_SESSION['user_id']) && $_SESSION['force_pass_change'] == 'Y') {
	$url = BASE_URL . 'change_password.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
};
if ($req_user_level <> -1) {
	if (!isset($_SESSION['user_id']) ||
	   (isset($_SESSION['user_id']) && $_SESSION['user_level'] < $req_user_level)) {
			$url = BASE_URL . 'index.php'; // Define the URL.
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
	}
}