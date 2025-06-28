<?php
require_once 'init.php';
use Firebase\JWT\JWT;

$page_title = "Login";
$login_error = "";
$login_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = '" . mysqli_real_escape_string($link, $username) . "'";
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if ($password === $user['password']) {
                $secret_key = "secret_digit";
                $token_payload = ["iat" => time(), "exp" => time() + 3600, "data" => ["id" => $user['id'], "username" => $user['username'], "role" => $user['role']]];
                $jwt = JWT::encode($token_payload, $secret_key, 'HS256');
                setcookie("auth_token", $jwt, ["expires" => time() + 3600, "path" => "/", "httponly" => true]);
                $login_success = true;
            }
        }
    }
    if (!$login_success) { $login_error = "Invalid username or password."; }
}

include 'header.php';
?>
<main class="auth-form">
    <?php if ($login_success): ?>
        <div style="text-align:center; background-color: #d4edda; padding: 2em; border-radius: 5px;">
            <h2 style="color: #155724;">Login Successful!</h2>
            <p>Welcome back, <?php echo htmlspecialchars($username); ?>.</p>
            <p style="margin-top: 2em;"><a href="index.php" class="button">Go to Homepage</a></p>
        </div>
    <?php else: ?>
        <h2>Login</h2>
        <?php if(!empty($login_error)){ echo '<div class="form-error">' . htmlspecialchars($login_error) . '</div>'; } ?>
        <form action="login.php" method="POST">
            <div><label for="username">Username:</label><input type="text" id="username" name="username" required></div>
            <div><label for="password">Password:</label><input type="password" id="password" name="password" required></div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Create Account</a></p>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>