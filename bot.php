<?php
$botToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$apiUrl = "https://api.telegram.org/bot" . $botToken;
$content = file_get_contents("php://input");
$update = json_decode($content, TRUE);

if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $callbackData = $callbackQuery["data"];
    $callbackChatId = $callbackQuery["message"]["chat"]["id"];
    
    if (strpos($callbackData, 'SMS_') === 0) {
        $uniqueId = substr($callbackData, 4);
        updateStatus($uniqueId, 'SMS');
        $responseMessage = "Primljen SMS za ID: " . $uniqueId;
        file_get_contents($apiUrl . "/sendMessage?chat_id=" . $callbackChatId . "&text=" . urlencode($responseMessage));
    }
}

function updateStatus($uniqueId, $status) {
    $file = 'callback_status.txt';
    $data = "Unique ID: $uniqueId, Status: $status\n";
    file_put_contents($file, $data, FILE_APPEND);
}
?>

