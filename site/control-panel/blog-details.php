<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");

$pageTitle = "Blog Details";
$pageDescription = "";

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['pageId'])) {
        $pageId = $_GET['pageId'];
        $url = API_BASE_URL . "/pages/{$pageId}";
        $token = retrieveToken(); // Use the secure method to retrieve the token
        $response = callAPI('GET', $url, false, $token);

        if ($response['status_code'] == 200) {
            $page = $response['response'];
            // Set default values for the form fields
            $path = $page['path'];
            $title = $page['title'];
            $description = $page['description'];
            $content = $page['content'];
            $publishedDate = $page['publishedDate'];
            $categoryId = $page['categoryId'];
            $setActive = $page['active'];
        } else {
            $errorMessage = "Error fetching page. HTTP Status Code: {$response['status_code']}";
        }
    } else {
        // Default values if no pageId is provided
        $pageId = '';
        $path = '';
        $title = '';
        $description = '';
        $content = '';
        $publishedDate = '';
        $categoryId = '';
        $setActive = '';
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $pageId = $_POST['pageId'] ?? '';
    $path = $_POST['path'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';
    $publishedDate = $_POST['publishedDate'] ?? '';
    $categoryId = $_POST['categoryId'] ?? '';
    $setActive = $_POST['active'] ?? 'no';

    if (validateBlogPostData($title, $description, $content, $categoryId, $setActive)) {
        $data = [
            'title' => $title,
            'content' => $content,
            'description' => $description,
            'active' => $setActive,
            'categoryId' => $categoryId,
            'path' => $path,
            'publishedDate' => $publishedDate,
        ];
        $token = retrieveToken(); // Use the secure method to retrieve the token

        if (!empty($pageId)) {
            $url = API_BASE_URL . "/pages/{$pageId}";
            $response = callAPI('PUT', $url, $data, $token);

            if ($response['status_code'] == 200) {
                $msg = "Blog page updated";
                sendEmail(ADMIN_EMAIL, "Blog Post Updated!", $msg);
                header("Location: blog-list.php"); // Redirect to blog-list.php
                exit();
            } else {
                $errorMessage = "Query Issue: page not updated. HTTP Status Code: {$response['status_code']}";
            }
        } else {
            $url = API_BASE_URL . "/pages";
            $response = callAPI('POST', $url, $data, $token);

            if ($response['status_code'] == 200) {
                $msg = "New blog page posted";
                sendEmail(ADMIN_EMAIL, "New Blog Post!", $msg);
                header("Location: blog-list.php"); // Redirect to blog-list.php
                exit();
            } else {
                $errorMessage = "Query Issue: page not posted. HTTP Status Code: {$response['status_code']}";
            }
        }
    } else {
        $errorMessage = "Could not validate form.";
    }
} else {
    // Only accept GET and POST requests
    header("Location: " . PROJECT_DIR . "error.php");
    exit();
}

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame">
        <h3>Blog Details</h3>
        <?php if (!empty($errorMessage)) : ?>
            <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
        <form class="control-panel" method="POST" action="<?php echo($_SERVER['PHP_SELF']) ?>">
            <input type="hidden" name="pageId" value="<?php echo htmlentities($pageId); ?>" />
            <label>Path Word</label>
            <input type="text" name="path" placeholder="mypost" value="<?php echo htmlentities($path); ?>" />
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlentities($title); ?>" />
            <label>Description</label>
            <textarea name="description"><?php echo htmlentities($description); ?></textarea>
            <label>Content</label>
            <textarea name="content"><?php echo htmlentities($content); ?></textarea>
            <label>Published Date (mm/dd/yyyy)</label>
            <input name="publishedDate" value="<?php echo htmlentities($publishedDate); ?>" />
            <label>Category</label>
            <select name="categoryId">
                <option value="1" <?php if ($categoryId == 1) echo 'selected'; ?>>Test</option>
                <option value="2" <?php if ($categoryId == 2) echo 'selected'; ?>>Test 2</option>
            </select>
            <label>Active</label>
            <input type="radio" name="active" value="yes" <?php if ($setActive == 'yes') echo 'checked'; ?>> YES    
            <input type="radio" name="active" value="no" <?php if ($setActive == 'no') echo 'checked'; ?>> NO
            <br>    
            <input type="submit" value="SAVE" />    
        </form>
    </div>
</main>

<?php
require("../includes/footer.inc.php");

function validateBlogPostData(string $title, string $description, string $content, string $categoryId, string $active): bool {
    if (empty($title) || empty($description) || empty($content) || empty($categoryId) || empty($active)) {
        echo "Fields left empty";
        return false;
    }
    
    return true;
}
?>
