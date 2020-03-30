<?php # Script 16.3 - view_users.php #6
// This script retrieves all the records from the users table.
require('includes/config.inc.php');
$page_title = 'WIL | RSVP';
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
}

$eventNo = $_GET["evNo"];
$userId = $_GET["uid"];
$res = $_GET["res"];


?>

<form method="GET">

<h1><b>RSVP DONE</b></h1>

</form>
<?php

if($res ==1 ){
	$ans = 'Yes';
}elseif($res ==2 ){
	$ans = 'No';
}elseif($res ==3 ){
	$ans = 'MayBe';
}
/*-------------------------------------------------------------------------------------------------*/

// Entering code to validate the database whether the reponse with same User Id and Event No exists

$query = "SELECT user_id, response, event_no FROM RSVP WHERE  user_id = $userId && event_no = $eventNo";
$result = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));

if(mysqli_num_rows($result) > 0 ){
	
	$q1 = "UPDATE RSVP set response=$res where user_id=$userId && event_no= $eventNo";
	$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));

	echo' You have already responded  to this event - ' . $ans. '';
	echo'<br>';echo'<br>';
}else{

		$q1 = "INSERT into RSVP (event_no, user_id, response) VALUES ($eventNo, $userId, $res)";
		$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));

		echo'Thank you for your RSVP!!';
}
/*--------------------------------------------------------------------------------------------------*/

// update RSVP table with the response Yes OR No from the user on the event.
// Storing Responses as Integers 1 = Yes, 2= No and 3 = Maybe.

// The code below is to check if the user RSVP is Yes, then show other members attending the event and add event to the Calender.

if(($res ==1 )||($res ==3 )){
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

// These below lines are for adding the Calender button 


	///these all variable can be taken from the Events table.
	$query = "SELECT event_name, event_date, event_time, event_details FROM EVENTS where event_no= '$eventNo'";
	$r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));

	if (mysqli_num_rows($r)>0) {
    
    
		while($row = mysqli_fetch_assoc($r)){
				
				$dt= $row["event_date"];$dt = str_replace("-","",$dt);echo $dt;echo'<br>';
				$sTime=$row["event_time"]; $sTime= str_replace(":","",$sTime); $sTime= substr($sTime,0,4);echo $sTime;
				$sTime = gmdate("$sTime");echo $sTime;echo'<br>';
				$eTime=$sTime +'0100';echo $eTime;echo'<br>';
				$sub = $row["event_name"]; echo $sub;echo'<br>';
				$desc=  $row["event_details"]; echo $desc;echo'<br>';echo'<br>';echo'<br>';echo'<br>';echo'<br>';
				//date_default_timezone_set("Europe/London");
				$dd = date('Y-m-d H:i:s T'); $time = date('H:i:s', $sTime);
				$dd1 = gmdate('Y-m-d H:i:s T');
				echo $dd;echo'<br>';
				echo $dd1;echo'<br>';
				echo $time;echo'<br>';
				/*echo date_default_timezone_get();
				date_default_timezone_set("GMT");echo'<br>';
				echo date_default_timezone_get();echo'<br>';*/

				$userTimezone = new DateTimeZone('America/New_York');
				$gmtTimezone = new DateTimeZone('GMT');
				$myDateTime = new DateTime($sTime, $userTimezone);
				$offset = $gmtTimezone->getOffset($myDateTime);
				$myInterval=DateInterval::createFromDateString((string)$offset . 'seconds');
				$myDateTime->add($myInterval);
				$result = $myDateTime->format('H:i:s');
				Echo $result;

	
		}
	}
echo '<br> <br>';

//echo'<a href=createCalEvent.php?date='.$dt .'&time='.$stime .'&subject='. $sub  .'&desc='. $desc .' >Add to Calender</a>';
echo'<a href="ical.php?date='.$dt .'&amp;startTime='. $sTime .'&amp;endTime='.$eTime .'&amp;subject='. $sub  .'&amp;desc='. $desc .'">Add to Calender</a>';
echo'<br><br>';
echo'<a href="ical.php?date=20200415&amp;startTime=1300&amp;endTime=1400&amp;subject=Meeting&amp;desc=Meeting to discuss processes.">Add Appointment to your Outlook Calendar</a>';
}

?>
<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='add_edit_view_events.php';" class="btn btn-primary" >Back</button>
</div><br><br>
<?php
mysqli_close($dbc);
include('includes/footer.html');
?>