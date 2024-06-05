<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TC Kimlik Numarası Doğrulama ve Üretme</title>
    <style>
        /* Sayfa stili */
        body {
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color:lightgreen;
            margin: 0;
        }
        .container {
            background: #FFFACD;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        /* Giriş alanı stili */
        .giris {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 80%;
        }
        /* Buton stili */
        .dogrula, .uret {
            padding: 12px 24px;
            margin: 10px 5px;
            border: none;
            border-radius: 6px;
            background-color: #32CD32;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        /* Buton hover stili */
        .dogrula:hover, .uret:hover {
            background-color: #228B22;
        }
        h1 {
            color: #2E8B57;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>TC Kimlik Numarası Doğrulama ve Üretme</h1>
        <form action="" method="POST">
            <!-- İsim girişi -->
            <input class="giris" type="text" name="isim" placeholder="İsim Girin">
            <!-- Soyisim girişi -->
            <input class="giris" type="text" name="soyisim" placeholder="Soyisim Girin">
            <!-- TC Kimlik numarası girişi -->
            <input class="giris" type="text" name="tckn" placeholder="TC Kimlik Numarası Girin">
            <!-- Doğrula butonu -->
            <input class="dogrula" type="submit" name="dogrula" value="Doğrula">
            <!-- Üret butonu -->
            <button class="uret" type="submit" name="uret">Üret</button>
        </form>
        <div>
            <?php
                // TC Kimlik numarasının geçerli olup olmadığını kontrol eden fonksiyon
                function kimlikNoGecerliMi($kimlikNumarasi) {
                    // TC Kimlik numarası 11 haneli olmalı ve ilk hanesi 0 olmamalıdır.
                    if (strlen($kimlikNumarasi) !== 11 || $kimlikNumarasi[0] == '0' || !ctype_digit($kimlikNumarasi)) {
                        return false;
                    }

                    // 1, 3, 5, 7, 9. hanelerin toplamı
                    $tekHaneToplam = $kimlikNumarasi[0] + $kimlikNumarasi[2] + $kimlikNumarasi[4] + $kimlikNumarasi[6] + $kimlikNumarasi[8];
                    // 2, 4, 6, 8. hanelerin toplamı
                    $ciftHaneToplam = $kimlikNumarasi[1] + $kimlikNumarasi[3] + $kimlikNumarasi[5] + $kimlikNumarasi[7];
                     
                    // 10. hane kontrolü
                    if ((($tekHaneToplam * 7) - $ciftHaneToplam) % 10 != $kimlikNumarasi[9]) {
                        return false;
                    }

                    // İlk 10 hanenin toplamının mod 10'u 11. haneye eşit olmalı
                    $toplam = 0;
                    for ($i = 0; $i < 10; $i++) {
                        $toplam += $kimlikNumarasi[$i];
                    }
                    if ($toplam % 10 != $kimlikNumarasi[10]) {
                        return false;
                    }

                    return true;
                }

                // Form gönderimi POST methodu ile yapıldıysa
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Üret butonuna basıldıysa
                    if (isset($_POST['uret'])) {
                        // Geçerli bir TC Kimlik numarası üret
                        $uretilenKimlikNo = kimlikNoUret();
                        echo "<p>Üretilen geçerli TC Kimlik numarası: $uretilenKimlikNo</p>";
                    } else {
                        // İsim ve soyisim kontrolü
                        $isim = $_POST['isim'];
                        $soyisim = $_POST['soyisim'];
                        if (empty($isim) || empty($soyisim)) {
                            echo "<p>Lütfen isim ve soyisim giriniz!</p>";
                        } elseif (empty($_POST['tckn'])) {
                            echo "<p>Lütfen bir TC Kimlik Numarası giriniz!</p>";
                        } else {
                            // Doğrula butonuna basıldıysa
                            if (isset($_POST['dogrula'])) {
                                $kimlikNumarasi = $_POST['tckn'];
                                // TC Kimlik numarasının geçerli olup olmadığını kontrol et
                                if (kimlikNoGecerliMi($kimlikNumarasi)) {
                                    echo "<p>$isim $soyisim: $kimlikNumarasi geçerli bir TC Kimlik numarasıdır.</p>";
                                } else {
                                    echo "<p>$isim $soyisim: $kimlikNumarasi geçerli bir TC Kimlik numarası değildir.</p>";
                                }
                            }
                        }
                    }
                }

                // Geçerli bir TC Kimlik numarası üreten fonksiyon
                function kimlikNoUret() {
                    $kimlikNumarasi = [];
                    
                    // İlk 9 hane rastgele oluşturuluyor
                    for ($i = 0; $i < 9; $i++) {
                        $kimlikNumarasi[$i] = rand(1, 9); // İlk hane sıfır olamaz
                    }

                    // 1, 3, 5, 7, 9. hanelerin toplamı
                    $tekHaneToplam = $kimlikNumarasi[0] + $kimlikNumarasi[2] + $kimlikNumarasi[4] + $kimlikNumarasi[6] + $kimlikNumarasi[8];
                    // 2, 4, 6, 8. hanelerin toplamı
                    $ciftHaneToplam = $kimlikNumarasi[1] + $kimlikNumarasi[3] + $kimlikNumarasi[5] + $kimlikNumarasi[7];
                    
                    // 10. hane
                    $kimlikNumarasi[9] = (($tekHaneToplam * 7) - $ciftHaneToplam) % 10;

                    // İlk 10 hanenin toplamının mod 10'u 11. haneye eşit olmalı
                    $toplam = 0;
                    for ($i = 0; $i < 10; $i++) {
                        $toplam += $kimlikNumarasi[$i];
                    }
                    $kimlikNumarasi[10] = $toplam % 10;

                    // Dizi elemanlarını string olarak birleştiriyoruz
                    $kimlikNumarasi =  implode('', $kimlikNumarasi);

                    if (strlen($kimlikNumarasi) !== 11 || $kimlikNumarasi[0] == '0' || !ctype_digit($kimlikNumarasi)) {
                        return kimlikNoUret();
                    }

                    return $kimlikNumarasi;
                }
            ?>
        </div>
    </div>
</body>
</html>
