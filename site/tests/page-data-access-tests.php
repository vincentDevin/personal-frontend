<?php 
require_once("../includes/config.inc.php");
require_once("../includes/PageDataAccess.inc.php");

/*
$pda = new PageDataAccess(getDBLink());

echo("ACTIVE PAGES<br>");
$activePages = $pda->getPageList();
var_dump($activePages);

echo("<br>ALL PAGES<br>");
$allPages = $pda->getPageList(false);
var_dump($allPages);

echo("<br>SINGLE PAGE<br>");
$page = $pda->getPageById(1);
var_dump($page);

echo("<br>SINGLE PAGE W/ ERROR<br>");
try {
    $page = $pda->getPageById("blah");
    var_dump($page);
} catch (Exception $e) {
    echo("YES! the handleError() function worked and threw an exception!");
}
*/

try {
    $dbLink = getDBLink();
    echo "<p>Database connection successful.</p>";
} catch (Exception $e) {
    echo "<p>Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

try {
    $pda = new PageDataAccess($dbLink);
    $activePages = $pda->getPageList();
    echo "<pre>";
    print_r($activePages);
    echo "</pre>";
    // Proceed with rendering the page
} catch (Exception $e) {
    echo "<p>Error retrieving pages: " . $e->getMessage() . "</p>";
}


?>