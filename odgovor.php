<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odgovor na Telegram poruku</title>
</head>
<body>
    <div>
        <h1>Odgovor na Telegram poruku</h1>
        <p>
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] === 'SMS') {
                    echo "POSLAT JE SMS";
                } elseif ($_GET['status'] === 'Reject') {
                    echo "REJECT REJECT";
                }
            } else {
                echo "Nema dostupnih informacija o akciji korisnika.";
            }
            ?>
        </p>
    </div>
</body>
</html>
