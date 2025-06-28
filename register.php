<?php
require_once "init.php";
$page_title = "Create Account";

$fullname = $email = $username = $password = "";
$username_err = $email_err = $password_err = $general_err = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Username validation
    if (empty(trim($_POST["new_username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $sql_check_username = "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($link, $_POST["new_username"]) . "'";
        if ($result_check = mysqli_query($link, $sql_check_username)) {
            if (mysqli_num_rows($result_check) == 1) { $username_err = "This username is already taken."; } 
            else { $username = trim($_POST["new_username"]); }
        } else { $general_err = "Database query error. Please try again."; }
    }
    
    // Email validation
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";
    } else {
        $sql_check_email = "SELECT id FROM users WHERE email = '" . mysqli_real_escape_string($link, $_POST["email"]) . "'";
        if ($result_email = mysqli_query($link, $sql_check_email)) {
            if (mysqli_num_rows($result_email) == 1) { $email_err = "This email address is already in use."; } 
            else { $email = trim($_POST["email"]); }
        } else { $general_err = "An error occurred during email check."; }
    }

    // Password validation
    if (empty(trim($_POST["new_password"]))) { $password_err = "Please enter a password."; } 
    elseif (strlen(trim($_POST["new_password"])) < 6) { $password_err = "Password must have at least 6 characters."; } 
    else { $password = $_POST["new_password"]; }
    if (empty($password_err) && ($password != $_POST["confirm_password"])) { $password_err = "Passwords did not match."; }
    
    // Get full name (this is where the Stored XSS payload will come from)
    $fullname = trim($_POST["fullname"]);

    // If there are no errors, insert into database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($general_err)) {
        
        // --- GÜVENLİK DÜZELTMESİ BURADA ---
        // Stored XSS'in çalışabilmesi için, SQL Injection'ı önlememiz gerekiyor.
        // Bu fonksiyon, tek tırnak gibi özel karakterleri güvenli hale getirir.
        $fullname_safe = mysqli_real_escape_string($link, $fullname);
        $email_safe = mysqli_real_escape_string($link, $email);
        $username_safe = mysqli_real_escape_string($link, $username);
        $password_safe = mysqli_real_escape_string($link, $password); // Passwords should be hashed in a real app

        $sql_insert = "INSERT INTO users (full_name, email, username, password, role) VALUES ('$fullname_safe', '$email_safe', '$username_safe', '$password_safe', 'user')";

        if (mysqli_query($link, $sql_insert)) {
            header("location: login.php");
            exit;
        } else {
            $general_err = "An error occurred during registration: " . mysqli_error($link);
        }
    }
}
include 'header.php';
?>
<main class="auth-form">
    <h2>Create Account</h2>
    <?php if(!empty($general_err)){ echo '<div class="form-error">' . htmlspecialchars($general_err) . '</div>'; } ?>
    <form action="register.php" method="POST">
        <div><label for="fullname">Full Name:</label><input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required></div>
        <div><label for="email">Email:</label><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><span class="error-text"><?php echo $email_err; ?></span></div>
        <div><label for="new_username">Username:</label><input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($username); ?>" required><span class="error-text"><?php echo $username_err; ?></span></div>
        <div><label for="new_password">Password:</label><input type="password" id="new_password" name="new_password" required></div>
        <div><label for="confirm_password">Confirm Password:</label><input type="password" id="confirm_password" name="confirm_password" required><span class="error-text"><?php echo $password_err; ?></span></div>
        <button type="submit">Create Account</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</main>
<?php include 'footer.php'; ?>