<?php
require_once "init.php";
include 'header.php';

$status = $_GET['status'] ?? 'error';
$message = $_GET['msg'] ?? 'An unknown situation occurred.';

$page_title = ($status === 'success') ? "Upload Successful" : "Upload Failed";
$message_class = ($status === 'success') ? 'status-success' : 'status-error';
$heading = ($status === 'success') ? '✅ Profile Picture Updated!' : '❌ Error!';
?>

<main class="content-page" style="max-width: 700px; text-align: center;">
    <div class="<?php echo $message_class; ?>" style="padding: 2em; border-radius: 5px;">
        <h2 style="margin-top:0;"><?php echo htmlspecialchars($heading); ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
    <p style="margin-top: 2em;">
        <a href="profile.php" class="button">Return to My Account Page</a>
    </p>
</main>
<?php include 'footer.php'; ?>