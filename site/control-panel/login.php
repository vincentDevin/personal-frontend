<?php
require_once("../includes/config.inc.php");

$pageTitle = "Login";
$pageDescription = "";

// Function to make a POST request to the API
function authenticateUser($username, $password, $recaptchaResponse) {
    $apiUrl = "https://express_api:3000/api/auth/login"; // Adjust the URL as needed

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'username' => $username,
        'password' => $password,
        'g-recaptcha-response' => $recaptchaResponse // Include reCAPTCHA response in the API request
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    logMessage("API URL: {$apiUrl}");
    logMessage("HTTP Code: {$httpcode}");
    logMessage("Response: {$response}");

    if ($httpcode == 200) {
        return json_decode($response, true);
    } else {
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $userNameEntered = $_POST['txtUserName'] ?? NULL;
    $passwordEntered = $_POST['txtPassword'] ?? NULL;
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? NULL;

    if ($userNameEntered && $passwordEntered && $recaptchaResponse) {
        $authResponse = authenticateUser($userNameEntered, $passwordEntered, $recaptchaResponse);

        if ($authResponse && isset($authResponse['token'])) {
            session_regenerate_id(true);
            $_SESSION['authenticated'] = "yes";
            $_SESSION['username'] = $userNameEntered; // Store the username in session
            $_SESSION['token'] = $authResponse['token']; // Store the token in session

            header("Location: index.php");
            exit();
        } else {
            // Handle failed login attempt
            $error = "Invalid username, password, or reCAPTCHA.";
        }
    } else {
        $error = "Please complete all fields and the reCAPTCHA challenge.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    // Destroy the session when user visits login page
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), "", time() - 3600, "/");
    }

    $_SESSION = array();
    session_destroy();
}

require("../includes/header.inc.php");
?>
<main>
    <div class="content-frame">
        <h3>Login</h3>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="login-form-container">
                <label for="txtUserName">User Name</label>
                <br>
                <input type="text" name="txtUserName" id="txtUserName" required />
                <br>
                <label for="txtPassword">Password</label>
                <br>
                <input type="password" name="txtPassword" id="txtPassword" required />
                <br>
                <!-- reCAPTCHA widget -->
                <div class="g-recaptcha" data-sitekey="<?php echo(CAPTCHA_SITE); ?>" data-callback="onRecaptchaSuccess"></div>
                <br>
                <input type="submit" value="Log In">
            </div>
        </form>
    </div>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    // This function is called when the reCAPTCHA is successfully completed
    function onRecaptchaSuccess(token) {
        console.log("reCAPTCHA token:", token);
    }
</script>

<?php
require("../includes/footer.inc.php");
?>
