<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Edit Sponsor';
include('includes/header.html');
require('includes/usagelog.php');

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if($_SERVER['REQUEST_METHOD'] == 'GET'){

	require(MYSQL);

	$spNo = $_GET["spNo"];
	
	
	$q1 = "SELECT sponsor_no, sponsor_name, chapter_name, event_name from Sponsor where sponsor_no='$spNo'";
	$r1= mysqli_query($dbc, $q1); //or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	
	$num = $r1->num_rows;
	if($num == 1){
		list($spNum, $spName, $spChName, $spEvName) = mysqli_fetch_array($r1, MYSQLI_NUM);
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


	// Assume invalid values:

	$spN = $chN = $evN =  FALSE;

		// Check for a Sponsor name:
			if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['spoName'])) {
				$spN = mysqli_real_escape_string($dbc, $trimmed['spoName']);
			} else {
				echo '<p class="error">Please enter Sponsor name!</p>';
			}

		// Check for a Chapter name:
		if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chapter'])) {
			$chN = mysqli_real_escape_string($dbc, $trimmed['chapter']);
		} else {
			echo '<p class="error">Please select Chapter name!</p>';
		}

		// Check for a Event name:
			if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['eveName'])) {
				$evN = mysqli_real_escape_string($dbc, $trimmed['eveName']);
			} else {
				echo '<p class="error">Please enter Event name!</p>';
			}
			

			if ($spN && $chN && $evN) { // If everything's OK...
				$q = "UPDATE Sponsor SET  sponsor_name='$spN', update_date = NOW() where event_name = '$evN'";
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
				
				if(mysqli_affected_rows($dbc) == 1){
					
					$body = "You have successfully updated Sponsor information.  \n\n";
				
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
mysqli_close($dbc);
?>

<div ng-app="thisapp" ng-controller="regController" ng-init="setSponsor()">
<div class="ng-view">
<div class="container">


<h1>Edit Sponsor</h1>
<form class="form-horizontal" action="editSp.php" method="post">
	
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="spoName">Sponsor Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="spoName" ng-model="sponsor.spoName" ng-required="true" value="<?php if (isset($trimmed['spoName'])) echo $trimmed['spoName']; ?>" placeholder="Sponsor Name">
	</div>
</div>	
<!--
<div class="form-group">
		<label class="col-sm-2" style="color: navy;" for="ForChapter">Chapter Name: *</label>
        <div class="col-sm-4">	
		
		<select class="form-control" name='chapter' ng-model="chapter" ng-change="getChapters()" ng-required="true">
				<option value="">Select a chapter</option>
				<option ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
						 
        </select>
    </div>	
</div> -->

<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="ForChapter">Chapter Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name='chapter' ng-model="sponsor.chapter" ng-required="true" value="<?php if (isset($trimmed['chapter'])) echo $trimmed['chapter']; ?>" placeholder="Chapter Name" readonly>
	</div>
	
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="eveName">Event Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="eveName" ng-model="sponsor.eveName" ng-required="true" value="<?php if (isset($trimmed['eveName'])) echo $trimmed['eveName']; ?>" placeholder="Event Name" readonly>
	</div>
	
</div>	

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Edit Sponsor</button>
	   <button type="reset" class="btn btn-primary"  >Reset</button>
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
					
                    $scope.myData = response.data.results;
                }, 
                function error(response){
			
					$window.alert("Error in getChapters");
					
                });
       	} 
		
	
		$scope.setSponsor = function() { 

            $scope.sponsor={   
				spoName:"<?php echo $spName; ?>",
                chapter:"<?php echo $spChName; ?>",
                eveName:"<?php echo $spEvName; ?>"
            };  
            $scope.chapter= "<?php echo  $spChName;?>";  
            $scope.getChapters();
        };     


				
	});               
</script>