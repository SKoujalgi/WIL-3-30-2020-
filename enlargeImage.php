<?php # index.php
// This is the main page for the site.

// Include the configuration file:
require('includes/config.inc.php');
//session_unset();
// Set the page title and include the HTML header:
$page_title = 'Event Pictures!';
include('includes/header.html');

if (isset($_SESSION['first_name'])) {
	echo "<h1>Hello {$_SESSION['first_name']}, ";
} else {
    echo "<h1>";
}
echo 'Welcome to Women in Licensing!!</h1>';

/* ------Print session variables-------
echo '<pre>';
var_dump($_SESSION);
echo '</pre>'; 
-------End printng-------------------*/
if ($_SERVER['REQUEST_METHOD'] == 'GET') { // Handle the form.

	// Need the database connection:
    require(MYSQL);
    // Trim all the incoming data:
	//$trimmed = array_map('trim', $_GET);
    
}

$img1 =  $_GET["img1"];
//echo $img1;


echo '
<img src="';echo $img1; echo'" alt="WIL Pictures!" style="width:100%">
';


?>
<br>
<br>
<div class="col-sm-offset-11 col-sm-1">
		<button onclick="window.location.href='index.php';" class="btn btn-primary" >Back</button>
</div>






<?php include('includes/footer.html'); ?>