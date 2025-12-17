<?php
/**
 * CSRF Protection Helper Functions
 * Generates and validates CSRF tokens for form submissions
 */

/**
 * Generate a CSRF token and store it in the session
 * @return string The generated CSRF token
 */
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate the CSRF token from form submission
 * @param string $token The token to validate
 * @return bool True if valid, false otherwise
 */
function validateCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Output a hidden CSRF token input field for forms
 */
function csrfTokenField() {
    $token = generateCsrfToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Verify CSRF token and die with error message if invalid
 * @param string $errorMessage Custom error message (optional)
 */
function verifyCsrfToken($errorMessage = "Invalid CSRF token. Please try again.") {
    $token = $_POST['csrf_token'] ?? '';
    
    if (!validateCsrfToken($token)) {
        http_response_code(403);
        die($errorMessage);
    }
}
