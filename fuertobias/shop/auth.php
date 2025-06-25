<?php
session_start();

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    include 'db.php';
    $stmt = $conn->prepare("SELECT username FROM users WHERE remember_token=? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($username);
    if ($stmt->fetch()) {
        $_SESSION['user'] = $username;
    }
    $stmt->close();
}
?>
