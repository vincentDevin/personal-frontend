<?php
require_once("includes/config.inc.php");
$pageTitle = "Contact";
$pageDescription = "This page will allow you to contact me!";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Honeypot field - should be left empty by real users
    $honeypot = $_POST['gender'] ?? "";
    if (!empty($honeypot)) {
        // If the honeypot field is filled, it's likely a bot submission
        header("Location: " . PROJECT_DIR . "error.php");
        exit();
    }

    // Get the data entered by the user
    $firstName = $_POST['txtFirstName'] ?? "";
    $lastName = $_POST['txtLastName'] ?? "";
    $email = $_POST['txtEmail'] ?? "";
    $comments = $_POST['txtComments'] ?? "";

    // Simple spam keyword filtering
    $spamKeywords = ['viagra', 'free money', 'click here', 'buy now'];
    foreach ($spamKeywords as $keyword) {
        if (stripos($comments, $keyword) !== false) {
            // If spam keywords are found, log and block the submission
            sendEmail(ADMIN_EMAIL, "Spam detected", "Spam submission blocked: " . $comments);
            header("Location: " . PROJECT_DIR . "error.php");
            exit();
        }
    }

    if (validateContactData($firstName, $lastName, $email, $comments)) {
        // Prepare data for API call
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'comments' => $comments
        ];

        // Make API call to save the contact form data
        $url = API_BASE_URL . "/contact";
        $response = callAPI('POST', $url, $data);

        if ($response['status_code'] == 200) {
            // Redirect to the confirmation page on success
            header("Location: " . PROJECT_DIR . "contact-confirmation.php");
            exit();
        } else {
            // Handle API errors
            $msg = "Failed to save contact data. HTTP Status Code: {$response['status_code']}";
            sendEmail(ADMIN_EMAIL, "API Error", $msg);
            header("Location: " . PROJECT_DIR . "error.php");
            exit();
        }
    } else {
        // Handle validation errors
        $msg = getAllSuperGlobals();
        sendEmail(ADMIN_EMAIL, "Security Warning!", $msg);
        header("Location: " . PROJECT_DIR . "error.php");
        exit();
    }
}

require("includes/header.inc.php");
?>
<script type="text/javascript" src="<?php echo(PROJECT_DIR); ?>js/contact-form.js"></script>
<main>
    <div class="content-frame-contact">
        <h1>Contact Me</h1>

        <div class="form-container">
            <form id="contactForm" method="POST" action="">
                <div class="form-validation-messages">
                    <div class="validation-message" id="vFirstName"></div>
                    <div class="validation-message" id="vLastName"></div>
                    <div class="validation-message" id="vEmail"></div>
                </div>
                <div class="contact-form-info">
                    <input type="text" id="txtFirstName" name="txtFirstName" placeholder="First Name">
                    <input type="text" id="txtLastName" name="txtLastName" placeholder="Last Name">
                    <input type="text" id="txtEmail" name="txtEmail" placeholder="youremail@email.com">
                    <input type="text" id="gender" name="gender" style="display:none;">
                </div>
                <div class="validation-message" id="vComments"></div>
                <div class="contact-form-comments">
                    <textarea id="txtComments" name="txtComments" placeholder="Type your comments here!"></textarea>
                </div>
                <div class="contact-form-comments">
                    <div class="g-recaptcha" data-sitekey="<?php echo(CAPTCHA_SITE); ?>"></div>
                </div>
                <div class="contact-form-submit-button">
                    <input type="submit" value="SUBMIT">
                </div>
            </form>
        </div>
    </div>
</main>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php

require("includes/footer.inc.php");

function validateContactData($firstName, $lastName, $email, $comments) {
    // Make sure that none of the inputs are empty
    if (empty($firstName) || empty($lastName) || empty($comments) || empty($email)) {
        return false;
    }

    // Make sure the email entered is a valid one
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }

    // Optionally, check for too many repeated characters (common in spam)
    if (preg_match('/(.)\\1{4,}/', $comments)) {
        return false;
    }

    return true;
}
?>
