<?php
session_start();
include 'db.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Veritabanından kullanıcının bilgilerini çekme
    $stmt = $conn->prepare("SELECT * FROM Kullanicilar WHERE Id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eğer kullanıcı bulunamazsa hata mesajı gösterme
    if (!$kullanici) {
        echo "Kullanıcı bulunamadı.";
        exit;
    }
} else {
    echo "Kullanıcı ID'si belirtilmedi.";
    exit;
}

// Form gönderildiğinde güncelleme işlemi yapma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $kullaniciAdi = $_POST['KullaniciAdi'];
    $email = $_POST['Email'];

    $stmt = $conn->prepare("UPDATE Kullanicilar SET KullaniciAdi = :kullaniciAdi, Email = :email WHERE Id = :id");
    $stmt->bindParam(':kullaniciAdi', $kullaniciAdi, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Kullanıcı başarıyla güncellendi.'); window.location.href = 'admin_panel.php';</script>";
    } else {
        echo "Güncelleme sırasında bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Güncelle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .update-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .update-container h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <div class="update-container">
        <h1>Kullanıcı Güncelle</h1>
        <form action="update_admin.php?id=<?php echo htmlspecialchars($kullanici['Id']); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($kullanici['Id']); ?>">

            <label for="KullaniciAdi">Kullanıcı Adı:</label>
            <input type="text" name="KullaniciAdi" id="KullaniciAdi" value="<?php echo htmlspecialchars($kullanici['KullaniciAdi']); ?>" required>

            <label for="Email">Email:</label>
            <input type="email" name="Email" id="Email" value="<?php echo htmlspecialchars($kullanici['Email']); ?>" required>

            <button type="submit">Güncelle</button>
        </form>
    </div>
</body>
</html>

