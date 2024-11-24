<?php
include 'db.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['futbolcu_id'])) {
    $futbolcuId = $_POST['futbolcu_id'];

    // Silme sorgusu
    $stmt = $conn->prepare("DELETE FROM Futbolcular WHERE ID = :id");
    $stmt->bindParam(':id', $futbolcuId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Futbolcu başarıyla silindi!";
        header("Location: admin_panel.php"); // Silme işleminden sonra admin paneline yönlendir
        exit();
    } else {
        echo "Bir hata oluştu, futbolcu silinemedi.";
    }
}
?>
