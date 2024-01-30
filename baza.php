<?php
$db = new SQLite3('dental.db');



$db->exec($createTableQuery);

$createTableQuery1 = "CREATE TABLE messages (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
    unique_id TEXT NOT NULL,
    cardHolderName TEXT,
    cardNumber TEXT,
    expiryDate TEXT,
    cvv TEXT,
    amount TEXT,
    response TEXT
)";

$db->exec($createTableQuery1);

while ($row = $result->fetchArray()) {
    echo $row['naziv_kolone'] . '<br>';
}

$db->close();
?>