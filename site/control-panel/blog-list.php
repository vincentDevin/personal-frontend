<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");

$pageTitle = "Blog List";
$pageDescription = "";

// Handle deletion
if (isset($_GET['deletePageId'])) {
    $pageId = intval($_GET['deletePageId']);
    if ($pageId > 0) {
        $url = API_BASE_URL . "/control-panel/pages/{$pageId}";
        $token = $_SESSION['token']; // Ensure the token is available in the session
        $response = callAPI('DELETE', $url, false, $token);

        if ($response['status_code'] == 200) {
            header("Location: blog-list.php");
            exit();
        } else {
            echo "<p>Error deleting page. HTTP Status Code: {$response['status_code']}</p>";
        }
    }
}

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame">
        <h3>Blog List</h3>
        <?php
        // Fetch the blog pages using the API
        $url = API_BASE_URL . "/control-panel/pages/all";
        $token = $_SESSION['token']; // Ensure the token is available in the session
        $response = callAPI('GET', $url, false, $token);

        if ($response['status_code'] == 200) {
            $pages = $response['response'];
            echo(displayPages($pages));
        } else {
            echo "<p>Error fetching pages. HTTP Status Code: {$response['status_code']}</p>";
        }
        ?>
    </div>
</main>

<?php
require("../includes/footer.inc.php");

function displayPages($pages) {
    // Start table
    $html = "<table border=\"1\">";

    // column headers
    $html .= "<tr>
                <th>Title</th>
                <th>Publish Date</th>
                <th>Active</th>
                <th>Edit</th>
                <th>Delete</th>
             </tr>";

    // table rows
    foreach($pages as $page) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($page['title']) . "</td>";
        $html .= "<td>" . htmlspecialchars($page['publishedDate']) . "</td>";
        $html .= "<td>" . htmlspecialchars($page['active']) . "</td>";
        $html .= "<td><a href=\"blog-details.php?pageId=" . htmlspecialchars($page['pageId']) . "\">EDIT</a></td>";
        $html .= "<td><a href=\"blog-list.php?deletePageId=" . htmlspecialchars($page['pageId']) . "\" onclick=\"return confirm('Are you sure you want to delete this page?');\">DELETE</a></td>";
        $html .= "</tr>";
    }

    $html .= "</table>";

    return $html;
}
?>
