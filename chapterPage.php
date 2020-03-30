<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'WIL-Chapter';
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
    <div>
    <section class="events FullWidth">
        <div class="eventHeader">
            <span class= "eventName">
                <h1>List of all Upcoming events in <?php echo $chapter; ?>!!</h1>
    </section>
  </div>

  
  <div>
    <section class="events FullWidth">
        <div class="eventHeader">
        
        
<?php

$date =date('Y-m-d');

$q = "SELECT * FROM Events where chapter_name='$chapter'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


if (mysqli_num_rows($r)>0) {
    //echo'There are '.mysqli_num_rows($r).' events coming up';
    echo'<div class="eventHeader">
	    <table class="table1" width="100%">
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
        $chapter = $row["chapter_name"];
        $id = $row["event_no"];
        $date1 = $row["event_date"];

        if($date < $date1){
        echo'
		<tr>
			<td>';echo $row["event_name"]; echo'</td>
			<td>';echo $chapter; echo'</td>
			<td>';echo $row["event_location"]; echo'</td>
			<td>';echo $date1; echo'</td>
			<td>';echo $row["event_time"]; echo'</td>
            <td>';echo $row["sponsor_name"]; echo'</td>
            <td><a href="details.php?id='.$id.'">click here</a></td>
           
		</tr>';
		
        }
    }
		echo '</table></div>';  

}else{
    echo'<h2>There are no upcoming events in this Chapter!!<h2>';
}

?>
    </section>
  </div>
 
  </div>
</div>
<br>
<br>
<div class="col-sm-offset-11 col-sm-1">
	<button name="submit" type="submit" class="btn btn-primary" >Back</button>
</div>

</form>
<?php include('includes/footer.html'); ?>
