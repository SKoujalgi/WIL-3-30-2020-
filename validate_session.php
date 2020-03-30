<?php
// backend script for validating the user session
require('includes/config.inc.php');
// Start output buffering:
//ob_start();
// Initialize a session:
//session_start();
require('responseclass.php');
$response = new Response;
$response->setStatus("");
$response->setMessage("");
$trimmed = array_map('test_input', $_POST);
if ($_SERVER['REQUEST_METHOD'] == "POST") { // Handle the form.
	require(MYSQL);
	$live_id=$trimmed['live_id'];
	$live_userid=$trimmed['live_userid'];
	$live_session = $trimmed['live_session'];
	$live_useragent=$trimmed['live_useragent'];
	$live_remoteaddress=$trimmed['live_remoteaddress'];
	if (isset($live_id)) {
		$query="Select id, session_id, userid, last_active, status, remote_address, user_agent, DATE_ADD(last_active, INTERVAL ".TIMEOUT_IN_MINUTES." MINUTE) AS timeout, NOW() as now 
		from sessions where id = $live_id";
		$r = mysqli_query($dbc, $query);
		if (mysqli_error($dbc) == "" ) {
			$num = $r->num_rows;
			if ($num == 1) { // If it ran OK, display the records.
				list($id, $session_id, $userid, $last_active, $status,$remote_address, $user_agent, $timeout, $now) = mysqli_fetch_array($r, MYSQLI_NUM);
				mysqli_free_result($r);
//				$nowtime = date('Y-m-d h:i:s');
				if ($live_userid        <> $userid || 
					$live_session       <> $session_id          || 
					$live_remoteaddress <> $remote_address      ||
					$live_useragent     <> $user_agent ) {
						$response->setStatus("Invalid");
						$response->setMessage("Invalid Session");
				} else {
					if ($status == "Active" && $now <= $timeout) {
						$q = "UPDATE sessions SET last_active = NOW(), last_update=NOW()
						WHERE id =  $id"; 
						$r = mysqli_query($dbc, $q);
						if (mysqli_error($dbc) == "" ) {
							$affected_rows=mysqli_affected_rows($dbc);
							if ($affected_rows <> -1) {
								$response->setStatus("Active");
								$response->setMessage("Session last active at $last_active. Will timeout at $timeout");
							} else {
								$response->setStatus("Error");
								$response->setMessage("Session # $id, Error updating $affected_rows records.");
							}			
						} else {
							$response->setStatus("Error");
							$response->setMessage('Query: '.$q.' MySQL Error: '.mysqli_error($dbc).' There was an error updating the session.');
						}
					} else {
						$response->setStatus("Timedout");
						$response->setMessage("Session Timedout at $timeout");
					}
				}
			} else {
				$response->setStatus("Error");
				$response->setMessage("Session Id - $num records found");
			}		
		} else {
			$response->setStatus("Error");
			$response->setMessage('Query: '.$q.' MySQL Error: '.mysqli_error($dbc).' There was an error retrieving the session data.');
		}
	} else {
		$response->setStatus("Error");
		$response->setMessage('Not Logged-in');
		}
mysqli_close($dbc);
} else {
    $response->setStatus("Error");
	$response->setMessage('Invaid request.');
}
echo json_encode($response);
//ob_end_flush();
?>