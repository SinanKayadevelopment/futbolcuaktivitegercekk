<?php
$session_duration = 3600; 
include 'db.php'; 
ini_set('session.gc_maxlifetime', $session_duration);
session_set_cookie_params($session_duration);
session_start();

$isUserLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;

// Yeni eklenen kartlar için veri tabanı sorgusu
$stmt = $conn->prepare("SELECT * FROM Kartlar ORDER BY Tarih DESC");
$stmt->execute();
$kartlar = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Müsait futbolcular için veri tabanı sorgusu
$stmt = $conn->prepare("SELECT * FROM Futbolcular");
$stmt->execute();
$futbolcular = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbolcu Sayfası</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<script>
    const isUserLoggedIn = <?php echo json_encode($isUserLoggedIn); ?>;
</script>
<header>
    <img src="resimler/fenerlogo.png" alt="Logo">
    <nav>
        <a href="#">Anasayfa</a>
        <a href="iletisim.html" target="_blank">İletişim</a>

        <?php if ($isUserLoggedIn): ?>
            <a href="kullanici_profil.php"><?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
            <a href="logout.php" style="color: red;">Çıkış Yap</a>
        <?php else: ?>
            <button id="registerBtn">Kayıt Ol</button>
            <button id="loginBtn">Giriş Yap</button>
        <?php endif; ?>
        
       <div id="sepet-container">
            <span class="sepet-ikon">🛒</span>
            <span id="sepet-count">0</span>
        </div>
    </nav>
</header>

<!-- Sepet Popup -->
<div id="cart-popup" class="cart-popup" style="display: none;">
    <div class="cart-content">
        <h3>Sepetim</h3>
        <div id="cart-items"></div>
        <div id="cart-total"></div>
        <button onclick="location.href='sepet.php'">Sepete Git</button>
    </div>
</div>

<!-- Kayıt Modalı -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">&times;</span>
        <div class="modal-inner">
            <div class="modal-image">
                <img src="resimler/merih.jpg" alt="Futbolcu Resmi">
            </div>
            <div class="modal-form">
                <h2>Kayıt Ol</h2>
                <form action="add_user.php" method="POST"> 
                    <label for="regName">Adınız</label>
                    <input type="text" id="regName" name="KullaniciAdi" required placeholder="Kullanıcı Adı"> 
                
                    <label for="regEmail">E-posta</label>
                    <input type="email" id="regEmail" name="Email" required placeholder="E-posta"> 
                
                    <label for="regPassword">Şifre</label>
                    <input type="password" id="regPassword" name="Sifre" required placeholder="Şifre"> 
                
                    <button type="submit">Kaydol</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Giriş Modalı -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('loginModal')">&times;</span>
        <div class="modal-inner">
            <div class="modal-image">
                <img src="resimler/keremak.webp" alt="Futbolcu Resmi"> 
            </div>
            <div class="modal-form">
                <h2>Giriş Yap</h2>
                <form action="login_user.php" method="POST"> 
                    <label for="loginEmail">E-posta</label>
                    <input type="email" id="loginEmail" name="Email" required placeholder="E-posta"> 
                
                    <label for="loginPassword">Şifre</label>
                    <input type="password" id="loginPassword" name="Sifre" required placeholder="Şifre"> 
                    
                    <button type="submit">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Banner -->
<section class="banner">
    <div class="futbolcu-resim">
        <img src="resimler/GOAL - Blank WEB - Facebook - 2024-09-23T105516.393.png.webp" alt="Örnek Futbolcu Resmi 1">
    </div>
    <div class="futbolcu-resim">
        <img src="resimler/paulo-dybala-roma-2023-24-1701686030-123099.jpg" alt="Örnek Futbolcu Resmi 2">
    </div>
    <div class="slogan-resim">
        <img src="resimler/fitnesslogo.jpg" alt="Slogan Image" class="slogan-resim">
        <div class="banner-text">TARAFTAR ZAMANI</div>
    </div>
</section>

<!-- Müsait Olan Oyuncular -->
<div class="section-title">
    <h2>Müsait Olan Oyuncular</h2>
</div>
<div class="cards-container">
    <?php foreach ($futbolcular as $futbolcu): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($futbolcu['Resim']); ?>" alt="<?php echo htmlspecialchars($futbolcu['Isim']); ?>">
            <h3><?php echo htmlspecialchars($futbolcu['Isim']); ?></h3>
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($futbolcu['Isim']); ?>', 'Birlikte Antrenman', 20000)">Birlikte Antrenman</button>
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($futbolcu['Isim']); ?>', 'Birlikte Akşam Yemeği', 40000)">Birlikte Akşam Yemeği</button>
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($futbolcu['Isim']); ?>', 'Stad Turu', 60000)">Stad Turu</button>
        </div>
    <?php endforeach; ?>
</div>

<!-- Yeni Eklenen Kartlar -->
<div class="section-title">
    <h2>Yeni Eklenen Kartlar</h2>
</div>
<div class="cards-container">
    <?php foreach ($kartlar as $kart): ?>
        <div class="card">
            <?php if (!empty($kart['ResimYolu'])): ?>
                <img src="<?php echo htmlspecialchars($kart['ResimYolu']); ?>" alt="Kart Resmi">
            <?php endif; ?>
            <h3><?php echo htmlspecialchars($kart['Baslik']); ?></h3>
            <p><?php echo htmlspecialchars($kart['Aciklama']); ?></p>
            <!-- Alt Kısım: Aktivite Seçenekleri -->
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($kart['Baslik']); ?>', 'Birlikte Antrenman', 20000)">Birlikte Antrenman</button>
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($kart['Baslik']); ?>', 'Birlikte Akşam Yemeği', 40000)">Birlikte Akşam Yemeği</button>
            <button onclick="sepeteEkle('<?php echo htmlspecialchars($kart['Baslik']); ?>', 'Stad Turu', 60000)">Stad Turu</button>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <p>Instagram adresimiz</p>
</footer>
<script src="main.js"></script>
</body>
</html>
