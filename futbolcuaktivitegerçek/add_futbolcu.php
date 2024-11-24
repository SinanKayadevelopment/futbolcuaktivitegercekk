<?php
include 'db.php'; // Veritabanı bağlantısını dahil et

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isim = $_POST['isim'];

    // Resim yükleme işlemi
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $dosyaAdi = $_FILES['resim']['name'];
        $geciciKonum = $_FILES['resim']['tmp_name'];
        $hedefDizin = 'uploads/'; // Resimlerin kaydedileceği dizin

        // Hedef dosya yolunu oluştur (benzersiz isim için time() kullanılır)
        $yeniDosyaYolu = $hedefDizin . time() . '-' . basename($dosyaAdi);

        // Dosya formatını kontrol et
        $izinVerilenFormatlar = ['jpg', 'jpeg', 'png', 'gif'];
$dosyaUzantisi = strtolower(pathinfo($dosyaAdi, PATHINFO_EXTENSION));

        if (!in_array($dosyaUzantisi, $izinVerilenFormatlar)) {
    die("Yalnızca JPG, JPEG, PNG ve GIF formatlarında dosyalar yükleyebilirsiniz.");
}


        // Dosya boyutunu kontrol et (örnek: 5MB limit)
        if ($_FILES['resim']['size'] > 5 * 1024 * 1024) {
            die("Dosya boyutu 5MB'ı aşamaz.");
        }

        // Resmi belirtilen dizine taşı
        if (move_uploaded_file($geciciKonum, $yeniDosyaYolu)) {
            // Resmi yükledikten sonra veritabanına kaydetme
            $stmt = $conn->prepare("INSERT INTO Futbolcular (Isim, Resim) VALUES (?, ?)");
            $stmt->execute([$isim, $yeniDosyaYolu]);
            echo "Futbolcu başarıyla eklendi!";
        } else {
            echo "Dosya yüklenirken hata oluştu.";
        }
    }
}
?>
