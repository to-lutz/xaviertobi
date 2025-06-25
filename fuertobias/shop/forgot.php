<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(16));
    $expires = date("Y-m-d H:i:s", time() + 3600); // 1 Stunde gültig

    $stmt = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expires, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $link = "http://localhost/kbmehrja/reset.php?token=$token";
        echo "<p style='color:lightgreen;'>Passwort-Reset-Link: <a href='$link'>$link</a></p>";
    } else {
        echo "<p style='color:red;'>E-Mail nicht gefunden.</p>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Passwort vergessen</title>
  <style>
    body { background:#121212; color:white; display:flex; align-items:center; justify-content:center; height:100vh; font-family:sans-serif; }
    form { background:#1e1e1e; padding:30px; border-radius:8px; box-shadow:0 0 10px black; width:300px; }
    input[type="email"], button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:5px; }
    input { background:#2c2c2c; color:white; }
    button { background:#4CAF50; color:white; cursor:pointer; }
  </style>
</head>
<body>
  <form method="POST">
    <h2>🔑 Passwort vergessen?</h2>
    <input type="email" name="email" placeholder="Deine E-Mail" required />
    <button type="submit">Link senden</button>
  </form>
</body>
</html>
