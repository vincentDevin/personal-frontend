<!DOCTYPE html>
<html>
<head>
	<title><?php echo($pageTitle); ?></title>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo($pageDescription); ?>">
    <meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo(PROJECT_DIR); ?>styles/main.css">
	<script type="text/javascript" src="<?php echo(PROJECT_DIR); ?>js/main.js"></script>
</head>
<body>
    <header>
        <h1><?php echo( isset($pageTitle) ? $pageTitle : "Welcome"); ?></h1>
    </header>
    <nav id="main-nav">
        <ul>
            <li><a href="<?php echo(PROJECT_DIR); ?>index.php">Home</a></li>
            <li><a href="<?php echo(PROJECT_DIR); ?>blog/index.php">Blog</a></li>
            <li><a href="<?php echo(PROJECT_DIR); ?>contact.php">Contact</a></li>
            <?php
            // Show link to control panel if user is logged in
            if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == "yes") {
                echo('<li><a href="' . PROJECT_DIR . 'control-panel/index.php">Control Panel</a></li>');
            }
            ?>
        </ul>
    </nav>