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

?>

<div ng-app="thisapp" ng-controller="regController" ng-init="query_organization()">
<div class="ng-view">
<div class="container-fluid">
<div id="StatusMessage"></div>
<h1>Add Users</h1>
<h3><div id="Loading"></div></h3>
<form class="form-horizontal" method="post">
<!--
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="provider_code">Organization Code:</label>
	<div class="col-sm-2">
        <select name="orgcode" ng-model="orgcode" class="form-control" ng-change="query_organization()" ng-required="true">
            <option value="">Select an organization</option>    
            <option ng-repeat="org in organizations" value="{{org.org_code}}">{{org.org_code}}</option>
        </select>
	</div>
</div> 
-->

<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="first_name">First Name:</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="first_name" size="20" maxlength="30" ng-model="user.first_name" ng-required="true" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" placeholder="First Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="last_name">Last Name:</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="last_name" size="20" maxlength="20" ng-model="user.last_name" ng-required="true" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" placeholder="Last Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="email">Email Address:</label>
	<div class="col-sm-4">
		<input type="email" class="form-control" name="email" id="email" size="30" maxlength="60" ng-model="user.email" ng-required="true" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" placeholder="Enter your Email id">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="org_name">Company Name:</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="org_name" size="40" maxlength="40" ng-model="details.org_name" ng-required="true" placeholder="Organizations Name">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="org_name">Role:</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="org_name" size="40" maxlength="40" ng-model="details.org_name" ng-required="true" placeholder="Organizations Name">
	</div>
</div>
<div class="form-group">
		<label class="col-sm-2" style="color: navy;" for="chapter">Select Chapter:</label>
        <div class="col-sm-4">	
			<select class="form-control" name='chapter' id='chapter'>
                <option value="default_chapter">Choose Your Chapter</option>
				<option value='New York'>New York</option>	
				<option value='Washington DC'>Washington DC</option>
				<option value='San Fransisco'>San Fransisco</option>
			</select>
    </div>	
</div>
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="password1">Password:</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="password1" size="20" ng-model="user.password1" ng-required="true" value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" placeholder="Select a password at least 10 characters long">
	</div>
	<span style="color: white;" class="help-block">Password must be at least 10 characters long</span>
</div>	
<div class="form-group">
	<label class="col-sm-2" style="color: navy;" for="password2">Confirm Password:</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="password2" size="20" ng-model="user.password2" ng-required="true" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" placeholder="Must match password">
	</div>
	<span style="color: white;" class="help-block">Password must be at least 10 characters long</span>
</div>
<div class="form-group">
<!--	<label class="col-sm-2" style="color: navy;" for="org_id">Organization Id:</label> -->
	<div class="col-sm-4">
		<input type="text" class="form-control" name="org_id" size="15" maxlength="15" ng-model="details.org_id" ng-required="true" ng-hide="true" placeholder="Organizations Id">
	</div>
</div>
<div class="form-group">
<!--	<label class="col-sm-2" style="color: navy;" for="user_org_id">Organization Id:</label> -->
	<div class="col-sm-4">
		<input type="text" class="form-control" name="user_org_id" id="user_org_id" size="15" maxlength="15" ng-model="user.org_id" ng-required="true" ng-hide="true" placeholder="User Org Id">
	</div>
</div>
<div class="form-group">
<!--	<label class="col-sm-2" style="color: navy;" for="user_org_type">Organization Type:</label> -->
	<div class="col-sm-4">
		<input type="text" class="form-control" name="user_org_type" id="user_org_type" size="20" maxlength="20" ng-model="user.org_type" ng-required="true" ng-hide="true" placeholder="User Org Type">
	</div>
</div>
<div class="form-group">
<!--	<label class="col-sm-2" style="color: navy;" for="user_org_name">Organization Name:</label> -->
	<div class="col-sm-4">
		<input type="text" class="form-control" name="user_org_name" id="user_org_name" size="40" maxlength="40" ng-model="user.org_name" ng-required="true" ng-hide="true" placeholder="User Org Name">
	</div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
	   <button type="button" class="btn btn-primary" value="submit" ng-click="submit()" >Add User</button>
	   <button type="button" class="btn btn-primary" value="refresh" ng-click="refresh()" >Clear</button>
    </div>
</div>	
</form>
</div>
</div>
</div>
<script type="text/javascript ">
    var app = angular.module('thisapp', []);
    app.controller('regController', function($scope, $http, $window) {
        $scope.query_organization = function() {
                if (typeof $scope.orgcode === 'undefined') {
                   $scope.orgcode='';    
                } 
                $scope.startLoader();
                data='{"org_code":"'+$scope.orgcode+'","message_type":"Organizations","message_subtype":"Query"}';
                $http.post("route.php", data).then(function(response) {
                $scope.stopLoader();
                if (response.data.status=="Success") {
                    $scope.organizations = response.data.message;
                    if ($scope.organizations.length > 0) {
                        $scope.details = $scope.organizations[0];
                        $scope.orgcode=$scope.details.org_code;
                        $scope.reset();
                    } else {
                        $scope.reset();
                    }
                } else {
                    $scope.displayStatus(response.data.message,'Y');
                }
            }, 
            function(response) {
                $scope.stopLoader();
                $scope.msg = response.data;
                $scope.status = response.status;
                $scope.statusmsg = response.statusText;
                $scope.headers = response.headers();
                $scope.displayStatus("There was an error processing your request. EEEEEEError: " + $scope.statusmsg + " " + $scope.msg[0].error_message,'Y');
            });
        }
        $scope.submit =
            function() {
                object = JSON.stringify($scope.user);
                data='{"message_type":"Users","message_subtype":"Add","object":'+object+'}';                
                $scope.startLoader();
                $http.post("add_users_be.php", data).then(function(response) {
                    $scope.stopLoader();
                    if (response.data.status=="Success") {
                        $scope.displayStatus(response.data.message,'N');
                    } else {
                        $scope.displayStatus(response.data.message,'Y');
                    }
                },
                function(response) {
                    $scope.stopLoader();
                    $scope.displayStatus("There was an error processing your request.",'Y');
                });
        }
		$scope.reset = function() { 
 //           $scope.userid=""; 
            $scope.user={	user_id :0,
                            first_name:"",
                            last_name:"",
                            email:"",
                            password1:"", 
							password2:"",
                            org_id:$scope.details.org_id,
                            org_type:$scope.details.type,
                            org_name:$scope.details.org_name,
                            user_level:"0",
							active:"",
							registration_date:Date(),
                            invalid_login_count:"0",
							force_pass_change:"Y",
                            locked:"N",
                            agreement_accepted: "N",
                            pass_change_date: Date(),
                            update_date:Date()};  
//            $scope.query_organization();
        }
<?php include('includes/jscommon.html') ?>
    });
</script>

<?php include('includes/footer.html'); ?>