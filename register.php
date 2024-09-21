<?php
include 'db_connection.php';

if (isset($_POST['userName'], $_POST['userEmail'], $_POST['userPassword'], $_POST['confirmPassword'])) {
    $nameField = trim($_POST['userName']);
    $emailField = trim($_POST['userEmail']);
    $passwordField = trim($_POST['userPassword']);
    $confirmPasswordField = trim($_POST['confirmPassword']);

    if (empty($nameField) || empty($emailField) || empty($passwordField) || empty($confirmPasswordField)) {
        echo "<script>alert('Please fill out all fields.'); window.location.href='register.php';</script>";
        exit();
    }

    if (!filter_var($emailField, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='register.php';</script>";
        exit();
    }

    if ($passwordField !== $confirmPasswordField) {
        echo "<script>alert('Passwords do not match.'); window.location.href='register.php';</script>";
        exit();
    }

    if (!checkPasswordStrength($passwordField)) {
        echo "<script>alert('Password is too weak. It should be at least 8 characters long and include a mix of letters, numbers, and symbols.'); window.location.href='register.php';</script>";
        exit();
    }

    $emailCheckQuery = "SELECT id FROM tbl_users WHERE email='$emailField'";
    $queryResult = $db_connection->query($emailCheckQuery);

    if ($queryResult->num_rows > 0) {
        echo "<script>alert('This email is already registered. Please use a different email or login.'); window.location.href='register.php';</script>";
        exit();
    }

    $hashedPassword = password_hash($passwordField, PASSWORD_BCRYPT);

    $insertQuery = "INSERT INTO tbl_users (name, email, password) VALUES ('$nameField', '$emailField', '$hashedPassword')";

    if ($db_connection->query($insertQuery) === TRUE) {
        echo "<script>alert('New record created successfully.'); window.location.href='register.php';</script>";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $db_connection->error;
    }

    $db_connection->close();
}

function checkPasswordStrength($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/\d/', $password) &&
           preg_match('/[@$!%*?&#]/', $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
        }

        body, html {
            height: 100%;
            margin: 0;
            background: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1, h2 {
            font-family: "Montserrat", sans-serif;
            color: #333;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bolder;
        }

        .form-container, .profile-container {
            width: 500px;
            padding: 70px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .profile-container {
            padding: 70px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 15px;
            color: #666;
        }

        .input-container {
            position: relative;
            width: 100%;
        }

        .input-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #666;
        }

        .input-container .eye-icon {
            position: absolute;
    margin-left: 80%;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #666;
    cursor: pointer;
        }

        input {
            width: 100%;
            padding: 15px 40px;
            font-size: 15px;
            color: #666;
            background: #e6e6e6;
            border: none;
            border-radius: 25px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        input:focus {
            outline: none;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .strength-bar {
            width: 100%;
            height: 5px;
            background: #e6e6e6;
            border-radius: 25px;
            margin-top: 10px;
        }

        .strength-bar-fill {
            height: 5px;
            border-radius: 25px;
            width: 0;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 15px;
            font-size: 15px;
            color: #fff;
            background: #3b7ea8;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.3s ease;
            text-decoration: none;
        }

        .btn:hover {
            background: #494949;
        }

        p {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }

        a {
            color: #3b7ea8;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .resend-link {
            display: block;
            margin-top: 20px;
            color: #3b7ea8;
            text-decoration: none;
            font-weight: bold;
        }

        .resend-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-container, .profile-container {
                padding: 20px;
            }

            h1, h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form method="post">
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" id="userName" name="userName" placeholder="Full name">
                </div>
            </div>
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="userEmail" name="userEmail" placeholder="Email">
                </div>
            </div>
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="userPassword" name="userPassword" placeholder="Enter Password" oninput="checkPasswordStrength()">
                    <i class="fas fa-eye eye-icon" onclick="togglePassword('userPassword')"></i>
                </div>
                <div class="strength-bar">
                    <div id="strengthBarFill" class="strength-bar-fill"></div>
                </div>
            </div>
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                    <i class="fas fa-eye eye-icon" onclick="togglePassword('confirmPassword')"></i>
                </div>
            </div>
            <button type="submit" class="btn">SIGNUP</button>
        </form>
        <p>Already Have an Account? <a href="login.php">LOGIN</a></p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.querySelector(`#${fieldId} + .eye-icon`);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('userPassword').value;
            const strengthBar = document.getElementById('strengthBarFill');

            if (password.length < 8) {
                strengthBar.style.width = '33%';
                strengthBar.style.backgroundColor = 'red';
            } else if (password.length >= 8 && password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/)) {
                strengthBar.style.width = '67%';
                strengthBar.style.backgroundColor = 'yellow';
            } else if (password.length >= 8 && password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/) && password.match(/[@$!%*?&#]/)) {
                strengthBar.style.width = '100%';
                strengthBar.style.backgroundColor = 'green';
            } else {
                strengthBar.style.width = '0%';
                strengthBar.style.backgroundColor = '#e6e6e6';
            }
        }
    </script>
</body>
</html>
