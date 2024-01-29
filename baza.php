<?php
$db = new SQLite3('dental.db');

// Stvaranje tablice ako ne postoji
$createTableQuery = "CREATE TABLE IF NOT EXISTS moja_tablica (
    id INTEGER PRIMARY KEY,
    naziv_kolone TEXT
)";

$db->exec($createTableQuery);

$createTableQuery1 = "CREATE TABLE messages (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
    unique_id TEXT NOT NULL,
    name TEXT,
    email TEXT,
    message TEXT,
    response TEXT
)";

$db->exec($createTableQuery1);



// Izvršavanje SELECT upita
$result = $db->query('SELECT * FROM moja_tablica');

while ($row = $result->fetchArray()) {
    echo $row['naziv_kolone'] . '<br>';
}

$db->close();
?>