<?php
require_once("../includes/config.inc.php");
require("authentication-check.inc.php");

$pageTitle = "Contact Submissions";
$pageDescription = "";

// Securely retrieve the token from the session
$token = getToken();

// Fetch contact submissions from the API using the global API call function
$response = callAPI('GET', API_BASE_URL . '/contacts', false, $token);

// Check for errors in the API response
if ($response['status_code'] == 200 && !empty($response['response'])) {
    $contacts = $response['response'];
} else {
    $contacts = [];
    echo "<p class='error'>Failed to fetch contact submissions. HTTP Status Code: {$response['status_code']}</p>";
}

function displayContacts($contacts) {
    // Start table
    $html = "<table border=\"1\">";

    // Column headers
    $html .= "<tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Comments</th>
                <th>Date Submitted</th>
             </tr>";

    // Table rows
    foreach($contacts as $contact) {
        $html .= "<tr>";
        $html .= "<td>" . htmlspecialchars($contact['id']) . "</td>";
        $html .= "<td>" . htmlspecialchars($contact['firstName']) . "</td>";
        $html .= "<td>" . htmlspecialchars($contact['lastName']) . "</td>";
        $html .= "<td>" . htmlspecialchars($contact['email']) . "</td>";
        $html .= "<td>" . htmlspecialchars($contact['comments']) . "</td>";
        $html .= "<td>" . htmlspecialchars($contact['created_at']) . "</td>";
        $html .= "</tr>";
    }

    $html .= "</table>";

    return $html;
}

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame">
        <h3>Contact Submissions</h3>
        <?php
        if (!empty($contacts)) {
            echo displayContacts($contacts);
        } else {
            echo "<p>No contact submissions found.</p>";
        }
        ?>
    </div>
</main>

<?php
require("../includes/footer.inc.php");
?>
