<?php
// Start a session if one hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load the UserDataSet class which handles login-related database operations
require_once('Models/UserDataSet.php');

// Create a user model instance to check login credentials
$users = new UserDataSet();

// Handle Login Request
if (isset($_POST["loginbutton"])) {
    // Get the submitted username and password,
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // Use the model to validate the credentials
    $isValidLogin = $users->checkUserCredentials($username, $password);

    if ($isValidLogin) {
        // If credentials are correct, go to homepage
        header("Location: index.php");
        exit;
    } else {
        // If credentials are wrong, reload homepage with an error message
        header("Location: index.php?error=1");
        exit;
    }
}

// Handle Logout Request
if (isset($_POST["logoutbutton"])) {
    // Destroy all session data (logs user out)
    session_destroy();

    // Send user back to homepage
    header("Location: index.php");
    exit;
}