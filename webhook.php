<?php
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (isset($update["callback_query"])) {
    $callbackQuery = $update["callback_query"];
    $queryData = $callbackQuery["data"];
    $chatId = $callbackQuery["message"]["chat"]["id"];

    // Logika za obradu callback query-jeva
    // Na primer, snimite odgovor u bazu podataka ili neki privremeni sistem za skladištenje
}
?>
