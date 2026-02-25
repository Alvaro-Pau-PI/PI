<?php
$user = App\Models\User::where('email', 'admin@admin.test')->first();
echo "User exists: " . ($user ? "Yes" : "No") . "\n";
if ($user) {
    echo "Password Hash: " . $user->password . "\n";
    echo "Check 'password': " . (Hash::check('password', $user->password) ? "Yes" : "No") . "\n";
    echo "Check '........': " . (Hash::check('........', $user->password) ? "Yes" : "No") . "\n";
}
