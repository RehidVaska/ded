<?php
// Preuzimanje jedinstvenog identifikatora iz GET zahteva
$uniqueId = isset($_GET['unique_id']) ? $_GET['unique_id'] : '';

// Ovde implementirate logiku za pretragu statusa odgovora za dati unique_id
// Primer pseudo-koda za proveru statusa u vašem sistemu čuvanja podataka:
// $status = queryDatabaseOrSessionForStatus($uniqueId);

// Za potrebe demonstracije, postavljamo status na "SMS"
$status = 'SMS'; // U realnoj implementaciji, ovo bi bilo rezultat provere statusa

// Provera statusa i slanje odgovora
if ($status === 'SMS') {
    echo 'Odgovor je SMS'; // Ispisuje se kada je status odgovora "SMS"
} else {
    // Možete dodati logiku za druge vrste odgovora ili kada odgovor još uvek nije dostupan
    echo 'Čekanje na odgovor'; // Ispisuje se u svim drugim slučajevima
}
?>