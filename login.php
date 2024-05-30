<?php
session_start();
$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password_hash, role FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $username, $password_hash, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $password_hash)) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role; 
        header("Location: index.php");
    } else {
        header("Location: regestration.php?login_error=Invalid username or password");
    }
    

    $stmt->close();
}

$mysqli->close();
?>
