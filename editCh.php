<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Edit Chapter';
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

	$ch = $_GET["ch"];
	
	$q1 = "SELECT chapter_name, chapter_admin, sponsors_name from Chapter where chapter_no='$ch'";
	$r1= mysqli_query($dbc, $q1); //or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	
	$num = $r1->num_rows;
	if($num == 1){
		list($chName, $chAdmin, $sponsor) = mysqli_fetch_array($r1, MYSQLI_NUM);
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
$chN = $chAdF = $chSp = FALSE;

	// Check for a Chapter name:
	if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chapterName'])) {
		$chN = mysqli_real_escape_string($dbc, $trimmed['chapterName']);
	} else {
		echo '<p class="error">Please enter chapter_name!</p>';
	}

	// Check for a Admin first name:
	if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['chaFirAdmin'])) {
		$chAdF = mysqli_real_escape_string($dbc, $trimmed['chaFirAdmin']);
	} else {
		echo '<p class="error">Please enter Admin first name!</p>';
	}


	// Check for Chapter Sponsors:
	if (preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['chapSponsor'])) {
		$chSp = mysqli_real_escape_string($dbc, $trimmed['chapSponsor']);
	} else {
		echo '<p class="error">Please enter Chapter Sponsors!</p>';
	}

	if ($chN && $chAdF && $chSp) { // If everything's OK...

		$q = "UPDATE Chapter set sponsors_name = '$chSp', ch_update_date =  NOW() where chapter_name='$chN'";
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

		//$q1 = "SELECT email from users where (first_name='$chAdF' AND last_name='$chAdL')";
		//$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
		//$email = $r1;
		if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....
		
			$body = "You have successfully updated the Chapter information.  \n\n";
			
			//mail('$email', 'Chapter Created!', $body, 'From: admin@WIL.com');
			echo $body;

			echo '<form action="add_edit_view_chapter.php">';
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
<div ng-app="thisapp" ng-controller="regController" ng-init="setChapter()">
<div class="ng-view">
<div class="container">

<h1>Edit Chapter</h1>
<form class="form-horizontal" action="editCh.php" method="post">
<!--
<div class="form-group">
		<label class="col-sm-2" style="color: navy;" for="ForChapter">Chapter Name: *</label>
        <div class="col-sm-4">	
		
		<select class="form-control" name='chapterName' ng-model="chapterName" ng-change="getChapters()" ng-required="true">
				<option value="">Select a chapter</option>
				<option ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
						 
        </select>
    </div>	
</div> -->

<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="ForChapter">Chapter Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name='chapterName' ng-model="chapter.chapterName" ng-required="true" value="<?php if (isset($trimmed['chapter'])) echo $trimmed['chapter']; ?>" placeholder="Chapter Name" readonly>
	</div>
	
</div>

<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="chaFirAdmin">Admin Name: *</label>
	<div class="col-sm-3">
		<input type="text" class="form-control" name="chaFirAdmin" ng-model="chapter.chaFirAdmin" ng-required="true" value="<?php if (isset($trimmed['chaFirAdmin'])) echo $trimmed['chaFirAdmin']; ?>" placeholder="Admin Name" readonly>
	</div>
</div>	
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="chapSponsor">Chapter Sponsors: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="chapSponsor" ng-model="chapter.chapSponsor" ng-required="true" value="<?php if (isset($trimmed['chapSponsor'])) echo $trimmed['chapSponsor']; ?>" placeholder="Sponsor Name">
	</div>
	
</div>	

<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
	   <button name="submit" type="submit" class="btn btn-primary" >Update Chapter</button>
	   <button type="reset" class="btn btn-primary"  >Reset</button>
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



		$scope.setChapter = function() { 

            $scope.chapter={   
				
                chapterName:	"<?php echo $chName; ?>",
				chaFirAdmin:"<?php echo $chAdmin; ?>",
                chapSponsor:"<?php echo $sponsor; ?>"
            };  
            $scope.chapterName= "<?php echo $chName;?>";  
            $scope.getChapters();
        };      

	});               
</script>