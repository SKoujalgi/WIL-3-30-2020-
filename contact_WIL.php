<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Contacts Information';
include('includes/header.html');
$req_user_level=1;
require('includes/check_active_session.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}
?>

<form action="index.php" class="form-horizontal" method="get"> 
<h1> Contact Us </h1>
<section>
<p>
If you need more information about WIL networking group or any of its activities and meetings, please contacts any of the following administrators.
</p>
</section>

<?php

$q = "SELECT first_name, last_name, email, chapter, linkedin FROM users where user_level >= '4' ";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if(mysqli_num_rows($r)>0){

	echo '<div>
     
    <table class="table1">
  <tr>
    <th>Contact Name</th>
    <th>Email</th>
    <th>Chapter</th>
    <th>LinkedIn</th>
  </tr>';

  while($row = mysqli_fetch_assoc($r)){
    $cName = $row["first_name"];
    $email = $row["email"];
    $chapter = $row["chapter"];
    $linkedIn = $row["linkedin"];

    echo'
        <tr>
            <td>';echo $cName; echo'</td>
            <td>';echo $email; echo'</td>
            <td>';echo $chapter; echo'</td>
            <td><a href="https://www.linkedin.com">';echo $linkedIn; echo'</td>
       
        </tr>';

    }
    echo'
        </table>
  </div>
  <br>';

}

?>

<div class="col-sm-offset-11 col-sm-1">
	<button name="submit" type="submit" class="btn btn-primary" >Back</button>
</div>
<br>
</form> 

<?php include('includes/footer.html'); ?>