<?php # Script 16.3 - view_users.php #6
// This script retrieves all the records from the users table.
require('includes/config.inc.php');
$page_title = 'WIL | Events';
include('includes/header.html');
require('includes/usagelog.php');

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}
?>
<form  class="form-horizontal" method="get" action="addEv.php">


<?php
	// Display links based upon the login status:
	if (isset($_SESSION['user_id'])) {
	// Add links if the user is an administrator:
			if ($_SESSION['user_level'] >=4) {
				echo'
<p align="right">
		<a href="addEv.php">
          <span class="glyphicon glyphicon-plus-sign">Add</span>
        </a>
</p>';
	}
}
?>

<h2>
<b>WIL Events</b>
</h2>

<p>Choose and RSVP to events happening in your area. Feel free to review other chapters as well. As a member you're invited to attend all events. Simply RSVP to an upcoming event to learn more about it.

</p>


<?php
$date = date('Y-m-d');
$q = "SELECT Events.event_name, Events.event_no, Events.chapter_name, Events.event_location, Events.event_date, Events.event_time, Chapter.chapter_no 
FROM (Events INNER JOIN Chapter ON Events.chapter_name = Chapter.chapter_name);";
/*$q = "SELECT * FROM Events";*/
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {

	echo'
	<table class="table1" width="100%">
		<tr>
		
		  <th>Event Name</th>
		  <th>Chapter</th>
		  <th>Location</th>
		  <th>Date</th>
		  <th>Time</th>
		  <th>Details</th>
		  <th>RSVP</th>
		</tr>';

    while($row = mysqli_fetch_assoc($r)){
		$date1 = $row["event_date"];
		$eventBelong = $row["chapter_name"];
		
		
		
		/*
		SELECT Events.event_name, Events.chapter_name, Events.event_location, Events.event_date, Events.event_time, Chapter.chapter_no 
		FROM (Events INNER JOIN Chapter ON Events.chapter_name = Chapter.chapter_name)
		$q1 = "SELECT chapter_no from Chapter where chapter_name == '$eventBelong'";
		$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));

		$id = $row["chapter_no"];*/



		$eid = $row["event_no"];
		$id =$row["chapter_no"];
		
        if($date < $date1){
			
		echo'
		<tr>
		
			<td>';echo $row["event_name"]; echo'</td>
			<td>';echo $eventBelong; echo'</td>
			<td>';echo $row["event_location"]; echo'</td>
			<td>';echo $row["event_date"]; echo'</td>
			<td>';echo $row["event_time"]; echo'</td>
            <td><a href="details.php?id='.$id.'&eventid='.$eid.'">details</a></td>
			<td><a href="rsvp.php?id='.$id.'&eventid='.$eid.'">RSVP</a></td>
		</tr>';
		
		}
	}
		echo '</table>';
}

?>


</form>
    <?php						
	// Display links based upon the login status:
	if (!isset($_SESSION['user_id'])) {
              
//        echo '<p>If you are a registered user of this site, you can login here:'; 
        echo '<form action="login.php">';
//        echo '<input type="submit" class="btn btn-primary" value="Login">';
        echo '<div class="form-group">
        <label class="col-sm-4" style="color: navy;" for="org_name">If you are a registered user, you can login here:</label>
        <div class="col-sm-2">
            <input type="submit" class="btn btn-primary" name="login" value="Login">
        </div>
    </div>';        
    echo '</form>';
    echo '</p>';
    }
	?> 
	<br>

	<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='index.php';" class="btn btn-primary" >Back</button>
	</div>

<?php
include('includes/footer.html');
?>