<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Add Sponsor';
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
	$spN = $chN = $evN =  FALSE;

		// Check for a Sponsor name:
			if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['spoName'])) {
				$spN = mysqli_real_escape_string($dbc, $trimmed['spoName']);
			} else {
				echo '<p class="error">Please enter Sponsor name!</p>';
			}

		// Check for a Chapter name:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chaBelong'])) {
			$chN = mysqli_real_escape_string($dbc, $trimmed['chaBelong']);
		} else {
			echo '<p class="error">Please enter Chapter name!</p>';
		}

		// Check for a Event name:
			if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['eveName'])) {
				$evN = mysqli_real_escape_string($dbc, $trimmed['eveName']);
			} else {
				echo '<p class="error">Please enter Event name!</p>';
			}


			if ($spN && $chN && $evN) { // If everything's OK...
				$q = "INSERT INTO Sponsor (sponsor_name, event_name, chapter_name,  create_date, update_date) VALUES ('$spN', '$evN', '$chN', NOW(), NOW())";
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
				
				if(mysqli_affected_rows($dbc) == 1){
					
					$body = "You have successfully added a Sponsor to WIL.  \n\n";
				
				//mail('$email', 'Chapter Created!', $body, 'From: admin@WIL.com');
				echo $body;
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


<h1>Add Sponsor</h1>

<form name="addSp" class="form-horizontal" action="addSp.php" method="post">
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="spoName">Sponsor Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" maxlength="50" name="spoName" ng-model="spoName" placeholder="Enter Sponsor Name" ng-required="true" >
		<div ng-messages="addSp.spoName.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addSp.spoName.$error.required">You must enter Sponsor Name.
						</div>
					</div>
	</div>
</div>	
<div class="form-group">
				<label class="col-sm-2" style="color: navy;" for="chaBelong">Select Chapter: *</label>
				<div class="col-sm-4">
					<select class="form-control" maxlength="50" name='chaBelong'ng-model='chaBelong' ng-focus="getChapters()" ng-required="true" > 
						<option value="">Select a chapter</option>
						<option  ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
					</select>
					
					<div ng-messages="addSp.chaBelong.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addSp.chaBelong.$error.required">Must select a Chapter
						</div>
					</div>
				</div>
			</div>
			
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="eveName">Event Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" maxlength="50" name="eveName" ng-model="eveName" placeholder="Enter Event Name" ng-required="true" >
		<div ng-messages="addSp.eveName.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addSp.eveName.$error.required">You must enter Event Name.
						</div>
					</div>
	</div>
	
</div>	

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Add Sponsor</button>
	   <button type="reset" class="btn btn-primary" value="Reset">Clear</button>

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
					
                    $scope.myData = response.data;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
                });
       		 } 
	});               
</script>
