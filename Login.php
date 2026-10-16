<?php
session_start();
require_once 'Config.php';

$error = '';
if (isset($_GET['error']) && $_GET['error'] == 'auth_required') {
    $error = "Bu sayfaya erişmek için lütfen giriş yapınız.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: Index.php");
            exit();
        } else {
            $error = "Hatalı şifre.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Trendyol Clone</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <div class="container">
            <a href="Index.php" class="logo">trendyol<span>clone</span></a>
        </div>
    </header>

    <div class="container">
        <div class="auth-box">
            <h2>Giriş Yap</h2>
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="username">E-Posta (Kullanıcı Adı: admin)</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">GİRİŞ YAP</button>
            </form>
        </div>
    </div>

</body>

</html>