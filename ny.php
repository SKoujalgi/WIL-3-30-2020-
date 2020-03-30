<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'WIL - NY Chapter';
include('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}
?>



<!-- ************************** ADDED FOR SIDEBAR   ********************* -->
<style>
* {
  box-sizing: border-box;
}
.menu {
  float:left;
  width:20%;
  text-align:center;
}
.menu a {
  background-color:#01adc4;
  padding:8px;
  margin-top:7px;
  display:block;
  width:100%;
  color:black;
}
.main {
  float:left;
  width:60%;
  padding:0 20px;
}
.right {
  background-color:#e5e5e5;
  float:left;
  width:20%;
  padding:15px;
  margin-top:7px;
  text-align:center;
}

@media only screen and (max-width:620px) {
  /* For mobile phones: */
  .menu, .main, .right {
    width:100%;
  }
}
</style>



<body style="font-family:Verdana;">

<div style="overflow:auto">
  <div class="menu">
  <a href="eventArchivesNY.php">Event Archives</a>
  <a href="memberCommNY.php">Member Comments</a>
  <a href="jobPostingsNY.php">Job Postings</a>
  </div>

    <div class="main">
    <div>
    <section class="events FullWidth">
        <div class="eventHeader">
            <span class= "eventName">
                <h4>List of all Upcoming events in New York!!</h4>
    </section>
  </div>

  <!-- This For-loop is for printing all the events in NY Chapter ----->
  <div>
    <section class="events FullWidth">
        <div class="eventHeader">
        <?php


$q = "SELECT * FROM Events where event_belong_to_chapter= 'New York' ";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));



if (mysqli_num_rows($r)>0) {
    
    echo'<div class="eventHeader">
	<table>
		<tr>
		  <th>Event Name</th>
		  <th>Chapter</th>
		  <th>Location</th>
		  <th>Date</th>
		  <th>Time</th>
          <th>Sponsor</th>
          <th>Details</th>
          
		</tr>';
    while($row = mysqli_fetch_assoc($r)){
       $eventName = $row["event_name"];
       $chapter= $row["event_belong_to_chapter"];
        echo'
		<tr>
			<td>';echo $eventName; echo'</td>
			<td>';echo $chapter; echo'</td>
			<td>';echo $row["event_location"]; echo'</td>
			<td>';echo $row["event_date"]; echo'</td>
			<td>';echo $row["event_time"]; echo'</td>
            <td>';echo $row["event_sponsors"]; echo'</td>
            <td><a href="details.php?chapter='.$chapter.'">click here</a></td>
           
		</tr>';
		
		}
		echo '</table></div>';  

}

?>
    </section>
  </div>
 
  </div>
</div>



<?php include('includes/footer.html'); ?>
