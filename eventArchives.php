<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'WIL - DC Chapter';
include('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}
$id = $_GET["id"];
if($_GET["id"] == 1){
    $chapter = "New York";
}elseif($_GET["id"] == 2){
    $chapter = "Washington DC";
}elseif($_GET["id"] == 3){
    $chapter = "Boston";
}elseif($_GET["id"] == 4){
    $chapter = "San Fransisco";
}elseif($_GET["id"] == 5){
    $chapter = "Texas";
}elseif($_GET["id"] == 6){
    $chapter = "Georgia";
}

?>
<form action="index.php" class="form-horizontal">
<body style="font-family:Verdana;">

<div style="overflow:auto">
  <div class="menu">
  <?php
  echo'
  <a href="eventArchives.php?id='.$id.'">Event Archives</a>
  <a href="memberComments.php?id='.$id.'">Member Comments</a>
  <a href="jobPostings.php?id='.$id.'">Job Postings</a>
  ';
  ?>
  </div>


<div class="main">
    <section class="events FullWidth">
        <div class="eventHeader">
        <h1>Event Archives!</h1>
            <span class= "eventName">
           
    
                <label class="col-sm-6" style="color: navy;" for="Chyear">Choose Year: *</label>
                <div class="col-sm-6">
                    <select class="form-control" name='year'ng-model='year' ng-change="getYear()"  ng-required="true" > 
                         <option value="">Select a Year</option>
                        <option  ng-repeat="x in myData" value="{{x.event_date}}">{{x.event_date}}</option>
                    </select>
                </div>
                </section>
</div>
</div>


<?php
    include('includes/footer.html');
?>

<script type="text/javascript">

        var app = angular.module('thisapp', []);

        app.controller('regController', function($scope, $http, $window) {

        $scope.getYear = function(){ 
            
            $http.get("retrieveDate.php").then(
                function success(response){
                    
                    $scope.myData = response.data.results;
                }, 
                function error(response){
            
                    $window.alert("Error in getDate");
                    
                });
             } 
    });               
</script>
        

