<?php # Script 18.11 - change_password.php
// This page allows a logged-in user to change their password.
require('includes/config.inc.php');
$page_title = 'WIL - Change Password';
include('includes/header.html');
$req_user_level=1;
require('includes/check_active_session.php');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require(MYSQL);
	$trimmed = array_map('test_input', $_POST);

	// Check for a new password and match against the confirmed password:
	$oldpassvalid=FALSE;
	$version = explode('.', PHP_VERSION);
	$op = $trimmed['oldpass'];
	$query = 	"SELECT pass 
			  	 FROM   users 
			  	 WHERE  user_id={$_SESSION['user_id']}";		
	$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
	if 	(@mysqli_num_rows($r) == 1) { // A match was made.
		list($pass) = mysqli_fetch_array($r, MYSQLI_NUM);
		mysqli_free_result($r);
		if 	(  (($version[0] == 7) && (password_verify($op, $pass))) 
			|| (($version[0] == 5) && (md5($op) == $pass )) 
			 ) {
				$oldpassvalid=TRUE;
		} else {
				echo '<p class="error">Invalid password! Please try again.</p>';
		}
	} else {
		echo '<p class="error">There was any error accessing your user id. Please try again after some time.</p>';
	}
	$p  = FALSE;
	if ($oldpassvalid) {
		if (strlen($trimmed['password1']) >= 10) {
			if ($trimmed['password1'] == $trimmed['password2']) {
				if ($trimmed['password1'] != $trimmed['oldpass']) {
					$version = explode('.', PHP_VERSION);
					if ($version[0] == 7) {
						$p = password_hash($trimmed['password1'], PASSWORD_DEFAULT);
					} else {
						$p = md5($trimmed['password1']);
					}
				} else {
					echo '<p class="error">New Password must be different than the current password!</p>';
				}
			} else {
				echo '<p class="error">Your password did not match the confirmed password!</p>';
			}
		} else {
			echo '<p class="error">Please enter a valid password!</p>';
		}
	}
	if ($p && $oldpassvalid) { // If everything's OK.

		// Make the query:
		$q = "UPDATE users SET pass='$p', force_pass_change='N',pass_change_date = NOW(), update_date = NOW() WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Send an email, if desired.
			echo '<p><b>Your password has been changed.</b></p>';
			?>
			
	<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='index.php';" class="btn btn-primary" >Back</button>
	</div>
	
	<?php
			mysqli_close($dbc); // Close the database connection.
			include('includes/footer.html'); // Include the HTML footer.
			exit();

		} else { // If it did not run OK.

			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>';

		}

	} else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<h1>Change Password</h1>
<form class="form-horizontal" action="change_password.php" method="post">
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="oldpass">Current Password:</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="oldpass" placeholder="Enter your current password">
	</div>
</div>	
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="password1">New Password:</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="password1" placeholder="Select a password at least 10 characters long">
	</div>
	<span style="color: white;" class="help-block">Password must be at least 10 characters long</span>
</div>	
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="password2">Confirm New Password:</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="password2" placeholder="Must match the new password">
	</div>
	<span style="color: white;" class="help-block">Password must be at least 10 characters long</span>
</div>	

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Change Password</button>
	   <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
    </div>
</div>	
</form>

<?php include('includes/footer.html'); ?>