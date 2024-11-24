<?php
session_start();
include 'db.php'; 
// Kullanıcı giriş yapmamışsa, giriş sayfasına yönlendir
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: index.php"); 
    exit();
}

// Kullanıcı bilgilerini alıyoruz
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM Kullanicilar WHERE Id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Kullanıcı bulunamadı.";
    exit();
}

// Başarı mesajını göstermek için bir kontrol
$success_message = "";
if (isset($_SESSION['profile_updated']) && $_SESSION['profile_updated'] === true) {
    $success_message = "Profil başarıyla güncellendi!";
    unset($_SESSION['profile_updated']); // Mesajı sadece bir kez göstermek için değişkeni temizliyoruz
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Profili</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .profile-info {
            margin: 20px 0;
        }
        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
        }
        .profile-info label {
            font-weight: bold;
        }
        .update-form {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .update-form input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .update-form button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .update-form button:hover {
            background-color: #218838;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Profil Bilgileri</h1>
        
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <div class="profile-info">
            <p><label>Kullanıcı Adı:</label> <?php echo htmlspecialchars($user['KullaniciAdi']); ?></p>
            <p><label>E-posta:</label> <?php echo htmlspecialchars($user['Email']); ?></p>
        </div>

        <h2>Profilinizi Güncelleyin</h2>
        <form method="POST" action="update_user.php">
            <label for="regName">Adınız</label>
            <input type="text" id="regName" name="KullaniciAdi" value="<?php echo htmlspecialchars($user['KullaniciAdi']); ?>" required>
            
            <label for="regEmail">E-posta</label>
            <input type="email" id="regEmail" name="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
            
            <label for="regPassword">Yeni Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
            <input type="password" id="regPassword" name="Sifre">
            
            <button type="submit">Güncelle</button>
        </form>
    </div>
</body>
</html>
