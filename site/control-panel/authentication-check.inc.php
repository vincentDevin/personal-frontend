<?php
require_once("../includes/config.inc.php"); // Include the configuration file

// Function to verify the token with the API
function verifyToken($token) {
    $apiUrl = API_BASE_URL . "/auth/verify"; // Use the defined constant for the API URL

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['token' => $token]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set a reasonable timeout for the request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // If there's a cURL error, log it or handle it appropriately
        error_log('cURL error: ' . curl_error($ch));
        curl_close($ch);
        return null;
    }

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        return json_decode($response, true);
    } else {
        return null;
    }
}

// Retrieve the token securely
$token = $_SESSION['token'] ?? null;

if (!$token) {
    header("Location: login.php");
    exit();
}

$authResponse = verifyToken($token);

if (!$authResponse || !$authResponse['valid']) {
    // If the token is invalid or the API verification fails, log out the user
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
