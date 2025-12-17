<?php
/**
 * Payment Details Encryption Helper
 * Uses AES-256-CBC encryption for secure payment data storage
 * Author: Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586
 */

// Encryption key - In production, store this in environment variables or secure config
// DO NOT commit real keys to version control
define('ENCRYPTION_KEY', hash('sha256', 'KXO205_SecureKey_2025_ChangeInProduction'));
define('ENCRYPTION_METHOD', 'AES-256-CBC');

/**
 * Encrypt payment details
 * @param string $data Payment details to encrypt
 * @return string Encrypted data with IV prepended (base64 encoded)
 */
function encryptPaymentDetails($data) {
    if (empty($data)) {
        return '';
    }
    
    // Generate a random initialization vector
    $iv_length = openssl_cipher_iv_length(ENCRYPTION_METHOD);
    $iv = openssl_random_pseudo_bytes($iv_length);
    
    // Encrypt the data
    $encrypted = openssl_encrypt(
        $data,
        ENCRYPTION_METHOD,
        ENCRYPTION_KEY,
        0,
        $iv
    );
    
    // Prepend IV to encrypted data and encode
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt payment details
 * @param string $encrypted_data Encrypted payment details (base64 encoded with IV)
 * @return string|false Decrypted data or false on failure
 */
function decryptPaymentDetails($encrypted_data) {
    if (empty($encrypted_data)) {
        return '';
    }
    
    // Decode the base64 data
    $data = base64_decode($encrypted_data);
    if ($data === false) {
        return false;
    }
    
    // Extract IV from the beginning
    $iv_length = openssl_cipher_iv_length(ENCRYPTION_METHOD);
    $iv = substr($data, 0, $iv_length);
    $encrypted = substr($data, $iv_length);
    
    // Decrypt the data
    $decrypted = openssl_decrypt(
        $encrypted,
        ENCRYPTION_METHOD,
        ENCRYPTION_KEY,
        0,
        $iv
    );
    
    return $decrypted;
}

/**
 * Create payment details string with transaction info
 * @param string $method Payment method (e.g., 'Credit Card', 'PayPal', 'Bank Transfer')
 * @param string $last4 Last 4 digits of card/account (optional)
 * @param string $transaction_id Transaction ID from payment gateway (optional)
 * @return string Formatted payment details string
 */
function createPaymentDetails($method = 'Online Payment', $last4 = '', $transaction_id = '') {
    $timestamp = date('Y-m-d H:i:s');
    $details = "Payment Method: $method | Timestamp: $timestamp";
    
    if (!empty($last4)) {
        $details .= " | Last 4: $last4";
    }
    
    if (!empty($transaction_id)) {
        $details .= " | Transaction ID: $transaction_id";
    }
    
    return $details;
}
