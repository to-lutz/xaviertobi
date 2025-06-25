<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password, status FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hash, $status);
    if ($stmt->fetch()) {
        if ($status !== 'verified') {
            $error = "Bitte bestätige zuerst deine E-Mail.";
        } elseif (password_verify($password, $hash)) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Falsches Passwort.";
        }
    } else {
        $error = "Benutzer nicht gefunden.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <style>
    body { background:#121212; font-family:sans-serif; color:white; display:flex; align-items:center; justify-content:center; height:100vh; }
    .form-box { background:#1e1e1e; padding:30px; border-radius:10px; max-width:400px; width:100%; }
    input, button { width:100%; padding:10px; margin-top:10px; border-radius:6px; border:none; }
    input { background:#2c2c2c; color:white; }
    button { background:#4CAF50; color:white; }
    label { display:block; margin-top:10px; }
    a { color:#4CAF50; text-decoration:none; display:block; text-align:center; margin-top:15px; }
  </style>
</head>
<body>
  <form method="POST" class="form-box">
    <h2>🔐 Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="text" name="username" placeholder="Benutzername" required>
    <input type="password" name="password" placeholder="Passwort" required>
    <button type="submit">Einloggen</button>
    <a href="register.php">Noch kein Konto? Jetzt registrieren</a>
  </form>
</body>
</html>
