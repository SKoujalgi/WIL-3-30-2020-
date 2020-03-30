<?php
require('includes/config.inc.php');

// Start output buffering:
ob_start();

// Initialize a session:
session_start();
require('responseclass.php');
$response = new Response;
$response->status="";
$response->message="";
if (!isset($_SESSION['user_id'])) {
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
    header("Location: $url");
 //   echo 'Quitting the Proce route.php'.$url.' User ID = '.$_SESSION['user_id'];
    exit(); // Quit the script.
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
 //   echo 'Not exiting....Proceeding.....';
    require(MYSQL);

	// Trim all the incoming data:
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, FALSE);
    $message_type=test_input   ($input->message_type);
    $message_subtype=test_input($input->message_subtype);
    if ($message_type == 'User') {
        if ($message_subtype == 'Sign') {
            $agreed=test_input($input->agreement);
            $user_id=$_SESSION['user_id'];
            if ($agreed) {
                $query="UPDATE users AS U SET U.agreement_accepted = 'Y', update_date=NOW()
                WHERE U.user_id = '$user_id'";
//                $r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
                $r = mysqli_query($dbc, $query);
                if (mysqli_error($dbc) == "" ) {
                    $response->status="Success";
                    $response->message="User agreement signed successfully.";
                    $query="SELECT U.agreement_accepted, U.force_pass_change FROM users as U
                    WHERE U.user_id = $user_id";
//                    $r = mysqli_query($dbc, $query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysqli_error($dbc));
                    $r = mysqli_query($dbc, $query);
                    if (mysqli_error($dbc) == "" ) {
                        if (@mysqli_num_rows($r) == 1) {
                            list($agreement_accepted, $force_pass_change) = mysqli_fetch_array($r, MYSQLI_NUM);
                            mysqli_free_result($r);
                            $_SESSION['agreement_accepted'] = $agreement_accepted;
                            $_SESSION['force_pass_change']  = $force_pass_change;
                        } else {
                            $response->status="Error";
                            $response->message="The user agreement could not be signed at this time. Please try again.";
                        }
                    } else {
                        $response->status="Error";
//                      $message=htmlentities('Query: '.$query.' MySQL Error: '.mysqli_error($dbc).' There was an error accessing the user agreement.');
//                        $response->message='Query: '.$query.' MySQL Error: '.mysqli_error($dbc).' There was an error accessing the user agreement.';
                        $response->message='MySQL Error: '.mysqli_error($dbc).' There was an error accessing the user agreement.';
                    }
                } else {
                    $response->status="Error";
//                  $message=htmlentities('Query: '.$query.'MySQL Error: '.mysqli_error($dbc).' There was an error updating the user agreement.');
                    $response->message='Query: '.$query.' MySQL Error: '.mysqli_error($dbc).' There was an error updating the user agreement. '.mysqli_affected_rows($dbc);

                }
            }    
        }
    }
    mysqli_close($dbc);
    $outp=json_encode($response);
    //   echo($outp); 
    echo($outp);
}
?>    