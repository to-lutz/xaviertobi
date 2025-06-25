<?php
include 'db.php';
$valid = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token=? AND reset_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $valid = true;
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['token'])) {
    $token = $_POST['token'];
    $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE reset_token=?");
    $stmt->bind_param("ss", $new_pass, $token);
    $stmt->execute();

    echo "<p style='color:lightgreen;'>Passwort wurde geändert. <a href='login.php'>Zum Login</a></p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>Passwort zurücksetzen</title>
  <style>
    body { background:#121212; color:white; display:flex; align-items:center; justify-content:center; height:100vh; font-family:sans-serif; }
    form { background:#1e1e1e; padding:30px; border-radius:8px; box-shadow:0 0 10px black; width:300px; }
    input[type="password"], button { width:100%; padding:10px; margin-top:10px; border:none; border-radius:5px; }
    input { background:#2c2c2c; color:white; }
    button { background:#4CAF50; color:white; cursor:pointer; }
  </style>
</head>
<body>
<?php if ($valid): ?>
  <form method="POST">
    <h2>🔒 Neues Passwort</h2>
    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>" />
    <input type="password" name="password" placeholder="Neues Passwort" required />
    <button type="submit">Zurücksetzen</button>
  </form>
<?php else: ?>
  <p style="color:red;">Ungültiger oder abgelaufener Token.</p>
<?php endif; ?>
</body>
</html>
