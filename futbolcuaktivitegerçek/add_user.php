<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // veritabanını içeri aktardım

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciAdi = $_POST['KullaniciAdi']; 
    $email = $_POST['Email']; // Email
    $sifre = password_hash($_POST['Sifre'], PASSWORD_DEFAULT); //şifreyi hashleyedim

    // Kullanıcı ekleme sorgusu
    function add_record($table, $fields, $values) {
        global $conn;
        $fields_list = implode(", ", $fields);
        $placeholders = implode(", ", array_fill(0, count($values), "?"));
    
        $stmt = $conn->prepare("INSERT INTO $table ($fields_list) VALUES ($placeholders)");
        return $stmt->execute($values);
    }
    
    if (add_record('Futbolcular', ['Isim', 'Resim'], [$isim, $yeniDosyaYolu])) {
        echo "Futbolcu başarıyla eklendi!";
    } else {
        echo "Hata oluştu.";
    }
    
}
?>
