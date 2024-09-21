<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

$email = $_SESSION['email']; 

$sql_query = "SELECT name FROM tbl_users WHERE email='$email'";
$query_result = $db_connection->query($sql_query);

if ($db_connection->error) {
    echo "SQL Error: " . $db_connection->error;
    exit();
}

if ($query_result->num_rows > 0) {
    $row = $query_result->fetch_assoc();
    $user_name = $row['name'];
} else {
    $user_name = 'User';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
    <div class="profile-container">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?> to your dashboard</h1>
        <p>You're now logged in to your profile.</p>
        <br>
        <br>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</body>
</html>
