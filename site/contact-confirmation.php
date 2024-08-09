<?php
$pageTitle = "Contact Confirmation";
$pageDescription = "Your contact form has been submitted!";
$sideBar = "";
require_once("includes/config.inc.php");
require("includes/header.inc.php");

?>
<main>
    <h1>Contact Form Submitted Successfully!</h1>
    <p> Thank you for submitting a contact form we 
        will read your form in a timely manner!
    </p>
	
</main>
<?php
if(!empty($sideBar)) {
	require("includes/" . $sideBar);
}

require("includes/footer.inc.php");
?>

