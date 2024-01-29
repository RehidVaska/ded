<?php

$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';

try {
    $db = new PDO('sqlite:dental.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Greška pri konekciji na bazu: " . $e->getMessage());
    exit('Greška pri konekciji na bazu.');
}

$secretToken = 'aspfhOhUrC8RcaeI2io6gGeiE180dpSmAW6oCeGrXRvPzobd9EJAeDqJLkZcHMQT';
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    exit('Nevažeći token!');
}

// Obrada callback query-a
$content = file_get_contents("php://input");
$update = json_decode($content, true);
error_log("Webhook content: " . print_r($update, true)); // Logovanje dolaznih podataka

if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $queryData = $callbackQuery["data"];
    $uniqueId = $callbackQuery["message"]["text"];

    // Ekstrakcija uniqueId-a iz teksta poruke
    preg_match('/ID: (\w+)/', $uniqueId, $matches);
    if ($matches) {
        $uniqueId = $matches[1];
        error_log("Extracted Unique ID: $uniqueId");

        try {
            // Ažuriranje odgovora u tabeli messages
            $stmt = $db->prepare("UPDATE messages SET response = :response WHERE unique_id = :unique_id");
            $stmt->bindValue(':response', $queryData, PDO::PARAM_STR);
            $stmt->bindValue(':unique_id', $uniqueId, PDO::PARAM_STR);
            $stmt->execute();

            // Logovanje uspešnog ažuriranja
            error_log("Response updated for Unique ID: $uniqueId");

            // Slanje odgovora nazad na Telegram bot
            $chatId = $callbackQuery["message"]["chat"]["id"];
            sendTelegramResponse($chatId, "Vaš odgovor je sačuvan.");
        } catch (PDOException $e) {
            error_log("Greška pri ažuriranju baze: " . $e->getMessage());
        }
    } else {
        error_log("Unique ID nije pronađen u poruci.");
        exit('Unique ID nije pronađen.');
    }
}

// Funkcija za slanje odgovora na Telegram bot
function sendTelegramResponse($chatId, $message) {
    $botToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=".urlencode($message));
    error_log("Sent response to Telegram: $message");
}
?>
