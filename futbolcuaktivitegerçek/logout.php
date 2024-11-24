<?php
session_start();
session_unset(); // Tüm oturum değişkenlerini temizle
session_destroy(); // Oturumu sona erdir

// Anasayfaya yönlendir
header("Location: index.php");
exit();
?>
