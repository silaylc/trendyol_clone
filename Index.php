<?php
/* bu php kodu , anasayfayi olusturur, vitrin, db den veri ceker,
kullanici giris durumunu kontrol eder, dinamik urun*/

session_start();// kullanici giris kontrolu//
require_once 'Config.php'; //db baglantisi icin config dahil edilir//

$is_logged_in = isset($_SESSION['user_id']);//kullanici giris kontrolu ve sepet sayisi//
$basket_count = 0;

if ($is_logged_in) {
    /*giris yaptiysa sepete bir sey ekleyebilir*/
    $user_id = $_SESSION['user_id'];
    $basket_sql = "SELECT SUM(quantity) as total FROM basket WHERE user_id = $user_id";
    $basket_result = $conn->query($basket_sql);
    $basket_count = $basket_result->fetch_assoc()['total'] ?? 0;
}

// Hardcoded Boutiques Data (Simulating Trendyol's Campaign/Boutique System)
// Using product images as banner representatives
$boutiques = [
    [
        'name' => 'English Home',
        'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQRP8XIqeMvU92ime8-uQavA_5TNz0H8TZxqw&s',
        'tag' => 'KARGO BEDAVA'
    ],
    [
        'name' => 'Karaca',
        'image' => 'https://images.seeklogo.com/logo-png/49/1/karaca-logo-png_seeklogo-492431.png',
        'tag' => 'SÜPER FIRSAT'
    ],
    [
        'name' => 'Madame Coco',
        'image' => 'https://avatars.mds.yandex.net/i?id=8ee8bb0cac524dd95834359400a25f66c19ea450-12422267-images-thumbs&n=13',
        'tag' => 'HIZLI TESLİMAT'
    ],
    [
        'name' => 'Vivense',
        'image' => 'https://images.seeklogo.com/logo-png/44/1/vivense-logo-png_seeklogo-445916.png',
        'tag' => 'SEPETTE %20 İNDİRİM'
    ],
    [
        'name' => 'Paşabahçe',
        'image' => 'https://cdn.brandfetch.io/pasabahcemagazalari.com/fallback/lettermark/theme/dark/h/256/w/256/icon?c=1bfwsmEH20zzEfSNTed',
        'tag' => 'ÇOK AL AZ ÖDE'
    ],
    [
        'name' => 'Yataş',
        'image' => 'https://www.logotypes101.com/logos/145/26C35F77503FB1E99D446E478269B823/yatas_bedding.png',
        'tag' => 'YENİ SEZON'
    ],
    [
        'name' => 'Koçtaş',
        'image' => 'https://avatars.mds.yandex.net/i?id=a94a63ddd254c09676297f166263f9b91e9f9a82-12622473-images-thumbs&n=13',
        'tag' => 'KARGO BEDAVA'
    ],
    [
        'name' => 'Bambum',
        'image' => 'https://i.pinimg.com/736x/f7/d3/81/f7d381bdfd4a00a437a6c5f3bde05de8.jpg',
        'tag' => 'DOĞAL YASAM'
    ],
    [
        'name' => 'Ikea',
        'image' => 'https://static.dezeen.com/uploads/2019/04/ikea-logo-new-sq-1.jpg',
        'tag' => 'DÜZENLEYİCİLER'
    ]
];

// Rastgele 9 urun (one cikanlar),vitrin dinamik kalir
$highlighted_sql = "SELECT * FROM products ORDER BY RAND() LIMIT 8";
$highlighted_result = $conn->query($highlighted_sql);

// en yeni 8 urun (en cok begenilen), db ye en son ekleneni gosterir
$liked_sql = "SELECT * FROM products ORDER BY id DESC LIMIT 8";
$liked_result = $conn->query($liked_sql);

?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anasayfa - Trendyol Clone</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        .search-container {
            flex-grow: 1;
            text-align: center;
        }

        .search-bar {
            width: 500px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f3f3f3;
        }
    </style>
</head>

