<?php
session_start();
include 'db.php'; // veritabanı bağlantısı

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];
    $sifre = $_POST['Sifre'];

    // Veritabanında admini bulma sorgusu
    $stmt = $conn->prepare("SELECT * FROM Kullanicilar WHERE Email = ? AND Rol = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Şifre doğrulaması
    if ($admin && password_verify($sifre, $admin['Sifre'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['Id'];
        $_SESSION['admin_name'] = $admin['KullaniciAdi'];
        header("Location: admin_panel.php"); // Giriş başarılı oldu admin paneline girebiliriz
        exit();
    } else {
        $error = "E-posta veya şifre hatalı!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('https://serbestiyet.com/wp-content/uploads/2023/09/Ekran-Resmi-2023-09-09-17.47.04.png'); 
            background-size: cover; 
            background-position: center; 
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8); 
            width: 300px;
            padding: 20px;
            margin: 100px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Giriş</h1>
        <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <form method="POST">
            <label for="email">E-posta:</label>
            <input type="email" id="email" name="Email" required>

            <label for="password">Şifre:</label>
            <input type="password" id="password" name="Sifre" required>

            <button type="submit">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
