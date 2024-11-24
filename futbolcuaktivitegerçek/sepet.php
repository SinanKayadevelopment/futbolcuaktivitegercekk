<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Sepet Sayfası Genel Stil */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .cart-container {
            background-color: white;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        .cart-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .cart-item h3 {
            margin: 0;
            font-size: 16px;
        }

        .cart-item p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .cart-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>Sepetim</h1>
        <div id="cart-items"></div>
        <div id="cart-total"></div>
        <button id="complete-purchase">Satın Almayı Tamamla</button>
    </div>

    <script>
        // Sepet Verilerini LocalStorage'dan Al
        function displayCart() {
            const cartItemsContainer = document.getElementById("cart-items");
            const cartTotalContainer = document.getElementById("cart-total");
            let sepet = JSON.parse(localStorage.getItem('sepet')) || [];
            let totalPrice = 0;

            cartItemsContainer.innerHTML = ""; // Önceki içerikleri temizle

            // Sepet boşsa mesaj göster
            if (sepet.length === 0) {
                cartItemsContainer.innerHTML = "<p>Sepet boş!</p>";
                cartTotalContainer.innerHTML = "";
                return;
            }

            // Sepet içeriğini oluştur
            sepet.forEach((urun) => {
                const item = document.createElement("div");
                item.className = "cart-item";
                item.innerHTML = `
                    <div>
                        <h3>${urun.isim}</h3>
                        <p>Aktivite: ${urun.aktivite}</p>
                    </div>
                    <p>${urun.fiyat}₺</p>
                `;
                cartItemsContainer.appendChild(item);
                totalPrice += urun.fiyat;
            });

            // Toplam fiyatı göster
            cartTotalContainer.innerHTML = `<div class="cart-total">Toplam: ${totalPrice}₺</div>`;
        }

        // Satın alma işlemi
        document.getElementById("complete-purchase").addEventListener("click", function () {
            alert("Satın alma işlemi tamamlandı!");
            localStorage.removeItem('sepet'); // Sepeti temizle
            displayCart(); // Boş sepeti göster
        });

        // Sayfa yüklendiğinde sepeti görüntüle
        displayCart();
    </script>
</body>
</html>
