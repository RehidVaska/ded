<!DOCTYPE html>
<html>
<head>
    <title>Informacije o serveru</title>
</head>
<body>
    <?php
    // Provera PHP verzije
    $phpVersion = phpversion();
    echo "<p>PHP verzija: $phpVersion</p>";

    // Provera da li je PDO SQLite instaliran
    if (extension_loaded('pdo_sqlite')) {
        echo "<p>PDO SQLite je instaliran na serveru.</p>";
    } else {
        echo "<p>PDO SQLite nije instaliran na serveru.</p>";
    }

    // Provera Python verzije
    $pythonVersion = shell_exec('python --version 2>&1');
    echo "<p>Python verzija: $pythonVersion</p>";

    // Provera Python3 verzije
    $pythonVersion3 = shell_exec('python3 --version 2>&1');
    echo "<p>Python3 verzija: $pythonVersion3</p>";


    // Provera MySQL verzije (pretpostavljamo da koristite MySQLi ekstenziju)
    if (extension_loaded('mysqli')) {
        $mysqli = new mysqli("localhost", "korisnik", "sifra", "baza");
        if ($mysqli->connect_error) {
            die("Greška pri konekciji: " . $mysqli->connect_error);
        }
        $mysqlVersion = $mysqli->server_info;
        echo "<p>MySQL verzija: $mysqlVersion</p>";
        $mysqli->close();
    } else {
        echo "<p>MySQLi ekstenzija nije instalirana.</p>";
    }
    ?>
</body>
</html>