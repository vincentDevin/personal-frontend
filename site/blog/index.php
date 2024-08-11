<?php
require_once("../includes/config.inc.php");

$pageTitle = "Blog";
require("../includes/header.inc.php");

try {
    $apiUrl = API_BASE_URL . '/pages';
    $response = callAPI('GET', $apiUrl);

    if ($response['status_code'] === 200) {
        $activePages = $response['response'];

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

} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "<p>Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
        error_log("Blog List Page Error: " . $e->getMessage()); // Log errors only in DEBUG mode
    } else {
        echo "<p>Sorry, something went wrong while loading the blog posts. Please try again later.</p>";
    }
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

require("../includes/footer.inc.php");
?>
