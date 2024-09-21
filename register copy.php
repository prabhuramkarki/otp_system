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
    <link rel="stylesheet" href="./assets/style.css">
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
                    <input type="password" id="userPassword" name="userPassword" placeholder="Enter Password">
                    <i class="fas fa-eye eye-icon" onclick="togglePassword('userPassword')"></i>
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
            const eyeIcon = passwordField.nextElementSibling;

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
    </script>
</body>
</html>
