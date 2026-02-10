<?php
session_start();
require_once 'Config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['remove_id'])) {
        $id = (int) $_POST['remove_id'];
        $conn->query("DELETE FROM basket WHERE id=$id AND user_id=$user_id");
    } elseif (isset($_POST['update_id']) && isset($_POST['action'])) {
        $id = (int) $_POST['update_id'];
        $action = $_POST['action'];

        if ($action == 'increase') {
            $conn->query("UPDATE basket SET quantity = quantity + 1 WHERE id=$id AND user_id=$user_id");
        } elseif ($action == 'decrease') {
            

            $current_qty_sql = "SELECT quantity FROM basket WHERE id=$id";
            $current_qty_res = $conn->query($current_qty_sql);
            if ($current_qty_res->num_rows > 0) {
                $qty = $current_qty_res->fetch_assoc()['quantity'];
                if ($qty > 1) {
                    $conn->query("UPDATE basket SET quantity = quantity - 1 WHERE id=$id AND user_id=$user_id");
                }
            }
        }
    }
}


$sql = "SELECT b.id as basket_id, b.quantity, p.name, p.price, p.image_url, p.brand, p.id as product_id 
        FROM basket b 
        JOIN products p ON b.product_id = p.id 
        WHERE b.user_id = $user_id";
$result = $conn->query($sql);

$total_price = 0;
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetim - Trendyol Clone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .basket-container {
            display: flex;
            gap: 20px;
        }

        .basket-items {
            flex-grow: 1;
        }

        .basket-summary {
            width: 300px;
            background: white;
            padding: 20px;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            height: fit-content;
        }

        .basket-item {
            display: flex;
            background: white;
            padding: 15px;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            margin-bottom: 15px;
            align-items: center;
        }

        .basket-item img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 20px;
            border: 1px solid #f0f0f0;
        }

        .item-details {
            flex-grow: 1;
        }

        .item-brand {
            font-weight: bold;
            font-size: 14px;
        }

        .item-name {
            font-size: 14px;
            margin-top: 5px;
            color: #666;
        }

        .item-price {
            font-weight: bold;
            color: var(--primary-color);
            margin-top: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: fit-content;
            margin-top: 10px;
        }

        .quantity-control button {
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 16px;
            color: #666;
        }

        .quantity-control button:hover {
            background: #f0f0f0;
        }

        .quantity-control span {
            padding: 5px 10px;
            font-weight: bold;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .remove-btn {
            color: #999;
            text-decoration: none;
            font-size: 20px;
            margin-left: 20px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .remove-btn:hover {
            color: var(--primary-color);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .total-row {
            font-weight: bold;
            font-size: 18px;
            color: var(--primary-color);
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .btn-checkout {
            background: var(--primary-color);
            color: white;
            width: 100%;
            border: none;
            padding: 12px;
            font-weight: bold;
            border-radius: 4px;
            margin-top: 20px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <a href="Index.php" class="logo">trendyol<span>clone</span></a>

            <div class="user-menu">
                <a href="Index.php">Alışverişe Devam Et</a>
                <a href="Logout.php">Çıkış Yap</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="basket-container">
            <div class="basket-items">
                <h2>Sepetim (<?php echo $result->num_rows; ?> Ürün)</h2>

                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $line_total = $row['price'] * $row['quantity'];
                        $total_price += $line_total;
                        ?>
                        <div class="basket-item">
                            <img src="<?php echo $row['image_url']; ?>" alt="Product">
                            <div class="item-details">
                                <div class="item-brand"><?php echo htmlspecialchars($row['brand']); ?></div>
                                <div class="item-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="quantity-control">
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="update_id" value="<?php echo $row['basket_id']; ?>">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit">-</button>
                                    </form>
                                    <span><?php echo $row['quantity']; ?></span>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="update_id" value="<?php echo $row['basket_id']; ?>">
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit">+</button>
                                    </form>
                                </div>
                                <div class="item-price"><?php echo number_format($row['price'], 2, ',', '.'); ?> TL</div>
                            </div>
                            <form method="post">
                                <input type="hidden" name="remove_id" value="<?php echo $row['basket_id']; ?>">
                                <button type="submit" class="remove-btn" title="Sil">&times;</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="background:white; padding:40px; text-align:center; border-radius:8px;">
                        <h3>Sepetinizde ürün bulunmamaktadır.</h3>
                        <a href="Index.php" class="btn-primary"
                            style="display:inline-block; width:auto; margin-top:20px;">Alışverişe Başla</a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($total_price > 0): ?>
                <div class="basket-summary">
                    <h3>Sipariş Özeti</h3>
                    <div class="summary-row">
                        <span>Ürünün Toplamı</span>
                        <span><?php echo number_format($total_price, 2, ',', '.'); ?> TL</span>
                    </div>
                    <div class="summary-row">
                        <span>Kargo Toplam</span>
                        <span>29,99 TL</span>
                    </div>
                    <div class="summary-row" style="color:#198038;">
                        <span>Kargo İndirimi</span>
                        <span>-29,99 TL</span>
                    </div>
                    <div class="summary-row total-row">
                        <span>Toplam</span>
                        <span><?php echo number_format($total_price, 2, ',', '.'); ?> TL</span>
                    </div>
                    <button class="btn-checkout">Sepeti Onayla</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>