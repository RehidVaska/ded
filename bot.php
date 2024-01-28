<?php
$botToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$apiUrl = "https://api.telegram.org/bot" . $botToken;
$content = file_get_contents("php://input");
$update = json_decode($content, TRUE);

// Obrada callback upita
if (isset($update["callback_query"])) {
    handleCallbackQuery($update["callback_query"], $apiUrl);
}

// Funkcija za obradu callback upita
function handleCallbackQuery($callbackQuery, $apiUrl) {
    $callbackData = $callbackQuery["data"];
    $callbackChatId = $callbackQuery["message"]["chat"]["id"];

    if (strpos($callbackData, 'SMS_') === 0) {
        $uniqueId = substr($callbackData, 4);
        updateStatus($uniqueId, 'SMS');
        $responseMessage = "Primljen SMS za ID: " . $uniqueId;
        file_get_contents($apiUrl . "/sendMessage?chat_id=" . $callbackChatId . "&text=" . urlencode($responseMessage));
        header('Location: dva.php');
    }
}

// Funkcija za ažuriranje statusa
function updateStatus($uniqueId, $status) {
    $file = 'callback_status.txt';
    $data = "Unique ID: $uniqueId, Status: $status\n";
    file_put_contents($file, $data, FILE_APPEND);
}

// Funkcija za slanje poruke sa inline tasterima
function sendMessageWithInlineKeyboard($chatId, $text, $keyboard, $apiUrl) {
    $postData = [
        'chat_id' => $chatId,
        'text' => $text,
        'reply_markup' => json_encode(['inline_keyboard' => $keyboard])
    ];

    file_get_contents($apiUrl . "/sendMessage?" . http_build_query($postData));
}

// Primer kako pozvati funkciju (potrebno prilagoditi)
// sendMessageWithInlineKeyboard($chatId, "Poruka sa tasterima", [[['text' => 'Taster 1', 'callback_data' => 'Data1']]], $apiUrl);
?>
