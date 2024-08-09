<?php
require_once("includes/config.inc.php");

$pageTitle = "Page Not Found";
$pageDescription = "We're sorry, we cannot find the page you are looking for.";

require("includes/header.inc.php");

?>
		<main>

			<div class="content-frame">
				<h3>Page Not Found</h3>
				<p>We're sorry, we can't find the page you're looking for.</p>
			</div>
			
		</main>
		

<?php
if(!empty($sideBar)){
	require("includes/" . $sideBar);
}
require("includes/footer.inc.php");
?>