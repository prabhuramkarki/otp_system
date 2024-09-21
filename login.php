
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/style.css">
    <style>

    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form method="post" action="sendotp.php">
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-envelope"></i>
                    <input type="text" id="email" name="email" placeholder="Email" required>
                </div>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't Have an Account? <a href="register.php">SIGNUP</a></p>
    </div>
</body>
</html>
