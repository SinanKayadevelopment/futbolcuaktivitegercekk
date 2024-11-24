<?php

$message = ''; // Varsayılan değer
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'deleted') {
        $message = "Kullanıcı başarıyla silindi!";
    } elseif ($_GET['status'] == 'updated') {
        $message = "Kullanıcı başarıyla güncellendi!";
    }
}

session_start();
include 'db.php'; // Veritabanı bağlantı

// Admin girişi yapılmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Kullanıcıları veritabanından çek
$stmt = $conn->prepare("SELECT * FROM Kullanicilar");
$stmt->execute();
$kullanicilar = $stmt->fetchAll(PDO::FETCH_ASSOC);
   // Futbolcuları veritabanından çekiyoruz
   $stmt = $conn->prepare("SELECT * FROM Futbolcular");
   $stmt->execute();
   $futbolcular = $stmt->fetchAll(PDO::FETCH_ASSOC);
   ?>

<?php if (!empty($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="admin_panel.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: linear-gradient(to right, #ffdd57, #007bff);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #fff;
            font-size: 24px;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container input[type="file"] {
            background-color: #fff;
            cursor: pointer;
        }

        .form-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container input:focus, 
        .form-container button:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.8);
        }
    </style>
</head>
<body>
    <h1>Admin Paneli</h1>

    
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Email</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($kullanicilar as $kullanici): ?>
            <tr>
                <td><?php echo $kullanici['Id']; ?></td>
                <td><?php echo $kullanici['KullaniciAdi']; ?></td>
                <td><?php echo $kullanici['Email']; ?></td>
                <td>
                    <a class="update" href="update_admin.php?id=<?php echo $kullanici['Id']; ?>">Güncelle</a>
                    <a class="delete" href="delete_user.php?id=<?php echo $kullanici['Id']; ?>">Sil</a>
                </td>
            </tr>
            


        <?php endforeach; ?>
    </table>
    <h2>Yeni Kart Ekle</h2>
<form action="add_card.php" method="POST" enctype="multipart/form-data">
    <label for="Baslik">Futbolcu İsmi:</label>
    <input type="text" name="Baslik" id="Baslik" required>

    <label for="Aciklama">Futbolcu Açıklaması:</label>
    <textarea name="Aciklama" id="Aciklama" required></textarea>

    <label for="Resim">Futbolcu Resmi:</label>
    <input type="file" name="Resim" id="Resim" accept="image/*">

    <button type="submit">Kart Ekle</button>
</form>



<div class="cards-container">
    <?php foreach ($futbolcular as $futbolcu): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($futbolcu['Resim']); ?>" alt="<?php echo htmlspecialchars($futbolcu['Isim']); ?>">
            <h3><?php echo htmlspecialchars($futbolcu['Isim']); ?></h3>
            
            <!-- Silme Formu -->
            <form action="delete_futbolcu.php" method="POST">
                <input type="hidden" name="futbolcu_id" value="<?php echo $futbolcu['ID']; ?>"> <!-- Futbolcunun ID'sini gizli olarak gönderiyoruz -->
                <button type="submit" onclick="return confirm('Emin misiniz?');">Sil</button> <!-- Silme butonu -->
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
