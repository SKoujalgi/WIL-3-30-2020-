<?php # Script 16.3 - view_users.php #6
// This script retrieves all the records from the users table.
require('includes/config.inc.php');
$page_title = 'WIL Members';
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

<form  class="form-horizontal" method="get" action="add_users.php">



<h2>
<b>Members</b>
</h2>

<?php
$q = "SELECT * FROM users";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));


if (mysqli_num_rows($r)>0) {

	echo'
	<table class="table1" width="100%">
		<tr>
		  <th>Name</th>
		  <th>Email</th>
		  <th>Phone</th>
		  <th>Company</th>
		  <th>Role</th>
          <th>Chapter</th>
          <th>Edit</th>
		</tr>';

    while($row = mysqli_fetch_assoc($r)){
       $email = $row["email"];
		echo'
		<tr>
			<td>';echo $row["first_name"]. " ".$row["last_name"]; echo'</td>
			<td>';echo $row["email"]; echo'</td>
			<td>';echo $row["work_phone"]; echo'</td>
			<td>';echo $row["co_name"]; echo'</td>
			<td>';echo $row["u_role"]; echo'</td>
            <td>';echo $row["chapter"]; echo'</td>
            <td><a href="edit_users.php?email='.$email.'">edit here</a></td>
		</tr>';
		
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