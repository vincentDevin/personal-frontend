<?php 
require_once("../includes/config.inc.php");

echo("Allow clean html<br>");
$html = "<p>this is a paragraph</p>";
$result = sanitizeHtml($html);
echo($result);

echo("<br>block bad html<br>");
$html = "<script>this is a script element</script>";
$result = sanitizeHtml($html);
echo($result);

echo("<br>allow clean attributes<br>");
$html = "<img src='https://via.placeholder.com/150 ' >";
$result = sanitizeHtml($html);
echo($result);

echo("<br>block bad attributes<br>");
$html = "<img onclick='google.com' >";
$result = sanitizeHtml($html);
echo($result);
?>