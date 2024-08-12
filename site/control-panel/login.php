<?php
require_once("../includes/config.inc.php"); // Include the configuration file

// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Login";
$pageDescription = "";

// Function to make a POST request to the API
function authenticateUser($username, $password, $recaptchaResponse) {
    $apiUrl = API_BASE_URL . "/auth/login"; // Use the defined constant for the API URL

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

// Function to store the JWT token in a session variable
function storeToken($token) {
    $_SESSION['token'] = $token; // Store the token in the session
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $userNameEntered = $_POST['txtUserName'] ?? null;
    $passwordEntered = $_POST['txtPassword'] ?? null;
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? null;

    if ($userNameEntered && $passwordEntered && $recaptchaResponse) {
        $authResponse = authenticateUser($userNameEntered, $passwordEntered, $recaptchaResponse);

        if ($authResponse && isset($authResponse['token'])) {
            //session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
            $_SESSION['authenticated'] = "yes";
            $_SESSION['username'] = $userNameEntered; // Store the username in session
            
            // Store the token using the secure storage function
            storeToken($authResponse['token']);

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
    // Destroy the session when user visits the login page
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
                <div class="g-recaptcha" data-sitekey="<?php echo(CAPTCHA_SITE); ?>"></div>
                <br>
                <input type="submit" value="Log In">
            </div>
        </form>
    </div>
</main>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php
require("../includes/footer.inc.php");
?>
