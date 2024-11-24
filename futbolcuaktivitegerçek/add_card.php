<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = $_POST['Baslik']; // Futbolcu ismi
    $aciklama = $_POST['Aciklama']; // Açıklama
    $resimYolu = '';

    // Resim yükleme işlemi
    if (isset($_FILES['Resim']) && $_FILES['Resim']['error'] === UPLOAD_ERR_OK) {
        $dosyaAdi = $_FILES['Resim']['name'];
        $geciciKonum = $_FILES['Resim']['tmp_name'];
        $hedefDizin = 'uploads/';
        $yeniDosyaYolu = $hedefDizin . time() . '-' . basename($dosyaAdi);

        if (move_uploaded_file($geciciKonum, $yeniDosyaYolu)) {
            $resimYolu = $yeniDosyaYolu;
        } else {
            die("Resim yükleme hatası oluştu.");
        }
    }

    // Veritabanına ekleme
    $stmt = $conn->prepare("INSERT INTO Kartlar (Baslik, Aciklama, ResimYolu) VALUES (?, ?, ?)");
    if ($stmt->execute([$baslik, $aciklama, $resimYolu])) {
        header("Location: admin_panel.php?status=card_added");
        exit();
    } else {
        echo "Kart ekleme sırasında bir hata oluştu.";
    }
}
?>
