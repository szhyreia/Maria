<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "    PHP CRUD SYSTEM    \n";

// add data on db
echo "[OPERATION 1: RECORD CREATION]\n";
$newUser = User::create([
    'name' => 'Divine Macavodvod',
    'email' => 'divine@ugmail.com',
    'password' => 'hello'
]);
echo "   Created user No: {$newUser->id}\n";

// show
echo "[OPERATION 2: RECORD RETRIEVAL]\n";
$retrievedUser = User::find($newUser->id);
echo "Found {$retrievedUser->name} with email: {$retrievedUser->email} and password {$retrievedUser->password}\n";

// edit
echo "[OPERATION 3: PROFILE UPDATE]\n";
$retrievedUser->update([
    'name' => 'Divine Chen',
    'email' => 'd.chen@gmail.com'
]);
echo "User profile updated\n";
echo "New name: {$retrievedUser->name}\n";
echo "New contact: {$retrievedUser->email}\n\n"; 

// delete 
/*echo "[OPERATION 4: RECORD REMOVAL]\n";
$userId = $retrievedUser->id;
$retrievedUser->delete();
echo "User record has been permanently removed from the system\n";
echo "Database entry #{$newUser->id} cleared\n\n";
*/

// check if deleted
echo "[OPERATION 5: SYSTEM VERIFICATION]\n";
$verificationCheck = User::find($newUser->id);
if ($verificationCheck) {
    echo "VERIFICATION: Record still exists in database\n";
} else {
    echo "VERIFICATION: No record found for ID #{$newUser->id}\n";
}
