<?php
session_start();
require_once 'Config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php?error=auth_required");
    exit();
}


$user_id = $_SESSION['user_id'];
$basket_sql = "SELECT SUM(quantity) as total FROM basket WHERE user_id = $user_id";
$basket_result = $conn->query($basket_sql);
$basket_count = $basket_result->fetch_assoc()['total'] ?? 0;


$search = $_GET['search'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT * FROM products WHERE 1=1";

if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR brand LIKE '%$search%')";
}

if ($min_price) {
    $min_price = $conn->real_escape_string($min_price);
    $sql .= " AND price >= $min_price";
}

if ($max_price) {
    $max_price = $conn->real_escape_string($max_price);
    $sql .= " AND price <= $max_price";
}

if ($category) {
    $category = $conn->real_escape_string($category);
    $sql .= " AND category = '$category'";
}

$result = $conn->query($sql);

$cat_sql = "SELECT DISTINCT category FROM products";
$cat_result = $conn->query($cat_sql);
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ev & Yaşam - Trendyol Clone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-filter {
            width: 100%;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #ddd;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-filter:hover {
            background: #f0f0f0;
        }

        .search-bar {
            width: 500px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f3f3f3;
        }

        .search-container {
            flex-grow: 1;
            text-align: center;
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <a href="Index.php" class="logo">trendyol<span>clone</span></a>

            <div class="search-container">
                <form action="" method="get">
                    <input type="text" name="search" class="search-bar"
                        placeholder="Aradığınız ürün, kategori veya markayı yazınız"
                        value="<?php echo htmlspecialchars($search); ?>">
                </form>
            </div>

            <div class="user-menu">
                <a href="#">Hesabım</a>
                <a href="#">Favorilerim</a>
                <a href="Summary.php">Sepetim <span class="basket-count"><?php echo $basket_count; ?></span></a>
                <a href="Logout.php">Çıkış Yap</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="filters">
            <form action="" method="get">
                <?php if ($search): ?>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <?php endif; ?>

                <div class="filter-group">
                    <label>Kitegori</label>
                    <select name="category" onchange="this.form.submit()">
                        <option value="">Tümü</option>
                        <?php while ($cat = $cat_result->fetch_assoc()): ?>
                            <option value="<?php echo $cat['category']; ?>" <?php if ($category == $cat['category'])
                                   echo 'selected'; ?>>
                                <?php echo $cat['category']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Fiyat Aralığı</label>
                    <input type="number" name="min_price" placeholder="En Az"
                        value="<?php echo htmlspecialchars($min_price); ?>" style="margin-bottom: 5px;">
                    <input type="number" name="max_price" placeholder="En Çok"
                        value="<?php echo htmlspecialchars($max_price); ?>">
                </div>

                <button type="submit" class="btn-filter">Ara</button>
                <a href="Products.php"
                    style="display:block; text-align:center; margin-top:10px; font-size:12px; color:#666;">Filtreleri
                    Temizle</a>
            </form>
        </div>

        <div class="main-content">
            <div class="product-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <a href="Shopping.php?id=<?php echo $row['id']; ?>" class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            </div>
                            <div class="product-info">
                                <div class="brand"><?php echo htmlspecialchars($row['brand']); ?></div>
                                <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="price"><?php echo number_format($row['price'], 2, ',', '.'); ?> TL</div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Ürün bulunamadı.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>