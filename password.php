<?php
session_start(); 
include 'db_connection.php'; 

if (isset($_SESSION['login_error'])) {
    echo "<script>alert('{$_SESSION['login_error']}');</script>";
    unset($_SESSION['login_error']); 
}

if (isset($_POST['password'])) {
    $email = $_SESSION['email'];
    $password = $_POST['password'];

    $sql_query = "SELECT password FROM tbl_users WHERE email='$email'";
    $query_result = $db_connection->query($sql_query);

    if ($db_connection->error) {
        echo "SQL Error: " . $db_connection->error;
        exit();
    }

    if ($query_result->num_rows > 0) {
        $row = $query_result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            header("Location: index.php");
            exit(); 
        } else {
            $_SESSION['login_error'] = "Invalid Password.";
            header("Location: password.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid Password.";
        header("Location: password.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Enter Password</h1>
        <form method="post" action="">
            <div class="input-group">
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye eye-icon" onclick="togglePassword()"></i>
                </div>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.querySelector('.eye-icon');

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