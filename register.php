<?php # Script 18.6 - register.php
// This is the registration page for the site.
require('includes/config.inc.php');
$page_title = 'Register to WIL';
include('includes/header.html');
require('includes/usagelog.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Need the database connection:
	require(MYSQL);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = $cn = $r = $ch = $pp = FALSE;
	

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

	// Check for Work Phone:
		if ($trimmed['work_phone']!= "") {
			$wp = mysqli_real_escape_string($dbc, $trimmed['work_phone']);
		} else {
			$wp = "";
		}

	// Check for a password and match against the confirmed password:
	if (strlen($trimmed['password1']) >= 10) {
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

	// Check for Chapter:

		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chapter'])) {
			$ch = mysqli_real_escape_string($dbc, $trimmed['chapter']);
		} else {
			echo '<p class="error">Please enter the Chapter it belongs to!</p>';
		}

/*
		if (($trimmed['chapter']!="") && ($trimmed['chapter'] !="default_chapter")){
			$ch = mysqli_real_escape_string($dbc, $trimmed['chapter']);
		} else {
			echo '<p class="error">Please choose which chapter you would like to belong!</p>';
		} 
		*/
	// Check for linkedIn:
		if ($trimmed['linkedin']!="") {
			$li = mysqli_real_escape_string($dbc, $trimmed['linkedin']);
		} else{
			$li = "";
		}

	// Check for Twitter:
		if ($trimmed['twitter']!="") {
			$tw = mysqli_real_escape_string($dbc, $trimmed['twitter']);
		} else{
			$tw = "";
		}
			
	// Check for Privacy Box
	if( empty($_POST["privacy_box"]) ) {
		echo '<p class="error">Please read and check the Privacy Policy!</p>';
	}else{
	
		if ($fn && $ln && $e && $p && $cn && $ur && $ch) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT user_id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));
			$email = urlencode($e);

			// Add the user to the database:
			$q = "INSERT INTO users (first_name, last_name, email, work_phone, pass, co_name, u_role, chapter, linkedin, twitter, active, force_pass_change, registration_date, chapter_admin, overAllSite_admin, exultAdmin) VALUES ('$fn', '$ln', '$e', '$wp', '$p', '$cn', '$ur', '$ch', '$li', '$tw', '$a', 'N', NOW(), 'N', 'N', 'N')";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
			
			
			//get Chapter Admin email address
			$q1 = "SELECT chapter_admin_email FROM Chapter WHERE chapter_name='$ch'";
			$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
			
			while($row = mysqli_fetch_assoc($r1)){
				$e1 =  $row["chapter_admin_email"];
				//echo $e1;
			}

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				$link_address = BASE_URL . 'activate.php?x=' . $e . "&y=$a";
				echo $link_address;
				// Send the email:
				//-----------------------COmmented 3/25
			/*	$body = '<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>Women In Licensing Registration Confirmation!</title>
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
			<p>Thank you for registering to Women In Licensing website. A confirmation email is sent to the Chapter admin.</p>
			<p>You will receive a email once Chapter admin activates your account. You will then be able to login to the WIL website.</p>
			
			<p>Thank you!</p>
			<p>Sincerely</p>
			<br>
			<p>WIL Team</p>
			</div>
			</body>
			</html>';

			$body1= '<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>Registration Confirmation and Activate User!</title>
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
			<p><b>Dear Chapter admin</b></p>
			<p>A new Registration to Women In Licensing website is done. Please click on the link below to activate the account!</p>';
			
			$body1 .= '<a href="'.$link_address.'">Activate your account</a>';
			$body1 .= '<p>Thank you!</p>
			<p>Sincerely</p>
			<br>
			<p>WIL Team</p>
			</div>
			</body>
			</html>';

			$to = $e; // note the comma
			$subject = 'Registration Confirmation';
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers[] = 'To: <'.$to.'>';
			$headers[] = 'From: WIL <'.AUTO_EMAIL_SENDER.'>';


			$to1 = $e1;
			$subject1 = 'Registration Confirmation and Activate Account!';
			$headers1[] = 'MIME-Version: 1.0';
			$headers1[] = 'Content-type: text/html; charset=iso-8859-1';
			$headers1[] = 'To: <'.$to1.'>';
			$headers1[] = 'From: WIL <'.AUTO_EMAIL_SENDER.'>';

				//$body = "Thank you for registering. To activate your account, please click on this link:  \n\n";
				//$body .= '<a href="'.$link_address.'">Activate your account</a>';
				
				//mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@WIL.com');
				mail($to, $subject, $body, implode("\r\n", $headers));
				mail($to1, $subject1, $body1, implode("\r\n", $headers1)); */
				//----------------------till here 3/25

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
			$to = $e1; // note the comma

			// Subject
			$subject = 'New Registration.';

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

				// ------------This is the Mail for OverAll-Site-Admin/Admin to Approve the User Account----------------
				//$link_address1 = BASE_URL . 'approve.php?x=' . $e;
				//$body1 = "This user had been registered. To approve this account, please click on the link:  \n\n";
				//$body1 .= '<a href="'.$link_address1.'">Approve account</a>';
				//mail($trimmed['skoujalgi@exultancy.com'], 'Waiting for Approval', $body1, 'From: admin@WIL.com');


				// Finish the page:
				echo '<p>Thank you for registering! A confirmation email has been sent to your address. You will receive an email from Admin once your account is active.</p>';
				

				echo '<form action="index.php">';
				echo '<div class="form-group">
					<div class="col-sm-2">
						<input type="submit" class="btn btn-primary" name="Back" value="Back">
					</div>
					</div>';        
				echo '</form>';
				echo '</p>';

				// I have added this code to see the activation link on the page.
				//echo $body;
				mysqli_close($dbc);
				include('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.

			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}

		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}

	} else { // If one of the data tests failed.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc);

} 
}// End of the main Submit conditional.

