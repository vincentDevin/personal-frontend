<?php
require_once("../includes/config.inc.php");

$pageTitle = "Blog";

try {
    require("../includes/header.inc.php");

    // Use the global API_BASE_URL
    $apiUrl = API_BASE_URL . '/pages';

    // Call API to get the list of active blog pages
    $response = callAPI('GET', $apiUrl);

    if ($response['status_code'] === 200) {
        $activePages = $response['response'];

        // Check if the API response is valid
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($activePages)) {
            throw new Exception("Failed to decode JSON response: " . json_last_error_msg());
        }

        if (empty($activePages)) {
            echo "<p>No blog posts found.</p>";
        } else {
            echo "<main>
                <div class=\"content-frame\">
                    <h1>Blog Posts</h1>
                    " . createBlogList($activePages) . "
                </div>
            </main>";
        }
    } else {
        throw new Exception("Failed to fetch blog pages. HTTP Status Code: " . $response['status_code']);
    }

    require("../includes/footer.inc.php");
} catch (Exception $e) {
    // Display the error message on the page
    echo "<p>Exception: " . htmlspecialchars($e->getMessage()) . "</p>";

    // Optionally, log the error to a file for debugging purposes
    error_log("Blog List Page Error: " . $e->getMessage());
}

function createBlogList(array $pages): string {
    $html = "<ul class=\"blog-list\">";
    foreach ($pages as $p) {
        $html .= "<li>";
        $html .= "<a href=\"blog-post.php?pageId=" . htmlspecialchars($p['pageId']) . "\">";
        $html .= "<div class=\"blog-list-title\">" . htmlspecialchars($p['title']) . "</div>";
        $html .= "<div class=\"blog-list-date\">Posted: " . htmlspecialchars($p['publishedDate']) . "</div>";
        $html .= "<div class=\"blog-list-description\">" . htmlspecialchars($p['description']) . "</div>";
        $html .= "<div class=\"blog-list-read-more\">Read More>>></div>";
        $html .= "</a>";
        $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
}
?>
