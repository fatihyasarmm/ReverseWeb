<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Attempt - ReverseWeb</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .status-message { background-color: white; padding: 2em; border-radius: 5px; max-width: 600px; margin: 2em auto; }
        .status-message h1 { text-align: center; }
    </style>
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">ReverseWeb</a></div>
        <nav class="user-nav">
            <a href="login.php">Login</a>
            <a href="register.php">Create Account</a>
        </nav>
    </header>
    <main>
        <div class="status-message">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email_reset = isset($_POST['email_reset']) ? $_POST['email_reset'] : 'Email not specified';
                $email_reset_safe = htmlspecialchars($email_reset, ENT_QUOTES, 'UTF-8');

                echo "<h1>Password Reset Request</h1>";
                echo "<p>If the address <strong>" . $email_reset_safe . "</strong> is registered in our system, a password reset link has been sent (theoretically).</p>"; 
                echo "<p style='color:darkblue; font-style:italic;'>Note: No actual email sending or password reset functionality is implemented on this page.</p>";
                echo "<br><hr><br>";
                echo "<p><a href='forgot_password.html'>Return to Password Reset Form</a></p>";
                echo "<p><a href='login.php'>Login</a></p>";
                echo "<p><a href='index.php'>Return to Homepage</a></p>";
            } else {
                echo "<h1>Bad Request!</h1>";
                echo "<p>You are not authorized to access this page directly.</p>";
                echo "<p><a href='forgot_password.html'>Go to Password Reset Form</a></p>";
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> ReverseWeb</p>
    </footer>
</body>
</html>