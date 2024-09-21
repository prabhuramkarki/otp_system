<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Verify OTP</h1>
        <form method="post" action="sendotp.php">
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-key"></i>
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>
            </div>
            <button type="submit" class="btn">Verify OTP</button>
            <a href="sendotp.php?resend=true" class="resend-link">Resend OTP</a>
        </form>
    </div>
</body>
</html>
