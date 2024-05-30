<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: regestration.php");
    exit();
}
?>
