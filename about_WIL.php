<?php # Script 18.8 - login.php
// This is the login page for the site.
require('includes/config.inc.php');
$page_title = 'About WIL';
include('includes/header.html');

?>
<form action="index.php">
<h1> About Us </h1>

<p><strong>
The mission of WIL is to provide a relaxed and welcoming forum for women in the field of intellectual property protection and licensing to network, share expertise and ideas, provide support and mentoring, and brainstorm on current topics of interest.
</strong>
</p>

<div class="col-sm-offset-11 col-sm-1">
	<button name="submit" type="submit" class="btn btn-primary" >Back</button>
</div>
</form>
<?php include('includes/footer.html'); ?>
