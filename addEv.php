<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Add an Event';
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
	$evName = $chBelong = $evDt = $evTime = $evLoc = $evSp = FALSE;
	// $evDetails = 

		// Check for a Event name:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['eveName'])) {
			$evName = mysqli_real_escape_string($dbc, $trimmed['eveName']);
		} else {
			echo '<p class="error">Please enter Event Name!</p>';
		}

		// Check for a chapter:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chaBelong'])) {
			$chBelong = mysqli_real_escape_string($dbc, $trimmed['chaBelong']);
		} else {
			echo '<p class="error">Please enter the Chapter it belongs to!</p>';
		}
	

		
		// Check for Event Date: alternate Regex /^(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.]((?:19|20)\d\d)$/
		if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$trimmed['eveDate'])){		
			$evDt = mysqli_real_escape_string($dbc, $trimmed['eveDate']);
		} else {
			echo '<p class="error">Please enter Event Date!</p>';
		}
		
		//Check for a Event Time: (([01]?[0-9]):([0-5][0-9]) ([AaPp][Mm])) OR ((0[0-1]|[1-59]\d):(0[0-1]|[1-12]\d)\s(AM|am|PM|pm))
			if (preg_match('/([0-9]{2}:[0-9]{2}:[0-9]{2})/', $trimmed['eveTime'])) {
				$evTime = mysqli_real_escape_string($dbc, $trimmed['eveTime']);
			} else {
				
				echo '<p class="error">Please enter Event Time!</p>';
			}	
		
		// Check for Event Location:
		if (preg_match('/^[A-Z \'.,-]{2,40}$/i', $trimmed['eveLocation'])) {
			$evLoc = mysqli_real_escape_string($dbc, $trimmed['eveLocation']);
		} else {
			echo '<p class="error">Please enter Event Location!</p>';
		}
	
		// Check for Event Sponsors:
			if (preg_match('/^[A-Z \'.,-]{2,40}$/i', $trimmed['eveSponsors'])) {
				$evSp = mysqli_real_escape_string($dbc, $trimmed['eveSponsors']);
			} else {
				echo '<p class="error">Please enter Event Sponsor!</p>';
			}
		
			// Check for Event Details:
				//if (preg_match('/^[A-Z \'.,-]{2,40}$/i', $trimmed['eveDetails'])) {
					$evDetails = mysqli_real_escape_string($dbc, $trimmed['eveDetails']);
				//} else {
				//	echo '<p class="error">Please enter Event Sponsors!</p>';
				//}

				$q1 = "SELECT * FROM Events where event_name = '$evName'";
				$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));

				if(mysqli_num_rows($r1)== 0){

					if ($evName && $chBelong && $evDt && $evTime && $evLoc && $evSp && $evDetails) { // If everything's OK...

					$q = "INSERT INTO EVENTS (event_name, event_date, event_time, event_location, sponsor_name, chapter_name, event_details) VALUES ('$evName', '$evDt', '$evTime', '$evLoc', '$evSp', '$chBelong', '$evDetails')";
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		
					if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....
				
						$body = "You have successfully added an event. An email will be sent to the Chapter Admin.  \n\n";
				
						mail('$email', 'Event Created!', $body, 'From: admin@WIL.com');
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
					}

				}
				
			}else{

				echo "Event already Exists!!";
			}
		
}

?>

<div ng-app="thisapp" ng-controller="regController">
<div class="ng-view">
<div class="container">
<h1>Add Event</h1>

<form name="addEv" action="addEv.php" class="form-horizontal" method="post">

<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label class="col-sm-3" style="color: navy;" for="eveName">Event Name: *</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" maxlength="50" name="eveName" ng-model="eveName"  ng_required="true" placeholder="Enter Event Name" value="<?php if (isset($trimmed['eveName'])) echo $trimmed['eveName']; ?>">
					<div ng-messages="addEv.eveName.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveName.$error.required">You must enter Event Name.
						</div>
					</div>	
				
				</div>
			</div>	
		
			<div class="form-group">
				<label class="col-sm-3" style="color: navy;" for="chaBelong">Select Chapter: *</label>
				<div class="col-sm-5">
					<select class="form-control" maxlength="50" name='chaBelong'ng-model='chaBelong' ng-focus="getChapters()" ng-required="true" > 
						<option value="">Select a chapter</option>
						<option  ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
					</select>
					
					<div ng-messages="addEv.chaBelong.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.chaBelong.$error.required">Must select a Chapter
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" style="color: navy;" for="eveDate">Event Date: *</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="eveDate" ng-model="eveDate" placeholder="Format YYYY-MM-DD" ng-required="true" >
					<div ng-messages="addEv.eveDate.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveDate.$error.required">You must enter Event Date.
						</div>
					</div>
       		</div>
	
