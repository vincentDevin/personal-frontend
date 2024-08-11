<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to verify the token with the API
function verifyToken($token) {
    $apiUrl = "https://devin-vincent.com/api/auth/verify"; // Adjust the URL as needed

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['token' => $token]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        return json_decode($response, true);
    } else {
        return null;
    }
}

if (!isset($_SESSION['token'])) {
    header("Location: login.php");
    exit();
}

$token = $_SESSION['token'];
$authResponse = verifyToken($token);

if (!$authResponse || !$authResponse['valid']) {
    header("Location: login.php");
    exit();
}
?>
