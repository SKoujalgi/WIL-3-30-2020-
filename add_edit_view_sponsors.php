<?php # Script 16.3 - view_users.php #6
// This script retrieves all the records from the users table.
require('includes/config.inc.php');
$page_title = 'WIL | Sponsors';
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

<form  class="form-horizontal" method="get" action="addSp.php">
<?php
	// Display links based upon the login status:
	if (isset($_SESSION['user_id'])) {
	// Add links if the user is an administrator:
			if ($_SESSION['user_level'] >=4) {
				echo'
<p align="right">
		<a href="addSp.php">
          <span class="glyphicon glyphicon-plus-sign">Add</span>
        </a>
</p>';
	}
}
?>

<h2>
<b>Sponsors</b>
</h2>

<?php
$date = date('Y-m-d');
$q = "SELECT Sponsor.sponsor_no,Sponsor.sponsor_name,Sponsor.event_name, Events.event_date
FROM (Sponsor
INNER JOIN Events ON Sponsor.event_name = Events.event_name);";
//$q = "SELECT * FROM Sposnor";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


if (mysqli_num_rows($r)>0) {

	echo'
	<table class="table1" width="100%">
		<tr>
		  <th>Sponsor Name</th>
		  <th>Event Name</th>
		  <th>Event Date</th>
		  <th>Edit</th>
		</tr>';

    while($row = mysqli_fetch_assoc($r)){
		$date1 = $row["event_date"];
	   $spNo = $row["sponsor_no"];
	  // if($date < $date1){
		echo'
		<tr>
			<td>';echo $row["sponsor_name"]; echo'</td>
			<td>';echo $row["event_name"]; echo'</td>
			<td>';echo $row["event_date"]; echo'</td>
			<td><a href="editSp.php?spNo='.$spNo.'">edit here</a></td>
			
		</tr>';
	  // 	}
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