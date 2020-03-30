<?php
// backend script for writing usagelog
// This is the script where usage log is written to the datbase.
require('includes/config.inc.php');

// Start output buffering:
ob_start();

// Initialize a session:
session_start();
require('responseclass.php');
$response = new Response;
$response->setStatus("");
$response->setMessage("");
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.
    require(MYSQL);
    $trimmed = array_map('test_input', $_POST);   
    $session=mysqli_real_escape_string($dbc, $trimmed['sessionid']);
    $userid=mysqli_real_escape_string($dbc, $trimmed['user_id']);
    $host=mysqli_real_escape_string($dbc, $trimmed['host']); 
    $server=mysqli_real_escape_string($dbc, $trimmed['server']); 
    $serverAddress=mysqli_real_escape_string($dbc, $trimmed['server_address']); 
    $serverPort=mysqli_real_escape_string($dbc, $trimmed['server_port']); 
    $script=mysqli_real_escape_string($dbc, $trimmed['script_name']); 
    $serverProtocol=mysqli_real_escape_string($dbc, $trimmed['server_protocol']); 
    $serverSign=mysqli_real_escape_string($dbc, $trimmed['server_signature']); 
    $origin=mysqli_real_escape_string($dbc, $trimmed['origin']); 
    $userAgent=mysqli_real_escape_string($dbc, $trimmed['user_agent']); 
    $remoteAddress=mysqli_real_escape_string($dbc, $trimmed['remote_address']); 
    $remotePort=mysqli_real_escape_string($dbc, $trimmed['remote_port']);
    $referrer=mysqli_real_escape_string($dbc, $trimmed['referrer']);
    $requestMethod=mysqli_real_escape_string($dbc, $trimmed['request_method']); 
    $requestScheme=mysqli_real_escape_string($dbc, $trimmed['request_scheme']); 
    $requestURI=mysqli_real_escape_string($dbc, $trimmed['request_uri']); 
$q = "INSERT INTO usagelog (
		usage_date, time, sessionid, userid, host, server, server_address, 
        server_port, script_name, server_protocol, server_signature, 
        origin,  user_agent,  remote_address,remote_port, referrer,
		request_method, request_scheme, request_uri) 
VALUES (NOW(),NOW(),'$session', $userid, '$host', '$server', '$serverAddress',
         $serverPort, '$script', '$serverProtocol', '$serverSign','$origin', 
        '$userAgent', '$remoteAddress', $remotePort, '$referrer',
		'$requestMethod', '$requestScheme', '$requestURI')";
$r = mysqli_query($dbc, $q); // or trigger_error("Query: $q\n<br>MySQL Error: " . mysqli_error($dbc));
if (mysqli_error($dbc) == "" ) {
    $last_row = mysqli_insert_id($dbc);
    $response->setStatus("Success");
	$response->setMessage($last_row);
} else {
    $response->setStatus("Error");
	$response->setMessage('Query: '.$q.' MySQL Error: '.mysqli_error($dbc).' There was an error processing your request.');
}
mysqli_close($dbc);
} else {
    $response->setStatus("Error");
	$response->setMessage('Invaid request.');
}
echo json_encode($response);
//ob_end_flush();
?>