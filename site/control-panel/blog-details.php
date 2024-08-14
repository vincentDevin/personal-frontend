<?php
include_once('../includes/config.inc.php');
require("authentication-check.inc.php");

$pageTitle = "Blog Details";
$pageDescription = "";

// Get the page ID from the query parameter
$pageId = isset($_GET['pageId']) ? intval($_GET['pageId']) : null;
$method = $pageId ? 'PUT' : 'POST';
$url = $API_BASE_URL . '/control-panel/pages' . ($pageId ? '/' . $pageId : '');

// Initialize variables for form fields
$title = '';
$description = '';
$content = '';
$path = '';
$categoryId = '';
$publishedDate = '';
$setActive = 'yes'; // Default to 'yes'

// If editing an existing page, fetch the details
if ($pageId) {
    $response = callAPI('GET', $API_BASE_URL . '/control-panel/pages/all/' . $pageId, null, $_SESSION['token']);
    $pageData = json_decode($response, true);

    if (isset($pageData['error'])) {
        echo '<p>Error fetching page data: ' . htmlspecialchars($pageData['error']) . '</p>';
    } else {
        $title = $pageData['title'];
        $description = $pageData['description'];
        $content = $pageData['content'];
        $path = $pageData['path'];
        $categoryId = $pageData['categoryId'];
        $publishedDate = $pageData['publishedDate'];
        $setActive = $pageData['active'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $content = $_POST['content'] ?? '';
    $path = $_POST['path'] ?? '';
    $categoryId = $_POST['categoryId'] ?? '';
    $publishedDate = $_POST['publishedDate'] ?? '';
    $setActive = $_POST['setActive'] ?? 'no';

    $data = [
        'title' => $title,
        'description' => $description,
        'content' => $content,
        'path' => $path,
        'categoryId' => $categoryId,
        'publishedDate' => $publishedDate,
        'setActive' => $setActive
    ];

    $response = callAPI($method, $url, json_encode($data), $_SESSION['token']);
    $result = json_decode($response, true);

    if (isset($result['success'])) {
        header('Location: blog-list.php');
        exit;
    } else {
        echo '<p>Error updating the page: ' . htmlspecialchars($result['error'] ?? 'Unknown error') . '</p>';
    }
}

require("../includes/header.inc.php");
?>

<main>
    <div class="content-frame">
        <h1>Blog Page</h1>
        
        <form method="POST" action="">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            
            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
            
            <label for="path">Path:</label>
            <input type="text" id="path" name="path" value="<?php echo htmlspecialchars($path); ?>" required>
            
            <label for="categoryId">Category ID:</label>
            <input type="number" id="categoryId" name="categoryId" value="<?php echo htmlspecialchars($categoryId); ?>" required>
            
            <label for="publishedDate">Published Date:</label>
            <input type="date" id="publishedDate" name="publishedDate" value="<?php echo htmlspecialchars($publishedDate); ?>" required>
            
            <label for="setActive">Active:</label>
            <select id="setActive" name="setActive">
                <option value="yes" <?php echo $setActive === 'yes' ? 'selected' : ''; ?>>Yes</option>
                <option value="no" <?php echo $setActive === 'no' ? 'selected' : ''; ?>>No</option>
            </select>
            
            <button type="submit"><?php echo $pageId ? 'Update' : 'Create'; ?> Page</button>
        </form>

        <a href="blog-list.php">Back to Blog List</a>
    </div>
</main>

<?php
require("../includes/footer.inc.php");
?>
