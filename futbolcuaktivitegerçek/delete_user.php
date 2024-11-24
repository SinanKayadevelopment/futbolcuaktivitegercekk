<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM Kullanicilar WHERE Id = ?");
    if ($stmt->execute([$id])) {
        header("Location: admin_panel.php?status=deleted");
    } else {
        echo "Silme işlemi başarısız oldu.";
    }
}
?>
