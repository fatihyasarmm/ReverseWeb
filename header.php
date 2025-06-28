<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$is_user_loggedin = false;
$current_user_data = null;
if (isset($_COOKIE['auth_token'])) {
    $secret_key = "secret_digit";
    $token = $_COOKIE['auth_token'];
    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        $is_user_loggedin = true;
        $current_user_data = $decoded->data;
    } catch (Exception $e) {
        $is_user_loggedin = false;
    }
}
$page_title = $page_title ?? 'ReverseWeb';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">ReverseWeb</a></div>
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for vulnerabilities...">
                <button type="submit">Search</button>
            </form>
        </div>
        <nav class="user-nav">
            <?php if ($is_user_loggedin): ?>
                <span class="welcome-text">Hello, <?php echo htmlspecialchars($current_user_data->username); ?></span>
                <a href="profile.php">My Account</a>
                <a href="progress.php">Progress</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Create Account</a>
            <?php endif; ?>
        </nav>
    </header>