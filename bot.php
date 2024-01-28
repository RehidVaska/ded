<?php
$botToken= '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';
$apiUrl = "https://api.telegram.org/bot" . $botToken;
$content = file_get_contents("php://input");
$update = json_decode($content, TRUE);

if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $callbackData = $callbackQuery["data"];
    $callbackChatId = $callbackQuery["message"]["chat"]["id"];
    $uniqueId = getSessionData($callbackChatId);

    if ($callbackData == 'odgovor_' . $uniqueId) {
        $responseMessage = "Odgovor za uniqueId: " . $uniqueId;
        file_get_contents($apiUrl . "/sendMessage?chat_id=" . $callbackChatId . "&text=" . urlencode($responseMessage));
    }
    file_get_contents($apiUrl . "/answerCallbackQuery?callback_query_id=" . $callbackQuery["id"]);
}
function getSessionData($chatId) {
    $sessionFile = "session_data_" . $chatId . ".txt";
    if (file_exists($sessionFile)) {
        return file_get_contents($sessionFile);
    }
    return null;
}
function saveSessionData($chatId, $data) {
    $sessionFile = "session_data_" . $chatId . ".txt";
    file_put_contents($sessionFile, $data);
}
?>
