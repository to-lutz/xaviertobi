<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
echo "Willkommen, " . htmlspecialchars($_SESSION['user']) . "! <a href='logout.php'>Logout</a>";
?>
