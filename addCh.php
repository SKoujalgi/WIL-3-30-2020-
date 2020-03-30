<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Add a Chapter';
include('includes/header.html');
require('includes/usagelog.php');


if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

	// Need the database connection:
	require(MYSQL);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$chN = $chAdN  = $chSp = FALSE;

		// Check for a Chapter name:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chaName'])) {
			$chN = mysqli_real_escape_string($dbc, $trimmed['chaName']);
		} else {
			echo '<p class="error">Please enter chapter_name!</p>';
		}

		// Check for a Admin first name:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chaAdmin'])) {
			$chAdN = mysqli_real_escape_string($dbc, $trimmed['chaAdmin']);
		} else {
			echo '<p class="error">Please enter Admin first name!</p>';
		}
	
		// Check for an admin email address:
		if (filter_var($trimmed['chEmail'], FILTER_VALIDATE_EMAIL)) {
			$e = mysqli_real_escape_string($dbc, $trimmed['chEmail']);
		} else {
			echo '<p class="error">Please enter a valid email address!</p>';
		}

		// Check for Chapter Sponsors:
		if (preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['chapSponsors'])) {
			$chSp = mysqli_real_escape_string($dbc, $trimmed['chapSponsors']);
		} else {
			echo '<p class="error">Please enter Chapter Sponsors!</p>';
		}

		if ($chN && $chAdN && $chSp) { // If everything's OK...
			

			$q = "INSERT INTO Chapter (chapter_name, chapter_admin, chapter_admin_email, sponsors_name, ch_create_date, ch_update_date) VALUES ('$chN', '$chAdN', '$e', '$chSp', NOW(), NOW())";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

			/*$q1 = "SELECT email from users where (first_name='$chAdF' AND last_name='$chAdL')";
			$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
			$email = $r1;*/
			if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....


				$body = "You have successfully added a New Chapter to WIL.";
			
				echo $body;
				//$body = "You have successfully added a New Chapter to WIL. An email will be sent to Chapter Admin.  \n\n";
				$body = '<!DOCTYPE html>
			<html lang="en">
			<head>
			<title>New Chapter Created!</title>
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
			<p><b>Dear Chapter Admin</b></p>
			<p>You have successfully added a New Chapter to WIL. An email will be sent to Chapter Admin.</p>

			<p>Thank you!</p>
			<p>Sincerely</p>
			<br>
			<p>WIL Team</p>
			</div>
			</body>
			</html>';

				$to = $e; // note the comma
			
				// Subject
				$subject = 'New Chapter Created!';
			

				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';

				mail($to, $subject, $body, implode("\r\n", $headers));
				//echo $body;
				//echo "You have successfully added a Chapter to WIL. An email will be sent to the Chapter Admin.";

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
			}

		}
}


?>

<div ng-app="thisapp" ng-controller="regController">
<div class="ng-view">
<div class="container">
<h1>Add Chapter</h1>
<form class="form-horizontal" name="addCh" action="addCh.php" method="post">

<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="chaName">Enter Chapter Name: *</label>
				<div class="col-sm-5">
				<input type="text" class="form-control" maxlength="50" name="chaName" ng-model="chaName"  ng_required="true" placeholder="Enter Chapter Name" value="<?php if (isset($trimmed['chaName'])) echo $trimmed['chaName']; ?>">
				<div ng-messages="addCh.chaName.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addCh.chaName.$error.required">Must Enter a Chapter
						</div>
				</div>	
			</div>
		</div>
<div class="form-group">
	<label class="col-sm-4" style="color: navy;" for="chaAdmin">Chapter Admin Name:*</label>
	<div class="col-sm-5">
					<select class="form-control" maxlength="50" name='chaAdmin'ng-model='chaAdmin' ng-focus="getAdmins()" ng-required="true" > 
						<option value="">Select Chapter Admin</option>
						<option  ng-repeat="x in myData" value="{{x.first_name+' '+ x.last_name}}">{{x.first_name+ ' '+ x.last_name}}</option>
					</select>
					<div ng-messages="addCh.chaAdmin.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addCh.chaAdmin.$error.required">Must select Chapter Admin
						</div>
					</div>
				</div>
</div>	

<div class="form-group">
				<label class="col-sm-4" style="color: navy;" for="chEmail">Chapter Admin Email:*</label>
				<div class="col-sm-5">
					<input type="email" class="form-control" maxlength="60" name="chEmail" ng_model="chEmail" ng_required="true" placeholder="email@server.com" ng-required="true" placeholder="Enter Admin Email" value="<?php if (isset($trimmed['chEmail'])) echo $trimmed['chEmail']; ?>">
					<div ng-messages="addCh.chEmail.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addCh.chEmail.$error.required">Email ID is Required!
						</div>
					</div>
				</div>
			</div>
<div class="form-group">
	<label class="col-sm-4" style="color: navy;" for="chapSponsors">Sponsor Name: *</label>
	<div class="col-sm-5">
		<input type="text" class="form-control" maxlength="50" name="chapSponsors" ng_model="chapSponsors" placeholder="Enter Chapter Sponsors" ng-required="true" >
		<div ng-messages="addCh.chapSponsors.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addCh.chapSponsors.$error.required">Sponsor is Required!
						</div>
					</div>
	</div>
	
</div>	

<div class="form-group">
    <div class="col-sm-offset-4 col-sm-5">
	   <button name="submit" type="submit" class="btn btn-primary" >Add Chapter</button>
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

		$scope.getAdmins = function(){ 
            
            $http.get("retrieveAdmins.php").then(
				function success(response){
				    if (response.data) { 
						$scope.myData = response.data;
					}else {
						$scope.myData=[];
					}
					console.log($scope.myData);
                },  
                function error(response){
			
					$window.alert("Error in getAdmins");
					
                });
		}
	});               
</script>