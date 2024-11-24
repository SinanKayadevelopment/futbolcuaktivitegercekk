<?php
session_start();
session_destroy(); // Oturumu sonlandırdık
header("Location: admin_login.php"); // Çıkıştan sonra giriş sayfasına gönderiyor
exit();
?>
