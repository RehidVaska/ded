<?php
// Konfiguracija
$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U'; // Vaš Telegram bot token
$secretToken = 'aspfhOhUrC8RcaeI2io6gGeiE180dpSmAW6oCeGrXRvPzobd9EJAeDqJLkZcHMQT'; // Vaš secret token za sigurnosnu proveru

// Provera sigurnosnog tokena
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    http_response_code(401);
    exit('Nevažeći token!');
}

// Povezivanje sa bazom podataka
try {
    $db = new PDO('sqlite:dental.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Greška pri konekciji na bazu: " . $e->getMessage());
    exit('Greška pri konekciji na bazu.');
}

// Obrada dolaznog webhook-a
$content = file_get_contents("php://input");
$update = json_decode($content, true);
error_log("Webhook content: " . print_r($update, true)); // Logovanje dolaznih podataka

// Obrada callback query-a
if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $queryData = $callbackQuery["data"];
    $uniqueId = $callbackQuery["message"]["text"];

    // Ekstrakcija uniqueId-a iz teksta poruke
    preg_match('/ID: (\w+)/', $uniqueId, $matches);
    if ($matches) {
        $uniqueId = $matches[1];
        error_log("Extracted Unique ID: $uniqueId");

        // Ažuriranje odgovora u tabeli messages
        try {
            $stmt = $db->prepare("UPDATE messages SET response = :response WHERE unique_id = :unique_id");
            $stmt->bindValue(':response', $queryData, PDO::PARAM_STR);
            $stmt->bindValue(':unique_id', $uniqueId, PDO::PARAM_STR);
            $stmt->execute();

            error_log("Response updated for Unique ID: $uniqueId");
            sendTelegramResponse($callbackQuery["message"]["chat"]["id"], "Vaš odgovor je sačuvan.", $telegramBotToken);
        } catch (PDOException $e) {
            error_log("Greška pri ažuriranju baze: " . $e->getMessage());
        }
    } else {
        error_log("Unique ID nije pronađen u poruci.");
    }
}

// Funkcija za slanje odgovora na Telegram bot
function sendTelegramResponse($chatId, $message, $botToken) {
    $response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=".urlencode($message));
    error_log("Sent response to Telegram: $response");
}
?>