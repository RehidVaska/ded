<?php
// Inicijalizacija SQLite baze
try {
    $db = new PDO('sqlite:dental.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS statuses (
        id INTEGER PRIMARY KEY,
        user_id INTEGER,
        status TEXT,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    error_log("Greška pri konekciji na bazu: " . $e->getMessage());
    exit('Greška pri konekciji na bazu.');
}

if (!isset($_GET['token'])) {
    die("Nevažeći token!");
}

$token = $_GET['token'];
// Provera sigurnosnog tokena
$secretToken = 'vaš_secret_token';
if ($_GET['token'] !== $secretToken) {
    exit('Nevažeći token!');
}

// Obrada callback query-a
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $queryData = $callbackQuery["data"];
    $chatId = $callbackQuery["message"]["chat"]["id"];

    try {
        $stmt = $db->prepare("INSERT INTO statuses (user_id, status) VALUES (:user_id, :status)");
        $stmt->bindValue(':user_id', $chatId, PDO::PARAM_INT);
        $stmt->bindValue(':status', $queryData, PDO::PARAM_STR);
        $stmt->execute();

        // Slanje odgovora nazad na Telegram bot
        sendTelegramResponse($chatId, "Status uspešno sačuvan.");
    } catch (PDOException $e) {
        error_log("Greška pri upisu u bazu: " . $e->getMessage());
    }
}

// Funkcija za slanje odgovora na Telegram bot
function sendTelegramResponse($chatId, $message) {
    $botToken = 'vaš_bot_token';
    $response = file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=".urlencode($message));
    // Logovanje odgovora (opciono)
}
?>
