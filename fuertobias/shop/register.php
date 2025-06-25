<?php
require 'vendor/autoload.php';
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));
    $status = 'pending';
    $expires = date('Y-m-d H:i:s', time() + 3600); // 1 Stunde gültig

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, token, status, verify_expires) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $password, $token, $status, $expires);

    if ($stmt->execute()) {
        $verifyLink = "http://localhost/shop/verify.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'thomanekxavier@gmail.com';
            $mail->Password = 'cypb baqf zaie yvek';     
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('DEINE_EMAIL@gmail.com', 'FitGear Shop');
            $mail->addAddress($email, $username);
            $mail->isHTML(true);
            $mail->Subject = 'Bitte bestätige deine E-Mail-Adresse';

            $htmlBody = file_get_contents('mail_template.html');
            $htmlBody = str_replace('{{username}}', $username, $htmlBody);
            $htmlBody = str_replace('{{link}}', $verifyLink, $htmlBody);

            $mail->Body = $htmlBody;
            $mail->AltBody = "Hallo $username, bitte bestätige deinen Account: $verifyLink";

            $mail->send();
            $success = "📩 Verifizierungslink wurde an deine E-Mail gesendet.";
        } catch (Exception $e) {
            $error = "E-Mail Fehler: " . $mail->ErrorInfo;
        }
    } else {
        $error = "Registrierung fehlgeschlagen: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Registrieren</title>
  <style>
    body { background:#121212; font-family:sans-serif; color:white; display:flex; align-items:center; justify-content:center; height:100vh; }
    .form-box { background:#1e1e1e; padding:30px; border-radius:10px; max-width:400px; width:100%; box-shadow:0 0 10px black; }
    input, button { width:100%; padding:10px; margin-top:10px; border-radius:6px; border:none; }
    input { background:#2c2c2c; color:white; }
    button { background:#4CAF50; color:white; cursor:pointer; }
    .message { text-align:center; margin-top:10px; }
  </style>
</head>
<body>
  <form method="POST" class="form-box">
    <h2>📝 Registrieren</h2>
    <?php if (isset($error)) echo "<p class='message' style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='message' style='color:lightgreen;'>$success</p>"; ?>
    <input type="text" name="username" placeholder="Benutzername" required />
    <input type="email" name="email" placeholder="E-Mail-Adresse" required />
    <input type="password" name="password" placeholder="Passwort" required />
    <button type="submit">Konto erstellen</button>
    <a href="login.php">Schon registriert? Jetzt einloggen</a>
  </form>
</body>
</html>
