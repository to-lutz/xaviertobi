<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id, verify_expires FROM users WHERE token=? AND status='pending'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $expires);
        $stmt->fetch();

        if (strtotime($expires) > time()) {
            $stmt->close();
            $update = $conn->prepare("UPDATE users SET status='verified', token=NULL, verify_expires=NULL WHERE id=?");
            $update->bind_param("i", $id);
            $update->execute();
            echo "<p style='color:lightgreen; text-align:center;'>✅ Verifizierung erfolgreich! Du kannst dich jetzt <a href='login.php'>einloggen</a>.</p>";
        } else {
            echo "<p style='color:orange; text-align:center;'>⏰ Der Link ist abgelaufen. <a href='resend.php'>Neuen Link senden</a></p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Ungültiger Link oder Benutzer bereits verifiziert.</p>";
    }
}
?>
