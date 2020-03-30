<?php # Script 18.6 - add_users.php
// This is the page where you can add new users.
require('includes/config.inc.php');
$page_title = 'WIL | Add User';
include('includes/header.html');

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	// Need the database connection:
	require(MYSQL);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $cn = $ur = $ch = $ul = $wp = $p = FALSE;

	// Check for a first name:
	if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}

	// Check for a last name:
	if (preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}

	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string($dbc, $trimmed['email']);
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
		if (strlen($trimmed['password1']) >= 5) {
			if ($trimmed['password1'] == $trimmed['password2']) {
				$p = password_hash($trimmed['password1'], PASSWORD_DEFAULT);
			} else {
				echo '<p class="error">Your password did not match the confirmed password!</p>';
			}
		} else {
			echo '<p class="error">Please enter a valid password!</p>';
		}

	// Check for Company Name:
		if ($trimmed['co_name']!= "") {
			$cn = mysqli_real_escape_string($dbc, $trimmed['co_name']);
		} else {
			echo '<p class="error">Please enter your company name!</p>';
		}

	// Check for Role:
		if ($trimmed['u_role']!= "") {
			$ur = mysqli_real_escape_string($dbc, $trimmed['u_role']);
		} else {
			echo '<p class="error">Please enter your role!</p>';
		}

	// Check for Work Phone:
		
			if ($trimmed['work_phone']!= "") {
				$wp = mysqli_real_escape_string($dbc, $trimmed['work_phone']);
			} else {
				echo '<p class="error">Please enter your work phone!</p>';
			}

	// Check for Chapter:
			if (($trimmed['chapter']!="") && ($trimmed['chapter'] !="default_chapter")){
				$ch = mysqli_real_escape_string($dbc, $trimmed['chapter']);
			} else {
				echo '<p class="error">Please choose which chapter you would like to belong!</p>';
			} 
			

			if ($fn && $ln && $e && $cn && $ur && $ch && $wp && $p) { // If everything's OK...
		
					// Make sure the email address is available:
			$q = "SELECT user_id FROM users WHERE email='$e'";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

			if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));
			$email = urlencode($e);

			// Add the user to the database:
			$q = "INSERT INTO users (first_name, last_name, email, work_phone, pass, co_name, u_role, chapter, active, registration_date, chapter_admin, overAllSite_admin, exultAdmin) VALUES ('$fn', '$ln', '$e', '$wp', '$p', '$cn', '$ur', '$ch', '$a', NOW(), 'N', 'N', 'N')";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				$link_address = BASE_URL . 'activate.php?x=' . $e . "&y=$a";
				// Send the email:
				$body = "Thank you for adding/Registerng New User. A confirmation email has been sent to your address. To activate your account, please click on this link:  \n\n";
				$body .= '<a href="'.$link_address.'">Activate your account</a>';
				
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@WIL.com');

				// ------------This is the Mail for OverAll-Site-Admin/Admin to Approve the User Account----------------
				//$link_address1 = BASE_URL . 'approve.php?x=' . $e;
				//$body1 = "This user had been registered. To approve this account, please click on the link:  \n\n";
				//$body1 .= '<a href="'.$link_address1.'">Approve account</a>';
				//mail($trimmed['skoujalgi@exultancy.com'], 'Waiting for Approval', $body1, 'From: admin@WIL.com');


				// Finish the page:
				//echo '<h3>Thank you for registering! A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account.</h3>';
				
				// I have added this code to see the activation link on the page.
				echo $body;

				echo '<form action="index.php">';
				echo '<div class="form-group">
					<div class="col-sm-2">
						<input type="submit" class="btn btn-primary" name="Back" value="Back">
					</div>
					</div>';        
				echo '</form>';
				echo '</p>';

				include('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.

			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}

		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}

	}

}

?>
<div ng-app="thisapp" ng-controller="regController">
<div class="ng-view">
<div class="container">

<h1>Add Users</h1>
<form action="add_users.php" class="form-horizontal" method="post">
<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="first_name">First Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="first_name" id="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" placeholder="Enter your First Name">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="last_name">Last Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="last_name" id="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" placeholder="Enter your Last Name">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="email">Email Address:</label>
				<div class="col-sm-4">
					<input type="email" class="form-control" name="email" id="email" size="20" maxlength="60" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" placeholder="xxxxxx@xxxxx.com">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="password1">Password:</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" name="password1" id="password1" size="20" value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" placeholder="Enter password">
				</div>
			</div>
	
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="password2">Confirm Password:</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" name="password2" id="password2" size="20" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" placeholder="Confirm your password">
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="co_name">Company Name:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="co_name" id="co_name" size="20" maxlength="20" value="<?php if (isset($trimmed['co_name'])) echo $trimmed['co_name']; ?>" placeholder="Your Company Name">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="work_phone">Work Phone:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="work_phone" id="work_phone" size="20" maxlength="60" value="<?php if (isset($trimmed['work_phone'])) echo $trimmed['work_phone']; ?>" placeholder="123-456-7890">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="u_role">Role:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="u_role" id="u_role" size="20" maxlength="40" value="<?php if (isset($trimmed['role'])) echo $trimmed['u_role']; ?>" placeholder="Enter your Role">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="chapter">Select Chapter:</label>
				<div class="col-sm-4">
					<select class="form-control" name='chapter' ng-model='chapter' ng-focus="getChapters()">
						<option  ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
                    </select>
				</div>
			</div>
		
			<button name="submit" type="submit" class="btn btn-primary" >Add User</button>
			<button type="reset" class="btn btn-primary" value="Reset">Clear</button>
	   
		</div>

		</div>	
		
	</div>	
	</div>

</form>

</div>
</div>
</div>



<?php include('includes/footer.html'); ?>

<script type="text/javascript">

        var app = angular.module('thisapp', []);

        app.controller('regController', function($scope, $http, $window) {

		$scope.getChapters = function(){ 
            
            $http.get("retrieveChapters.php").then(
				function success(response){
					
                    $scope.myData = response.data.results;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
                });
       		 } 
	});               
</script>

	