<body>

    <!-- main header -->
    <header>
        <div class="container">
            <a href="Index.php" class="logo">trendyol<span>clone</span></a>

            <div class="search-container">
                <form action="Products.php" method="get">
                    <input type="text" name="search" class="search-bar"
                        placeholder="Aradığınız ürün, kategori veya markayı yazınız">
                </form>
            </div>

            <div class="user-menu"> 
                <?php if ($is_logged_in): ?>
                    <a href="#">Hesabım</a>
                    <a href="#">Favorilerim</a>
                    <a href="Summary.php">Sepetim <span class="basket-count"><?php echo $basket_count; ?></span></a>
                    <a href="Logout.php">Çıkış Yap</a>
                <?php else: ?>
                    <a href="Login.php" class="btn-login-header" style="font-weight: bold; color: var(--text-color);">Giriş
                        Yap</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!--navigation menu -->
    <div class="nav-menu">
        <div class="container">
            <a href="#" class="nav-link">KADIN</a>
            <a href="#" class="nav-link">ERKEK</a>
            <a href="#" class="nav-link">ANNE & ÇOCUK</a>
            <a href="Products.php" class="nav-link active">EV & MOBİLYA</a>
            <a href="#" class="nav-link">SÜPERMARKET</a>
            <a href="#" class="nav-link">KOZMETİK</a>
            <a href="#" class="nav-link">AYAKKABI & ÇANTA</a>
            <a href="#" class="nav-link">SAAT & AKSESUAR</a>
            <a href="#" class="nav-link">ELEKTRONİK</a>
            <a href="#" class="nav-link">SPOR & OUTDOOR</a>
        </div>
    </div>

    <!-- main content -->
    <div class="container">

        <!-- banner animasyonlari -->
        <div class="promo-banners">
            <div class="promo-banner">
                <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=2070&auto=format&fit=crop"
                    alt="Promo 1">
            </div>
            <div class="promo-banner">
                <img src="https://images.unsplash.com/photo-1567016432779-094069958ea5?q=80&w=2070&auto=format&fit=crop"
                    alt="Promo 2">
            </div>
        </div>

        <!-- db den rastgele secilen (one cikanlar) -->
        <h2 class="section-title">Öne Çıkan Ürünler</h2>
        <div class="product-slider">
            <?php while ($row = $highlighted_result->fetch_assoc()): ?> <!-- db den gelen satirlari okur -->
                <a href="Shopping.php?id=<?php echo $row['id']; ?>" class="slider-item"><!-- dinamik link , shopping php icin -->
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="slider-brand"><?php echo htmlspecialchars($row['brand']); ?></div>
                    <div class="slider-name"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="slider-price"><?php echo number_format($row['price'], 2, ',', '.'); ?> TL</div>
                </a>
            <?php endwhile; ?>
        </div>

      
        <h2 class="section-title">Popüler Markalar</h2>
        <div class="boutique-grid">   <!-- css deki class sayesinde izgaraya dizilir -->
            <?php foreach ($boutiques as $boutique): ?>   <!-- hardcoded yazdigimiz arr icin -->
                <a href="Products.php?search=<?php echo urlencode($boutique['name']); ?>" class="boutique-card">   <!-- marka isminde bosluk varsa ,url formatina cevirir -->
                    <img src="<?php echo $boutique['image']; ?>" alt="<?php echo htmlspecialchars($boutique['name']); ?>"
                        class="boutique-image">
                    <div class="boutique-info">
                        <div class="boutique-brand"><?php echo htmlspecialchars($boutique['name']); ?></div>
                        <span class="boutique-tag"><?php echo $boutique['tag']; ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    
        <h2 class="section-title">En Çok Beğenilenler</h2>
        <div class="product-slider"> 
            <?php while ($row = $liked_result->fetch_assoc()): ?>   <!--db deki urunller bitene kadar html kodlarini olusturur -->
                <a href="Shopping.php?id=<?php echo $row['id']; ?>" class="slider-item">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    <div class="slider-brand"><?php echo htmlspecialchars($row['brand']); ?></div>
                    <div class="slider-name"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="slider-price"><?php echo number_format($row['price'], 2, ',', '.'); ?> TL</div>
                </a>
            <?php endwhile; ?>
        </div>

        <br><br>
    </div>

</body>

</html>