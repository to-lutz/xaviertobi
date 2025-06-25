<?php
require 'vendor/autoload.php';
include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT username FROM users WHERE email=? AND status='pending'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 Stunde gültig
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();

        $update = $conn->prepare("UPDATE users SET token=?, verify_expires=? WHERE email=?");
        $update->bind_param("sss", $token, $expires, $email);
        $update->execute();

        $link = "http://localhost/shop/verify.php?token=$token";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'thomanekxavier@gmail.com'; // ändern!
        $mail->Password = 'DEIN_APP_PASSWORT';     // ändern!
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('DEINE_EMAIL@gmail.com', 'FitGear Shop');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Dein neuer Verifizierungslink';
        $mail->Body = "Hallo $username,<br><br>Klicke hier, um deinen Account zu verifizieren:<br><a href='$link'>$link</a>";

        $mail->send();
        $info = "📧 Neuer Link gesendet!";
    } else {
        $error = "⚠️ E-Mail nicht gefunden oder bereits bestätigt.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Verifizierungslink erneut senden</title>
  <style>
    body { background:#121212; color:white; font-family:sans-serif; display:flex; align-items:center; justify-content:center; height:100vh; }
    .form-box { background:#1e1e1e; padding:30px; border-radius:10px; max-width:400px; width:100%; box-shadow:0 0 10px black; }
    input, button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:6px; background:#2c2c2c; color:white; }
    button { background:#4CAF50; cursor:pointer; }
  </style>
</head>
<body>
  <form method="POST" class="form-box">
    <h2>📨 Link erneut senden</h2>
    <?php if (isset($info)) echo "<p style='color:lightgreen;'>$info</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="email" name="email" placeholder="Deine E-Mail-Adresse" required />
    <button type="submit">Senden</button>
  </form>
</body>
</html>
