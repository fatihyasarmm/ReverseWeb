<?php
$page_title = "My Account";
require_once "init.php";
include 'header.php'; 

if ($is_user_loggedin !== true) {
    header("location: login.php");
    exit;
}

$user_id = $current_user_data->id;
$user_db_details = null;
$user_balance = 0.00;

$sql = "SELECT * FROM users WHERE id = " . (int)$user_id;
if ($result = mysqli_query($link, $sql)) {
    $user_db_details = mysqli_fetch_assoc($result);
    $user_balance = $user_db_details['balance'] ?? 0.00;
}

$status_msg = $_GET['msg'] ?? '';
$status_type = $_GET['status'] ?? '';
?>

<main class="profile-page">
    <div class="profile-header">
        <div class="profile-avatar">
            <img src="<?php echo htmlspecialchars($user_db_details['avatar_path'] ?? 'images/avatars/default.png'); ?>" alt="Profile Picture">
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user_db_details['full_name'] ?? $current_user_data->username); ?></h1>
            <p>@<?php echo htmlspecialchars($current_user_data->username); ?></p>
        </div>
    </div>
    
    <div class="upload-section">
         <form action="upload_avatar.php" method="post" enctype="multipart/form-data" class="upload-form">
            <label for="avatar_file">Change Profile Picture:</label>
            <input type="file" name="avatar_file" id="avatar_file" required>
            <input type="submit" value="Upload" class="button">
        </form>
        <?php 
            if(!empty($status_msg)){
                $status_class = $status_type == 'success' ? 'status-success' : 'status-error';
                echo '<p class="' . $status_class . '">' . htmlspecialchars($status_msg) . '</p>';
            }
        ?>
    </div>

    <div class="profile-content">
        <h3>Account Actions</h3>
        <p>Current Balance: <strong><?php echo htmlspecialchars(number_format($user_balance, 2)); ?> TL</strong></p>
        <a href="invoices.php" class="button" style="margin-right:10px;">My Invoices</a>
        <hr style="margin: 2em 0;">

        <h4>Use Gift Coupon</h4>
        <form action="apply_coupon.php" method="POST">
            <div class="form-group" style="max-width:300px;">
                <label for="coupon_code">Coupon Code:</label>
                <input type="text" id="coupon_code" name="coupon_code" placeholder="e.g., SPRING50" required>
            </div>
            <button type="submit" class="button">Use Coupon</button>
        </form>
        <hr style="margin: 2em 0;">

        <h3>Account Information</h3>
        <p>Email: <?php echo htmlspecialchars($user_db_details['email'] ?? 'not available'); ?></p>
    </div>
</main>
<?php include 'footer.php'; ?>