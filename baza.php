<?php
$db = new SQLite3('moja_baza.db');

// Stvaranje tablice ako ne postoji
$createTableQuery = "CREATE TABLE IF NOT EXISTS moja_tablica (
    id INTEGER PRIMARY KEY,
    naziv_kolone TEXT
)";

$db->exec($createTableQuery);

// Izvršavanje SELECT upita
$result = $db->query('SELECT * FROM moja_tablica');

while ($row = $result->fetchArray()) {
    echo $row['naziv_kolone'] . '<br>';
}

$db->close();
?>