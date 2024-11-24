<?php
session_start();
include 'db.php'; 


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php"); // Giriş sayfasına yönlendir
    exit();
}

// Kullanıcı bilgilerini güncelleme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $kullaniciAdi = $_POST['KullaniciAdi'];
    $email = $_POST['Email'];
    
    // Şifre güncellenirse hashle
    $sifre = !empty($_POST['Sifre']) ? password_hash($_POST['Sifre'], PASSWORD_DEFAULT) : null;

    if ($sifre) {
        $stmt = $conn->prepare("UPDATE Kullanicilar SET KullaniciAdi = ?, Email = ?, Sifre = ? WHERE Id = ?");
        $stmt->execute([$kullaniciAdi, $email, $sifre, $user_id]);
    } else {
        $stmt = $conn->prepare("UPDATE Kullanicilar SET KullaniciAdi = ?, Email = ? WHERE Id = ?");
        $stmt->execute([$kullaniciAdi, $email, $user_id]);
    }

    
    $_SESSION['profile_updated'] = true;
    
    
    header("Location: kullanici_profil.php");
    exit();
}
?>
