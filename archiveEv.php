<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'Archive Events';
include('includes/header.html');

?>




<h1>Archive Events</h1>
<form class="form-horizontal" action="archiveEv.php" method="post">

<div class="form-group">
	<label class="col-sm-3" style="color: navy;" for="chaName">Which Chapter Event to be Archived?</label>
	<div class="col-sm-4">
		<input type="password" class="form-control" name="chaName" placeholder="Enter Chapter this event belongs">
	</div>
	
</div>	


<div class="form-group">
    <div class="col-sm-offset-3 col-sm-4">
       <button name="submit" type="submit" class="btn btn-primary" >Archive Events</button>
       <button type="reset" class="btn btn-primary" value="Reset">Clear</button>
    </div>
</div>	
</form>

<?php include('includes/footer.html'); ?>
