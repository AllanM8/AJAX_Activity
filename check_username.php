<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $action = $_POST['action'] ?? 'check'; // Check if this is just a "check" or "register" action
    $xmlFile = 'usernames.xml';

    // Load the XML file and check availability
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<users></users>');
    }

    $isAvailable = true;
    foreach ($xml->username as $username) {
        if ((string)$username == $inputUsername) {
            $isAvailable = false;
            break;
        }
    }

    if ($action === 'check') {
        // Just check availability and return result
        echo $isAvailable ? 'available' : 'taken';
    } elseif ($action === 'register' && $isAvailable) {
        // Register the new username if it's available
        $newUsername = $xml->addChild('username', $inputUsername);
        $xml->asXML($xmlFile);  // Save the updated XML file
        echo 'registered';
    } else {
        // Username was already taken if we reached here
        echo 'taken';
    }
}
?>
