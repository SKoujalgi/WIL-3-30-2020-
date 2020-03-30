<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Delete a Sponsor';
include('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // Need the database connection:
    require(MYSQL);
    
    // Trim all the incoming data:
    $trimmed = array_map('trim', $_POST);
    
    // Assume invalid values:
    $spN = FALSE;
    
        // Check for a Sponsor name:
        if (preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['spoName'])) {
            $spN = mysqli_real_escape_string($dbc, $trimmed['spoName']);
        } else {
            echo '<p class="error">Please enter Sponsor name!</p>';
        }

        if ($spN) { // If everything's OK...

            // Add the code for Pop-up window here .......

            $q = "Delete from Sponsor where sponsor_name ='$spN'";
            $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
    
            //$q1 = "SELECT email from users where (first_name='$chAdF' AND last_name='$chAdL')";
            //$r1 = mysqli_query($dbc, $q1) or trigger_error("Query: $q1\n<br>MySQL Error: " . mysqli_error($dbc));
            //$email = $r1;
            if(mysqli_affected_rows($dbc) == 1){// if the query runs Okay.....
            
                $body = "You have successfully deleted all the informtion about the Chapter.  \n\n";
                
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




<h1>Delete Sponsor</h1>
<form class="form-horizontal" action="deleteSp.php" method="post">
<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="spoName">Which Sponsor do you want to delete?</label>
	<div class="col-sm-4">
		<input type="text" class="form-control" name="spoName" placeholder="Enter Sponsor Name">
	</div>
</div>	


<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
       <button name="submit" type="submit" class="btn btn-primary" >Delete Sponsor</button>
       <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
    </div>
</div>	
</form>

<?php include('includes/footer.html'); ?>
