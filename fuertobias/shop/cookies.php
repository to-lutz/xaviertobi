<?php


// Zustimmung zu Cookies speichern
if (isset($_GET['accept_cookies']) && $_GET['accept_cookies'] === 'true') {
    setcookie('cookies_accepted', '1', $cookieParams);
}



?>
