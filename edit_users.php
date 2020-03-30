<?php # Script 18.6 - add_users.php
// This is the page where you can add new users.
require('includes/config.inc.php');
$page_title = 'WIL | Edit User';
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

	$em = $_GET["email"];

	$q1 = "SELECT user_id, first_name, last_name, email, work_phone, co_name, u_role, chapter, user_level from users where email='$em'";
	$r1= mysqli_query($dbc, $q1); //or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	
	$num = $r1->num_rows;
	if($num == 1){
		list($userId, $first_name, $last_name, $email, $work_phone, $co_name, $user_role, $chapter, $user_level) = mysqli_fetch_array($r1, MYSQLI_NUM);
        mysqli_free_result($r1);
	}else{
		trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
	}

}



if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	// Need the database connection:
	require(MYSQL);

	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $cn = $ur = $ch = $ul = FALSE;

	// Check for a first name:x
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
			if (($trimmed['chapter']!="") && ($trimmed['chapter'] !="default_chapter")){
				$ch = mysqli_real_escape_string($dbc, $trimmed['chapter']);
			} else {
				echo '<p class="error">Please choose which chapter you would like to belong!</p>';
			} 
		// Check for User Level:
			if ($trimmed['u_level']!= "") {
				$ul = mysqli_real_escape_string($dbc, $trimmed['u_level']);
			} else {
				echo '<p class="error">Please enter your role!</p>';
			} 	

			if ($fn && $ln && $e && $cn && $ur && $ch && $ul) { // If everything's OK...
		
					// Create the activation code:
					$a = md5(uniqid(rand(), true));
					$email = urlencode($e);
		
					// Add the user to the database:
					$q = "UPDATE users SET first_name = '$fn', last_name ='$ln', work_phone = '$wp', co_name = '$cn', u_role = '$ur', chapter = '$ch', update_date = NOW(), user_level = '$ul' where email='$e'";
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
		
					if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
						$link_address = BASE_URL . 'activate.php?x=' . $e . "&y=$a";
						// Send the email:
						$body = "Thank you for editing User Information  \n\n";
						
						mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@WIL.com');
		
						// ------------This is the Mail for OverAll-Site-Admin/Admin to Approve the User Account----------------
						//$link_address1 = BASE_URL . 'approve.php?x=' . $e;
						//$body1 = "This user had been registered. To approve this account, please click on the link:  \n\n";
						//$body1 .= '<a href="'.$link_address1.'">Approve account</a>';
						//mail($trimmed['skoujalgi@exultancy.com'], 'Waiting for Approval', $body1, 'From: admin@WIL.com');
		
		
						// Finish the page:
						
						// I have added this code to see the activation link on the page.
						echo $body;
		
						echo '<form action="add_edit_view_members.php">';
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
		
			}

}
mysqli_close($dbc);
?>


	
<div ng-app="thisapp" ng-controller="regController" ng-init="setUser()">
<div class="ng-view">
<div class="container">

<h1>Edit Users</h1>
<form class="form-horizontal" action="edit_users.php" method="post">
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="first_name">First Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="first_name" maxlength="40" ng-model="user.first_name" ng-required="true" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" placeholder="First Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="last_name">Last Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="last_name" maxlength="40" ng-model="user.last_name" ng-required="true" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" placeholder="Last Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="email">Email Address: *</label>
	<div class="col-sm-4">
		<input type="email" class="form-control" name="email" id="email" maxlength="60" ng-model="user.email" ng-required="true" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" placeholder="Email Address" readonly>
	</div>
</div>


<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="work_phone">Work Phone: </label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="work_phone" id="work_phone" maxlength="15"  ng-model="user.work_phone" ng-required="true" value="<?php if (isset($trimmed['work_phone'])) echo $trimmed['work_phone']; ?>" placeholder="Work Phone">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="co_name">Company Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="co_name" maxlength="20" ng-model="user.co_name" ng-required="true" value="<?php if (isset($trimmed['co_name'])) echo $trimmed['co_name']; ?>" placeholder="Company Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="u_role">Role: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="u_role" maxlength="20" ng-model="user.u_role" ng-required="true" value="<?php if (isset($trimmed['u_role'])) echo $trimmed['u_role']; ?>" placeholder="User Role">
	</div>
</div>


<!--
<div class="form-group">
		<label class="col-sm-2" style="color: navy;" for="ForChapter">Select Chapter: *</label>
        <div class="col-sm-4">	
		
		<select name='chapter' ng-model="chapter" class="form-control" ng-change="getChapters()" ng-required="true">
				<option value="">Select a chapter</option>
				<option ng-repeat="x in myData" value="{{x.chapter_name}}">{{x.chapter_name}}</option>
						 
        </select>
    </div>	
</div> -->
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="ForChapter">Chapter Name: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name='chapter' ng-model="user.chapter" ng-required="true" value="<?php if (isset($trimmed['chapter'])) echo $trimmed['chapter']; ?>" placeholder="Chapter Name" readonly>
	</div>
	
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="u_level">User Level: *</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="u_level" maxlength="1" ng-model="user.u_level" ng-required="true" value="<?php if (isset($trimmed['u_level'])) echo $trimmed['u_level']; ?>" placeholder="User Level">
	</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
		<button name="submit" type="submit" class="btn btn-primary" >Change User</button>
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
		$scope.setUser = function() { 

            	$scope.user={  

                            first_name:"<?php echo $first_name;?>",
                            last_name:"<?php echo $last_name;?>",
                            email:"<?php echo $email;?>",
                            work_phone:"<?php echo $work_phone; ?>",
                            co_name:"<?php echo $co_name; ?>",
                            u_role :"<?php echo $user_role; ?>",
                            chapter:"<?php echo $chapter; ?>",
                            u_level:"<?php echo $user_level; ?>"
                            };  
						
                        $scope.chapter="<?php echo $chapter; ?>";  
                        $scope.getChapters();
        };     

		
	});     
     


</script>