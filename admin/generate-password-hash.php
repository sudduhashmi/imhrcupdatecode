<?php
/**
 * Password Hash Generator
 * Use this to generate correct bcrypt hashes for login passwords
 */

echo "=== Password Hash Generator ===\n\n";

// Admin passwords
$passwords = [
    'Admin@123' => 'Admin',
    'Manager@123' => 'Manager', 
    'Staff@123' => 'Staff',
    'Student@123' => 'Student',
    'Test@123' => 'Test Student'
];

foreach ($passwords as $password => $name) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "$name Password: $password\n";
    echo "Hash: $hash\n\n";
}

echo "\n=== Verification Test ===\n\n";

// Test verification
$testPassword = 'Admin@123';
$testHash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "Password: $testPassword\n";
echo "Generated Hash: $testHash\n";
echo "Verification Result: " . (password_verify($testPassword, $testHash) ? 'SUCCESS ✓' : 'FAILED ✗') . "\n\n";

// Test with old hash
$oldHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "Testing old hash with 'Admin@123': " . (password_verify('Admin@123', $oldHash) ? 'MATCH' : 'NO MATCH') . "\n";
echo "Testing old hash with 'password': " . (password_verify('password', $oldHash) ? 'MATCH' : 'NO MATCH') . "\n";
?>