</div>	

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveTime">Event Time: *</label>
	<div class="col-sm-5">
        <input type="text" class="form-control" name="eveTime" ng-model="eveTime" placeholder="Format hh:mm:ss(24-hour clock)" ng-required="true" > 
		<div ng-messages="addEv.eveTime.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveTime.$error.required">You must enter Event Time.
						</div>
					</div>   
	</div>
	
</div>	

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveLocation">Event Location: *</label>
	<div class="col-sm-5">
		<input type="text" class="form-control" maxlength="50" name="eveLocation" ng-model="eveLocation" placeholder="Enter Event location" ng-required="true" >
		<div ng-messages="addEv.eveLocation.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveLocation.$error.required">You must enter Event Location.
						</div>
					</div> 
	</div>
	
</div>

<div class="form-group">
				<label class="col-sm-3" style="color: navy;"  for="eveSponsors">Sponsor Name: *</label>
				<div class="col-sm-5">
					<select class="form-control" maxlength="50" name='eveSponsors'ng-model='eveSponsors' ng-focus="getSponsors()" ng-required="true" > 
						<option value="">Select a Sponsor</option>
						<option  ng-repeat="x in mySponsor" value="{{x.sponsor_name}}">{{x.sponsor_name}}</option>
					</select>
					
					<div ng-messages="addEv.eveSponsors.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveSponsors.$error.required">Must select Event Sponsor
						</div>
					</div>
				</div>
			</div>

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveDetails">Event Details: *</label>
	<div class="col-sm-5">
		<input type="text" class="form-control" maxlength="50" name="eveDetails" ng-model="eveDetails" placeholder="Enter Event Details" ng-required="true" >
		<div ng-messages="addEv.eveDetails.$error" style="color:red" role="alert">        
						<div ng-message="required" ng-if="addEv.eveDetails.$error.required">You must enter Event Details.
						</div>
					</div>
	</div>
	
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Add Event</button>
	   <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
       
    </div>
</div>	

</div>	
</div>
</div>
</form>
</div>
</div>
</div>

<script type="text/javascript">

        var app = angular.module('thisapp', []);

        app.controller('regController', function($scope, $http, $window) {

		/*$scope.getChapters = function(){ 
            
            $http.get("retrieveChapters.php").then(
				function success(response){
				    if (response.data) { 
						$scope.myData = response.data;
					}else {
						$scope.myData=[]; 
					}
					console.log($scope.myData);
				
					//$scope.myData = response.data;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
				});*/
				
		$scope.getChapters = function(){ 
            
            $http.get("retrieveChapters.php").then(
				function success(response){
					
                    $scope.myData = response.data;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
                });
       		 } 
				
		////////////////////////////////////////////////////////////////////////////////////////////////////

		$scope.getSponsors = function(){ 
            
            $http.get("retrieveSponsors.php").then(
				function success(response){
					
                    $scope.mySponsor = response.data;
                }, 
                function error(response){
			
					$window.alert("Error in getSponsors");
					
                });
       	} 
	/*	$scope.getSponsors = function(){ 
            
            $http.get("retrieveSponsors.php").then(
				function success(response){
				    if (response.data) { 
						$scope.myData = response.data;
					}//else {
					//	$scope.myData=[]; //This line is creating an empty array later..... check it out..
					//}
					
					console.log($scope.myData);
				
					//$scope.myData = response.data;
                }, 
				/*function success(response){
					
					$scope.myData = response.data.results;
					//console.log($scope.myData);
					//$scope.myData = response.data;
					
                }, */
             /*   function error(response){
			
					$window.alert("Error in getSponsors");
					
                });
       		 } */
	});               
</script>

<?php include('includes/footer.html'); ?>