<?php
session_start();
$mysqli = new mysqli("localhost", "root", "Qq123456", "music");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);


    $sql = "SELECT username FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: regestration.php?register_error=Username already exists");
    } else {
        $sql = "INSERT INTO users (username, password_hash) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $username, $password_hash);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'user';
            header("Location: regestration.php?register_success=User registered successfully");
        } else {
            header("Location: regestration.php?register_error=Error registering user");
        }
    }

    $stmt->close();
}

$mysqli->close();
?>
