<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Delete User';
include('includes/header.html');

if (!isset($_SESSION['user_id'])) {

	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // Need the database connection:
    require(MYSQL);
    
    // Trim all the incoming data:
    $trimmed = array_map('trim', $_POST);
    
    // Assume invalid values:
    $userEmail = FALSE;
   
    
        // Check for a Chapter name:
            if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
                $userEmail = mysqli_real_escape_string($dbc, $trimmed['email']);
            } else {
                echo '<p class="error">Please enter a valid email address!</p>';
            }

        if ($userEmail) { // If everything's OK...

            // Add the code for Pop-up window here .......

            $q = "Delete from users where email='$userEmail'";
            $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
    
            //$q1 = "SELECT email from users where (first_name='$chAdF' AND last_name='$chAdL')";
            //$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
            //$email = $r1;
            if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....
            
                $body = "You have successfully deleted User.  \n\n";
                
                //mail('$email', 'Chapter Created!', $body, 'From: admin@WIL.com');
                echo $body;
                echo '<form action="index.php">';
				echo '<div class="form-group">
					<div class="col-sm-2">
						<input type="submit" class="btn btn-primary" name="Back" value="Back">
					</div>
				</div>';        
				echo '</form>';
				echo '</p>';
				
    
                include('includes/footer.html'); // Include the HTML footer.
                exit(); // Stop the page.
            }
    
        }


    }
?>




<h1>Delete User</h1>
<form class="form-horizontal" action="delete_users.php" method="post">
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="email">Which User do you want to delete?</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="email" placeholder="Enter User Email">
	</div>
</div>	


<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
       <button name="submit" type="submit" class="btn btn-primary" >Delete User</button>
       <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
    </div>
</div>	
</form>

<?php include('includes/footer.html'); ?>
