<?php 

require('includes/config.inc.php');
$page_title = 'WIL | Deatiled Events';
include('includes/header.html');
require('includes/usagelog.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}

// If no user_id session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])&&!isset($_SESSION['event_id']) ) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.

}
$id = $_GET["id"];
$eventNo = $_GET["eventid"];

//echo $id;
//echo  $eventNo;


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

<h1>Welcome to <?php echo $chapter; ?> WIL - a Network of Women in the Field of Intellectual Property Protection and Licensing!!</h1>


<h2><b>
Upcoming  <?php echo $chapter; ?> Event</b>
</h2>


<br>

<?php

$q = "SELECT * FROM Events where chapter_name= '$chapter' && event_no ='$eventNo'";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {
    
    
    while($row = mysqli_fetch_assoc($r)){
	   
		echo'<div class="eventHeader">
				<table align ="center" width="60%">
					';
        echo'
					<tr>
						<td style="width:250px"><b>';echo $row["chapter_name"];echo' Event Details</b></td>
						<td>';echo $row["event_name"]; echo'</td>
					</tr>
					<tr>
						<td>';echo'<b>When</b> </td>
						<td>';echo $row["event_date"]; echo'</td>
					</tr>
					<tr>
						<td>';echo'<b>Where</b> </td>
						<td>';echo $row["event_location"]; echo'</td>
					</tr>
					<tr>
						<td>';echo'<b>Time</b> </td>
						<td>';echo $row["event_time"]; echo'</td>
					</tr>
					<tr>
						<td>';echo'<b>Sponsors</b> </td>
          			    <td>';echo $row["sponsor_name"]; echo'</td>
					</tr>
					<tr>
						<td>';echo'<b>Details</b> </td>
					  <td>';echo $row["event_details"]; echo'</td>
				</tr>';
					
		}
		echo '</table></div>';  

}
echo'<section>
	<div align= "center" class="eventHeader">
	
	<a href="rsvp.php?id='.$id.'&eventid='.$eventNo.'"?>Click here to RSVP</a>

	</div>
	
</section>';
?>
<br>

<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='add_edit_view_events.php';" class="btn btn-primary" >Back</button>
</div>

<?php

$q = "SELECT user_id FROM RSVP WHERE event_no = '$eventNo' && (response=1 || response=3)";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {
	
	echo '<h2> The following members are likely to attend this event!</h2>';
	echo '<div>
				 
				<table class="table1">
			  <tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Organization</th>
				
			  </tr>';
    while($row = mysqli_fetch_assoc($r)){
		
		$uid = $row["user_id"];
		//echo $uid;
		$q1 = "SELECT first_name, last_name , co_name FROM users WHERE user_id=$uid";
		$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));


		if(mysqli_num_rows($r1)>0){

			

		while($row = mysqli_fetch_assoc($r1)){
			$fName = $row["first_name"];
			$lName = $row["last_name"];
			$Org = $row["co_name"];
			
		
			echo'
				<tr>
					<td>';echo $fName; echo'</td>
					<td>';echo $lName; echo'</td>
					<td>';echo $Org; echo'</td>
				   
				</tr>';
		
			}
			
		
		
		}
		
	}
}
echo'
	</table>
	</div>
	<br>';
?>




<?php
include('includes/footer.html');
?>