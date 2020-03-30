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
$eventNo = $_GET["eventid"];
$chapterNo = $_GET["id"];

//echo $eventNo;
//echo  $chapterNo;


if($chapterNo == 1){
	$chapter = "New York";
}elseif($chapterNo == 2){
    $chapter = "Washington DC";
}elseif($chapterNo == 3){
    $chapter = "Boston";
}elseif($chapterNo == 4){
    $chapter = "San Fransisco";
}elseif($chapterNo == 5){
    $chapter = "Texas";
}elseif($chapterNo == 6){
    $chapter = "Georgia";
}



?>
<form action="rsvpDone.php" method="POST">
<h1><b>RSVP</b></h1>


<?php
$uid =  $_SESSION['user_id'];

$q = "SELECT * FROM Events where chapter_name= '$chapter' && event_no ='$eventNo'"; // Add And to this SQL to get only one event for this chapter and event number.
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {


    while($row = mysqli_fetch_assoc($r)){
		
		
		$evNo= $row["event_no"];
		
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
?>

<section>
	<div align= "center" class="eventHeader">
		<label for="rsvp">EVENT RSVP :</label>&nbsp&nbsp
		<button type="button" onclick="window.location.href='rsvpDone.php?evNo='+ <?php echo $evNo ?>+ '&uid='+ <?php echo $uid ?>+ '&res='+1  ;" class="btn btn-primary" >Yes</button>&nbsp&nbsp

		<button type="button" onclick="window.location.href='rsvpDone.php?evNo='+ <?php echo $evNo ?>+ '&uid='+ <?php echo $uid  ?>+ '&res='+2  ;" class="btn btn-primary" >No</button>&nbsp&nbsp

		<button type="button" onclick="window.location.href='rsvpDone.php?evNo='+ <?php echo $evNo ?>+ '&uid='+ <?php echo $uid  ?> + '&res='+3 ;" class="btn btn-primary" >MayBe</button>&nbsp&nbsp


	</div>
</section>

</form>

<br>

	<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='add_edit_view_events.php';" class="btn btn-primary" >Back</button>
	</div>

<?php
mysqli_close($dbc);
include('includes/footer.html');
?>