function validateFields() {
	
	alert("Inside Validate function!!");
}


?>

<div ng-app="thisapp" ng-controller="regController">
<div class="ng-view">
<div class="container">
<h1>New Account Registration</h1>

<form name="register" action="register.php" class="form-horizontal" method="post">

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="first_name">First Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="first_name" maxlength="40" ng_model="first_name" ng_required="true" placeholder="Enter your First Name" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>"
>
					<div ng-messages="register.first_name.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="register.first_name.$error.required">You must enter your FirstName.
						</div>
					</div>	
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="last_name">Last Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="last_name" maxlength="40" ng-model="last_name" ng-required="true" placeholder="Enter your Last Name" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" >
					<div ng-messages="register.last_name.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="register.last_name.$error.required">You must enter your Last Name.
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="email">Email Address:</label>
				<div class="col-sm-6">
					<input type="email" class="form-control" name="email" maxlength="60" ng-model="email" ng-required="true" placeholder="email@server.com" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" >
					<div ng-messages="register.email.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="register.email.$error.required">Email ID is Required!
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="work_phone">Work Phone:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="work_phone" maxlength="15" ng-model="work_phone"  placeholder="XXX-XXX-XXXX" value="<?php if (isset($trimmed['work_phone'])) echo $trimmed['work_phone']; ?>">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="password1">Password:</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" name="password1" maxlength="50"  ng-model="password1" ng-required="true" placeholder="Select a password at least 10 characters long" value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>">
					<div ng-messages="register.password1.$error" style="color:red" role="alert">        
					<div ng-message="required" ng-if="register.password1.$error.required">You must enter a password
					</div>
					</div>
				</div>
			</div>
	
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="password2">Confirm Password:</label>
				<div class="col-sm-6">
					<input type="password" class="form-control" name="password2" maxlength="50" ng-model="password2" ng-required="true" placeholder="Confirm your password" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" >
					<div ng-messages="register.password2.$error" style="color:red" role="alert">        
					<div ng-message="required" ng-if="register.password2.$error.required">Re-enter your password again
					</div>
					</div>
				</div>
			</div>
		</div>	
	
		<div class="col-sm-8">
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="co_name">Company Name:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="co_name" maxlength="20" ng-model="co_name" ng-required="true" placeholder="Your Company Name" value="<?php if (isset($trimmed['co_name'])) echo $trimmed['co_name']; ?>" >
					<div ng-messages="register.co_name.$error" style="color:red" role="alert">        
					<div ng-message="required" ng-if="register.co_name.$error.required">Must provide Company Name
					</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="u_role">Role:</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" name="u_role" maxlength="20" ng-model="u_role" ng-required="true"  placeholder="Enter your Role" value="<?php if (isset($trimmed['role'])) echo $trimmed['u_role']; ?>" >
					<div ng-messages="register.u_role.$error" style="color:red" role="alert">        
					<div ng-message="required" ng-if="register.u_role.$error.required">Must enter your Role
					</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="chapter">Select Chapter:</label>
				<div class="col-sm-6">
				<select class="form-control" maxlength="20" name='chapter'ng-model='chapter' ng-focus="getChapters()" ng-required="true">
					<option value="">Select a chapter</option>
					<option  ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>	
				</select>
				<div ng-messages="register.chapter.$error" style="color:red" role="alert">        
				<div ng-message="required" ng-if="register.chapter.$error.required">Must select a Chapter
				</div>
				</div>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="linkedin">Your Linkedin URL:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="linkedin" maxlength="50" ng-model="linkedin" value="<?php if (isset($trimmed['linkedin'])) echo $trimmed['linkedin']; ?>" placeholder="Your LinkedIn link">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="twitter">Your Twitter URL:</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" name="twitter" maxlength="50" ng-model="twitter" value="<?php if (isset($trimmed['twitter'])) echo $trimmed['twitter']; ?>" placeholder="Your Twitter link">
				</div>
			</div>
			<input type='checkbox' name='privacy_box' /> 
				I agree with Women In License's <a href="privacypolicy.php" target="_new">Privacy Policy</a>&nbsp;&nbsp;
				<button name="submit" type="submit" class="btn btn-primary" >Register</button>
				<button type="reset" class="btn btn-primary" value="Reset">Reset</button>
				
		</div>

		</div>	
		
	</div>	
	</div>
	
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
					$scope.myData = response.data;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
                });
       		 } 


// Below is the code for disabled Register Button
				$scope.submit =
            function() {
                $scope.startLoader();
				data = JSON.stringify($scope.details);
                $http.post("register_be.php", data).then(function(response) {
                    $scope.stopLoader();
                    if (response.data.status=="Success") {
//                        $scope.displayStatus(response.data.message,'N');
						window.alert(response.data.message);
                        window.location="index.php";
                    } else {
                        $scope.displayStatus(response.data.message,'Y');
                    }
                },
                function(response) {
                    $scope.stopLoader();
                    $scope.displayStatus("There was an error processing your request.",'Y');
                });
        }

		$scope.isButtonDisabled = 
            function() {
                return ($scope.isValid($scope.details.npi)  || 
                        $scope.isValid($scope.details.orgname)  ||
                        $scope.isValid($scope.details.country)  ||
                        $scope.isValid($scope.details.website)  ||
                        $scope.isValid($scope.details.role)  ||
                        $scope.isValid($scope.details.firstname) ||
                        $scope.isValid($scope.details.lastname) ||
                        $scope.isValid($scope.details.email)     ||
                        $scope.isValid($scope.details.password1) ||
                        $scope.isValid($scope.details.password2)); 
                }




	});               
</script>