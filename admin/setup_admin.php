<?php
require_once __DIR__ . '/../config/database.php';

try {
    $db = getDBConnection();
    
    $username = 'Admin';
    $email    = 'onyok404@gmail.com';
    $password = 'Duqueseanjohna02@@@'; // Change this to your desired password!
    $hashedPw = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare("
        INSERT INTO users (username, email, password) 
        VALUES (:username, :email, :password)
        ON DUPLICATE KEY UPDATE password = :password_update
    ");

    $stmt->execute([
        ':username'        => $username,
        ':email'           => $email,
        ':password'        => $hashedPw,
        ':password_update' => $hashedPw
    ]);

    echo "<h3 style='color: #10B981; font-family: monospace;'>Admin user successfully created/updated!</h3>";
    echo "<p style='font-family: monospace;'>Username: <strong>admin</strong><br>Password: <strong>$password</strong></p>";
    echo "<p style='color: #EF4444;'><strong>Important:</strong> Delete or secure this setup_admin.php file after running!</p>";

} catch (Exception $e) {
    exit("Setup Error: " . $e->getMessage());
}