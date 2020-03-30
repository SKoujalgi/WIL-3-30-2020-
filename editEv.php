<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Add an Event';
include('includes/header.html');

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if($_SERVER['REQUEST_METHOD'] == 'GET'){

	require(MYSQL);

	$id1 = $_GET["id"];
	$q1 = "SELECT event_name, event_belong_to_chapter, event_date, event_time, event_location, event_sponsors from Events where event_no='$id1'";
	$r1= mysqli_query($dbc, $q1); //or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	
	$num = $r1->num_rows;
	if($num == 1){
		list($evName, $evChapter, $evDate, $evTm, $evLocation, $evSponsor) = mysqli_fetch_array($r1, MYSQLI_NUM);
        mysqli_free_result($r1);
	}else{
		trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	}

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

	// Need the database connection:
	require(MYSQL);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	
	//Assume invalid values:
	//$evName = $chBelong = $evDt = $evTime = $evLoc = $evSp = $evAtt = $evDetails = FALSE;

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
				echo '<p class="error">Please enter Event Sponsors!</p>';
			}

		if ($evName && $chBelong && $evDt && $evTime && $evLoc && $evSp) { // If everything's OK...

				$evAtt = 0;
		

			if ($evDetails = FALSE){
				$evDetails = "";
			}
			
			$q = "Update EVENTS set event_name='$evName', event_date='$evDt', event_time='$evTime', event_location='$evLoc', event_sponsors='$evSp', event_attendees='$evAtt', event_details='$evDetails' where event_belong_to_chapter='$chBelong'";
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		
			if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....
				
				$body = "You have successfully added an event. An email will be sent to the Chapter Admin.  \n\n";
				
				//mail('$email', 'Chapter Created!', $body, 'From: admin@WIL.com');

				//yet to work on sending the mails after editing the events.
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
}
mysqli_close($dbc);
?>

<div ng-app="thisapp" ng-controller="regController">
<div class="ng-view">
<div class="container">


<h1>Edit Event</h1>
<form class="form-horizontal" action="editEv.php" method="post">
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveName">Event Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="eveName" placeholder="Enter Event Name">
	</div>
</div>	
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="chaBelong">Chapter Event belongs to: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="chaBelong" placeholder="Enter Chapter Name" value="<?php echo $evChapter;?>">
	</div>
</div>	
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveDate">Event Date: *</label>
	<div class="col-sm-4">
        <input type="text" class="form-control" name="eveDate" placeholder="Format YYYY-MM-DD" value="<?php echo $evDate;?>">
       </div>
	
</div>	

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveTime">Event Time: *</label>
	<div class="col-sm-4">
        <input type="text" class="form-control" name="eveTime" placeholder="Format hh:mm:ss" value="<?php echo $evTm;?>">
       </div>
	
</div>

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveLocation">Event Location: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="eveLocation" placeholder="Enter Event location" value="<?php echo $evLocation;?>">
	</div>
	
</div>

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="eveSponsors">Event Sponsors: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="eveSponsors" placeholder="Enter Event Sponsors" value="<?php echo $evSponsor;?>">
	</div>
	
</div>

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Update Event</button>
	   <button type="reset" class="btn btn-primary" value="Reset">Reset</button>
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
