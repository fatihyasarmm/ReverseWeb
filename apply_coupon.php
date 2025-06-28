<?php
// Include core files and Composer's autoloader
require_once "init.php";
include 'header.php'; // The header handles auth checks and sets $is_user_loggedin, $current_user_data

// Variables for success/error messages
$message = "";
$is_error = false;

if (!$is_user_loggedin) {
    $message = "You must be logged in to perform this action.";
    $is_error = true;
} else {
    $coupon_code = $_POST['coupon_code'] ?? '';
    $user_id = $current_user_data->id;

    if (empty($coupon_code)) {
        $message = "Please enter a coupon code.";
        $is_error = true;
    } else {

        $sql_check = "SELECT * FROM coupons WHERE coupon_code = '" . mysqli_real_escape_string($link, $coupon_code) . "' AND uses_left > 0";
        $result_check = mysqli_query($link, $sql_check);

        if ($result_check && mysqli_num_rows($result_check) > 0) {
            $coupon = mysqli_fetch_assoc($result_check);
            $amount = $coupon['discount_amount'];

            // 2. DELAY: We slow down the server artificially to make exploiting the vulnerability easier.
            sleep(2);

            $sql_use_coupon = "UPDATE coupons SET uses_left = uses_left - 1 WHERE coupon_code = '" . mysqli_real_escape_string($link, $coupon_code) . "'";
            mysqli_query($link, $sql_use_coupon);

            $sql_add_balance = "UPDATE users SET balance = balance + " . (float)$amount . " WHERE id = " . (int)$user_id;
            mysqli_query($link, $sql_add_balance);

            $message = "Congratulations! The coupon worth " . htmlspecialchars($amount) . " TL has been successfully added to your account.";
            $is_error = false;

        } else {
            $message = "Invalid or expired coupon code.";
            $is_error = true;
        }
    }
}
?>

<main class="content-page" style="max-width: 600px;">
    <h1>Coupon Usage Status</h1>
    <div style="padding: 1.5em; border-radius: 5px; border: 1px solid <?php echo $is_error ? '#f5c6cb' : '#c3e6cb'; ?>; background-color: <?php echo $is_error ? '#f8d7da' : '#d4edda'; ?>; color: <?php echo $is_error ? '#721c24' : '#155724'; ?>;">
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
    <p style="text-align:center; margin-top: 2em;">
        <a href="profile.php">Return to My Account Page</a>
    </p>
</main>

<?php
include 'footer.php';
?>