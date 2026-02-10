<?php
session_start();
require_once 'Config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_basket'])) {
    
    $check_sql = "SELECT id, quantity FROM basket WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        
        $conn->query("UPDATE basket SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        
        $conn->query("INSERT INTO basket (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    }
    $message = "Ürün sepete eklendi!";
}

$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Ürün bulunamadı.";
    exit();
}

$product = $result->fetch_assoc();


$img_sql = "SELECT * FROM product_images WHERE product_id = $product_id";
$img_result = $conn->query($img_sql);

// Basket Count (for header)
$basket_sql = "SELECT SUM(quantity) as total FROM basket WHERE user_id = $user_id";
$basket_result = $conn->query($basket_sql);
$basket_count = $basket_result->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Trendyol Clone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .product-detail-container {
            display: flex;
            gap: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e6e6e6;
        }

        .product-gallery {
            width: 500px;
            flex-shrink: 0;
        }

        .main-image img {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }

        .thumbnail-list {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            overflow-x: auto;
        }

        .thumbnail-list img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e6e6e6;
            cursor: pointer;
        }

        .thumbnail-list img:hover {
            border-color: var(--primary-color);
        }

        .product-details {
            flex-grow: 1;
        }

        .product-brand {
            font-size: 20px;
            font-weight: bold;
            color: var(--text-color);
        }

        .product-name {
            font-size: 20px;
            margin-top: 5px;
            color: #333;
        }

        .product-price {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-color);
            margin: 20px 0;
        }

        .btn-add-basket {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            max-width: 400px;
            transition: background 0.3s;
        }

        .btn-add-basket:hover {
            background: #d8660e;
        }

        .product-desc {
            margin-top: 30px;
            font-size: 14px;
            line-height: 1.6;
            color: #666;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            margin-bottom: 20px;
            padding: 10px;
            background: #e6fffa;
            border: 1px solid #b2f5ea;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <a href="Index.php" class="logo">trendyol<span>clone</span></a>

            <div class="user-menu">
                <a href="Index.php">Alışverişe Devam Et</a>
                <a href="Summary.php">Sepetim <span class="basket-count"><?php echo $basket_count; ?></span></a>
                <a href="Logout.php">Çıkış Yap</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="product-detail-container">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?php echo $product['image_url']; ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainImg">
                </div>
                <div class="thumbnail-list">
                    <!-- Main image as first thumb -->
                    <img src="<?php echo $product['image_url']; ?>"
                        onclick="document.getElementById('mainImg').src=this.src">

                    <?php while ($img = $img_result->fetch_assoc()): ?>
                        <img src="<?php echo $img['image_url']; ?>"
                            onclick="document.getElementById('mainImg').src=this.src">
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="product-details">
                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>

                <div class="product-price"><?php echo number_format($product['price'], 2, ',', '.'); ?> TL</div>

                <?php if ($message): ?>
                    <div class="success-msg"><?php echo $message; ?></div>
                <?php endif; ?>

                <form method="post">
                    <button type="submit" name="add_to_basket" class="btn-add-basket">Sepete Ekle</button>
                </form>

                <div class="product-desc">
                    <h3>Ürün Özellikleri</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>