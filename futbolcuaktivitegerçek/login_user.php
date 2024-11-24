<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];
    $sifre = $_POST['Sifre'];

    // Kullanıcıyı veritabanından bulma
    $stmt = $conn->prepare("SELECT * FROM Kullanicilar WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Şifre doğrulaması
    if ($user && password_verify($sifre, $user['Sifre'])) {
        // Kullanıcı giriş başarılı
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['Id'];
        $_SESSION['user_name'] = $user['KullaniciAdi'];

        // Başarılı mesaj ve yönlendirme
        echo "<div style='color: green;'>Giriş başarılı!</div>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php'; // 2 saniye sonra anasayfaya yönlendir
                }, 2000);
              </script>";
              
    } else {
        // Hatalı giriş
        $error = "E-posta veya şifre hatalı!";
        echo "<div style='color: red;'>$error</div>";
    }
}
$_SESSION['user_name'] = $user['KullaniciAdi']; // Kullanıcı adını oturuma kaydet

?>
