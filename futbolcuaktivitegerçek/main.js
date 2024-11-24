// Sayfa tamamen yüklendikten sonra işlemleri başlatır
document.addEventListener("DOMContentLoaded", function () {
    // Giriş ve kayıt modallarını açma
    const registerBtn = document.getElementById("registerBtn");
    const loginBtn = document.getElementById("loginBtn");
    const registerModal = document.getElementById("registerModal");
    const loginModal = document.getElementById("loginModal");

    if (registerBtn && loginBtn && registerModal && loginModal) {
        registerBtn.onclick = function () {
            registerModal.style.display = "block";
        };

        loginBtn.onclick = function () {
            loginModal.style.display = "block";
        };
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    }

    // Sepete aktivite ekleme işlevi
    function sepeteEkle(futbolcuIsmi, aktivite, fiyat) {
        let sepet = JSON.parse(localStorage.getItem('sepet')) || [];
        sepet.push({ isim: futbolcuIsmi, aktivite: aktivite, fiyat: fiyat });
        localStorage.setItem('sepet', JSON.stringify(sepet));

        alert(`${futbolcuIsmi} için "${aktivite}" aktivitesi sepete eklendi.`);
        updateCartIcon(); // Sepet ikonunu güncelle
    }

    // İşlevi global hale getir
    window.sepeteEkle = sepeteEkle;

    // Sepet ikonunu güncelleyen işlev
    function updateCartIcon() {
        let sepet = JSON.parse(localStorage.getItem('sepet')) || [];
        const sepetCount = document.getElementById("sepet-count");
        if (sepetCount) {
            sepetCount.textContent = sepet.length; // Sepetteki öğe sayısını günceller
        }
    }

    // Sepet popup'ını açıp kapatma işlevi
    function toggleCartPopup() {
        const cartPopup = document.getElementById("cart-popup");
        if (cartPopup) {
            cartPopup.style.display = cartPopup.style.display === "block" ? "none" : "block";
        }
    }

    // Sepet içeriği ve toplam fiyatı göstermek için işlev
    function displayCart() {
        const cartItemsContainer = document.getElementById("cart-items");
        const cartTotalContainer = document.getElementById("cart-total");
        let sepet = JSON.parse(localStorage.getItem('sepet')) || [];
        let totalPrice = 0;

        cartItemsContainer.innerHTML = ""; // Önceki içerikleri temizle

        // Sepet boşsa mesaj göster
        if (sepet.length === 0) {
            cartItemsContainer.innerHTML = "<p>Sepet boş veya oyuncu seçilmedi.</p>";
            return;
        }

        // Sepet içeriğini oluştur
        sepet.forEach((urun, index) => {
            const item = document.createElement("div");
            item.className = "cart-item";
            item.innerHTML = `
                <h3>${urun.isim}</h3>
                <p>Aktivite: ${urun.aktivite}</p>
                <p>Fiyat: ${urun.fiyat}₺</p>
                <button onclick="removeFromCart(${index})">Kaldır</button>
            `;
            cartItemsContainer.appendChild(item);
            totalPrice += urun.fiyat;
        });

        // Toplam fiyatı göster
        cartTotalContainer.innerHTML = `<strong>Toplam Fiyat:</strong> ${totalPrice}₺`;
    }

    // Sepetten bir ürünü kaldırma işlevi
    function removeFromCart(index) {
        let sepet = JSON.parse(localStorage.getItem('sepet')) || [];
        if (index > -1) {
            sepet.splice(index, 1); // Belirtilen ürünü sepetten kaldır
        }
        localStorage.setItem('sepet', JSON.stringify(sepet));
        displayCart(); // Sepeti güncelle
        updateCartIcon(); // Sepet ikonunu güncelle
    }

    // Satın alma işlemini tamamlama
    function completePurchase() {
        alert("Satın alma işlemi tamamlandı!");
        localStorage.removeItem('sepet'); // Sepeti temizle
        displayCart(); // Boş sepeti göster
        updateCartIcon(); // Sepet ikonunu sıfırla
    }

    // Sayfa tamamen yüklendiğinde sepet ikonunu ve popup'ı çalıştır
    const completePurchaseButton = document.getElementById("complete-purchase");
    if (completePurchaseButton) {
        completePurchaseButton.addEventListener("click", completePurchase);
    }

    const sepetContainer = document.getElementById("sepet-container");
    if (sepetContainer) {
        sepetContainer.addEventListener("click", function () {
            toggleCartPopup(); // Popup'ı aç veya kapat
        });
    }

    displayCart(); // Sepet içeriğini göster
    updateCartIcon(); // Sepet ikonunu güncelle
});
