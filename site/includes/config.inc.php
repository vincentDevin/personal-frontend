<?php

// Load environment variables from .env file in the root directory
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = parse_ini_file(__DIR__ . '/../.env');
    foreach ($dotenv as $key => $value) {
        putenv("$key=$value");
    }
}

// Determine if the connection is secure (HTTPS)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;

// Set session security settings before starting the session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// Start the session
session_start([
    'cookie_lifetime' => 0, // Session cookie expires when the browser closes
    'cookie_secure' => $isSecure, // Use HTTPS
    'cookie_httponly' => true, // Prevent JavaScript access to the session cookie
    'cookie_samesite' => 'Lax', // Adjust this if needed
    'use_strict_mode' => true,
]);

// API Base URL
define('API_BASE_URL', 'https://devin-vincent.com/api');

// Detect if the code is running on localhost or live server
if ($_SERVER['SERVER_NAME'] == "localhost") {
    // Settings for dev environment
    define("PROJECT_DIR", "/");
    define("DEBUG_MODE", true);
    define("ADMIN_EMAIL", "vincentdevin111@gmail.com");
} else {
    // Settings for live site
    define("PROJECT_DIR", "/");
    define("DEBUG_MODE", false); // Disable debug mode for production
    define("ADMIN_EMAIL", "vincentdevin111@gmail.com");

    // Secure CAPTCHA keys for production from .env file
    define("CAPTCHA_SECRET", getenv('CAPTCHA_SECRET'));
    define("CAPTCHA_SITE", getenv('CAPTCHA_SITE'));
}

// Set custom error handler
set_error_handler("customErrorHandler");

// In debug mode, all errors are displayed in the browser
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorMsg = "Error: [$errno] $errstr - $errfile:$errline";
    // Only attempt to log if DEBUG_MODE is on
    if (DEBUG_MODE) {
        $logPath = __DIR__ . '/../logs/error.log';
        
        // Check if the directory exists and is writable
        if (is_writable(dirname($logPath)) || (!file_exists($logPath) && mkdir(dirname($logPath), 0777, true))) {
            error_log($errorMsg, 3, $logPath);
        }

        // Display error in debug mode
        echo "<b>Error:</b> [$errno] $errstr - $errfile:$errline<br>";
    } else {
        // Redirect to a generic error page in production
        header("Location: " . PROJECT_DIR . "error.php");
        exit();
    }
}

// Wrapper function for sending emails
function sendEmail($to, $subject, $msg, $headers = "") {
    return mail($to, $subject, $msg, $headers);
}

// Function to store JWT token securely
function storeToken($token) {
    $_SESSION['token'] = $token; // Store token in session
}

// Function to retrieve JWT token
function getToken() {
    return $_SESSION['token'] ?? null;
}

// Global function to call an API with optional JWT token
function callAPI($method, $url, $data = false, $token = null) {
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default: // GET
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    // Optional Authentication
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    } elseif ($sessionToken = getToken()) {
        $headers[] = 'Authorization: Bearer ' . $sessionToken;
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return ['status_code' => $httpcode, 'response' => json_decode($result, true)];
}

// Redirect to 404 page
function redirectTo404Page() {
    header("HTTP/1.0 404 Not Found");
    header("Location: " . PROJECT_DIR . "404.php");
    exit();
}

// Sanitize HTML
function sanitizeHtml($inputHTML) {
    $allowed_tags = array('<sub>', '<sup>', '<div>', '<span>', '<h1>', '<h2>', '<br>', '<h3>', '<h4>', '<h5>', '<h6>', '<i>', '<b>', '<a>', '<ul>', '<ol>', '<em>', '<li>', '<pre>', '<hr>', '<blockquote>', '<p>', '<img>', '<strong>', '<code>');
    $bad_attributes = array('onerror', 'onmousemove', 'onmouseout', 'onmouseover', 'onkeypress', 'onkeydown', 'onkeyup', 'onclick', 'onchange', 'onload', 'javascript:');
    
    $inputHTML = str_replace($bad_attributes, "x", $inputHTML);
    $allowed_tags = implode('', $allowed_tags);
    $inputHTML = strip_tags($inputHTML, $allowed_tags);
    
    return $inputHTML;
}

// Function to log debug messages
function logMessage($message) {
    if (DEBUG_MODE) { // Only log in debug mode
        $logfile = __DIR__ . '/../logs/debug.log';
        file_put_contents($logfile, $message . PHP_EOL, FILE_APPEND);
    }
}

?>
