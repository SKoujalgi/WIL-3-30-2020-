<?php # Script 18.6 - add_users.php
// This is the page where you can add new users.
require('includes/config.inc.php');
$page_title = 'WIL | User Agreement';
include('includes/header.html');

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
?>

<div ng-app="thisapp" ng-controller="regController">
	<div class="ng-view">
		<div class="container-fluid">
			<h1>Review & Accept the User Agreement</h1>
			<h3><div id="Loading"></div></h3>
			<?php 
			  $doc_dir=BASE_URL.DOCUMENTS_SUBDIR;
			 echo '
			<embed src="'.$doc_dir.'user_agreement" type="application/pdf"   height="500px" width="100%" class="responsive">';
			?>
			<form name="acceptagreement" class="form-horizontal"  method="POST">
				<div class="form-group">
					<div class="checkbox-inline">
						<label><input type="checkbox" name="agreement" ng-model="agreement" value="Y">
						I have read the user agreement and my continued use of this site is subject to the terms of the user agreement.</label>
						<button type="button" class="btn btn-primary" ng-click="submit()" ng-disabled="isButtonDisabled()">Agree</button>
					</div>
				</div>	
			</form>
		</div>
	</div>
</div>
<script type="text/javascript ">
    var app = angular.module('thisapp', []);
    app.controller('regController', function($scope, $http, $window) {
		$scope.submit = function() {
            data='{"message_type":"User","message_subtype":"Sign","agreement":'+$scope.agreement+'}';                
            $scope.startLoader();
            $http.post("updateagree.php", data).then(function(response) {
                status = response.data.status;
				message= response.data.message;
				if (status == "Error") {
                	$window.alert(status+': '+message);
				}
				//once accepted agreement, forward the user to Home page
				window.location="index.php"
            },
            function(response) {
                $scope.stopLoader();
                $window.alert(response.message+" The user agreement could not be signed.");
            });
        }			
		$scope.isButtonDisabled = 
            function() {
                return ($scope.isValid($scope.agreement));  
            }
        $scope.isValid = 
            function(value) {
                return !value;
            }
		$scope.startLoader = function() {
            document.getElementById("Loading").innerHTML = '<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>';
        }
        $scope.stopLoader = function() {
            document.getElementById("Loading").innerHTML = "";
        }
	});
</script>
</div>
</div>
<?php include('includes/footer.html'); ?>