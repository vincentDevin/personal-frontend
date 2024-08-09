<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");

// Handle logout action before any output
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Destroy the session
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$pageTitle = "Control Panel";
$pageDescription = "";

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame cpanel-home">
        <h1>Control Panel</h1>
        <a href="blog-list.php">Blog List</a>
        <a href="blog-details.php">Blog Details</a>
        <a href="contacts.php">Contact Submissions</a>
        <a href="?action=logout" style="float:right;">Logout</a>
    </div>
</main>

<?php
require("../includes/footer.inc.php");
?>
