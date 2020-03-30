<?php # Script 18.9 - end_session.php
// This is the page used to show the logoff message.
require('includes/config.inc.php');
$page_title = 'WIL | Logged off';
include('includes/header.html');
?>

<script>
    history.pushState(null, null, null);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, null);
    });
</script>

<?php 

$event='Closed';
if (isset($_GET['event'])) {
   $event=test_input($_GET['event']);
   //$refferer = test_input($_GET['referrer']);
}
//echo "Entering into end_session from : $refferer";
if ($event=='Timedout') {
    echo '
    <p class="error">
    <strong>Thank you. You have been logged out due to inactivity.</strong>
    </p>
    <br>
    ';
} elseif ($event=='Invalid') {
    echo '
    <p class="error">
    <strong>Your session is invalid. You have been logged out.</strong>
    </p>
    <br>
    ';
} elseif ($event=='Error') {
    echo '
    <p class="error">
    <strong>There was an error or you are not logged-in.</strong>
    </p>
    <br>
    ';
} else {
    echo '
    <p class="error">
    <strong>Thank you. You have logged out successfully.</strong>
    </p>
    <br>
    ';
}

	// Display links based upon the login status:
if (!isset($_SESSION['user_id'])) {
        echo '<br>';        
//        echo '<p>If you are a registered user of this site, you can login here:'; 
        echo '<form action="login.php">';
//        echo '<input type="submit" class="btn btn-primary" value="Login">';
        echo '<div class="form-group">
        <label class="col-sm-4" style="color: navy;">If you are a registered user, you can login here:</label>
        <div class="col-sm-2">
            <input type="submit" class="btn btn-primary" name="login" value="Login">
        </div>
    </div>';    
    echo '</form>';
}
    echo '<br>';        
    echo '<br>';                
    echo '<form action="index.php">';
    echo '<div class="form-group">
    <label class="col-sm-4" style="color: navy;">Alternately, you can return to the home page:</label>
    <div class="col-sm-2">
        <input type="submit" class="btn btn-primary" name="home" value="Home">
    </div>
</div>';        
echo '<br>';        
echo '<br>';        
echo '</form>';
echo '<br>';        
echo '<br>';        
echo '<br>';        
echo '<br>';        
echo '<br>';        
echo '<br>';        
echo '<br>';        
echo '<br>'; 
echo '<br>';        
echo '<br>';        
echo '<br>';
include('includes/footer.html');
?>
