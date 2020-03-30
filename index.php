<?php # index.php
// This is the main page for the site.

// Include the configuration file:
require('includes/config.inc.php');
//session_unset();
// Set the page title and include the HTML header:
$page_title = 'Welcome to WIL';
include('includes/header.html');
require('includes/usagelog.php');

// Welcome the user (by name if they are logged in):
/*echo '<h4>Welcome'; */
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

?>
<html>
<body>
<form action="login.php" class="form-horizontal" method="get"><!--action supposed to ne index.php -->

<section>
<p>
The mission of Women in Licensing(WIL) is to provide a relaxed and welcoming forum for women in the field of intellectual property protection and licensing to network, share expertise and ideas, provide support and mentoring, and brainstorm on current topics of interest.
<br>
<br>
<br>
If you need more information about WIL networking group or any of its activities and meetings, please contacts any of the following administrators.
</p>
</section>

<?php
//Added the line below to show the upcoming event table ---------
if(UPCOMING_EVENTS == 'TRUE'){

?>

<h2>
<b>Check Out our Upcoming Events</b>
</h2>

 <?php

    $date =date('Y-m-d');

$q = "SELECT * FROM Events";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {
    
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
        $chapter = $row["event_belong_to_chapter"];
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
            <td>';echo $row["event_sponsors"]; echo'</td>
            <td><a href="details.php?id='.$id.'">click here</a></td>
           
		</tr>';
		
        }
    }
		echo '</table></div>';  

  }
}

//not showing all the upcoming events
else{

  $q = "SELECT first_name, last_name, email, chapter, linkedin FROM users where user_level >= '4' ";
$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));

if (mysqli_num_rows($r)>0) {
    echo '<div class="row">

  <div class="column1 left">
     
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
</table>';
					
// Display links based upon the login status:
  
  echo'</div>
  <div class="column1 right">';
  if (!isset($_SESSION['user_id'])) {

    echo'<div class="form-group">
        <label style="color: navy;" for="org_name">If you are interested in any of the  Women In Licensing events, you need to be a registered member of the WIL website and if you are not a registered member already then Register below</label>';
      //echo' <button onclick="window.location.href='register.php';" class="btn btn-primary" >Register</button>';
       echo' <a href="register.php">Register</a>
        </div>';

        echo '<label style="color: navy;" for="org_name">If you are a registered user of this site, you can login here:'; 
       // echo '<form action="login.php">';
         echo '<input type="submit" class="btn btn-primary" value="Login">';
        echo '<div class="form-group">';
      echo' </div>
   </div>';
    
           
    //echo '</form>';
       echo'</div>';   
    
      }
   
   echo '</div>
  </div>';

 

}
echo'
<div class="row">
  <div class="column1">
    <h2>Women in Licensing Events - Picture Gallary</h2>';

    $q = "SELECT * FROM `Event-Images`";
    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
    
    if (mysqli_num_rows($r)>0) {
     while($row = mysqli_fetch_assoc($r)){
    
      $img1 = $row["image_ref"];
    //echo $img1;
    echo ' <a href="enlargeImage.php?img1='.$img1.'">
    <img src="';echo $img1; echo'" alt="There are no pictures in the Database" style="width:150px">
   </a>
   ';

    }

      }
    echo'
</div>
</div>';   
}
?>
</form>
</body>
</html> 
<?php include('includes/footer.html'); ?>