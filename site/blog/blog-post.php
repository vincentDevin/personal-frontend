<?php
require_once("../includes/config.inc.php");

$page = null;

// Use the pageId query string param to get all the data for the blog page
if (isset($_GET['pageId'])) {
    $pageId = $_GET['pageId'];
    logMessage("Page ID: {$pageId}");

    // Use the global callAPI function
    $response = callAPI('GET', API_BASE_URL . "/pages/{$pageId}");

    if ($response['status_code'] === 200) {
        $page = $response['response'];

        // Check if the API response is valid JSON and contains data
        if (json_last_error() !== JSON_ERROR_NONE || !$page || $page['active'] == "no") {
            logMessage("Redirecting to 404");
            redirectTo404Page();
        }
    } else {
        logMessage("Failed to fetch page. HTTP Code: " . $response['status_code']);
        redirectTo404Page();
    }
} else {
    redirectTo404Page();
}

$pageTitle = $page['title'];
$pageDescription = $page['description'];

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame">
        <article>
            <h3 class="blog-post-title"><?php echo htmlspecialchars($page['title']); ?></h3>
            <i class="blog-post-date">Date Posted: <?php echo htmlspecialchars($page['publishedDate']); ?></i>
            <div class="blog-post-content">
                <?php echo $page['content']; ?>
            </div>
        </article>
    </div>
</main>
<?php
require("../includes/footer.inc.php");
?>
