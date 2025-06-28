<?php
$page_title = "Invoice Detail";
require_once "init.php";
include 'header.php';

if (!$is_user_loggedin) {
    header("location: login.php");
    exit;
}

$invoice_number = $_GET['invoice_id'] ?? '';
$invoice_details = null;

if (!empty($invoice_number)) {
    $sql = "SELECT * FROM invoices WHERE invoice_number = '" . mysqli_real_escape_string($link, $invoice_number) . "'";
    
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) == 1) {
            $invoice_details = mysqli_fetch_assoc($result);
        }
    }
}
?>

<main class="content-page" style="max-width: 700px;">
    <a href="invoices.php">&larr; Return to My Invoices</a>
    <hr>
    <?php if ($invoice_details): ?>
        <h2>Invoice Detail: <?php echo htmlspecialchars($invoice_details['invoice_number']); ?></h2>
        <p><strong>Invoice Date:</strong> <?php echo htmlspecialchars($invoice_details['invoice_date']); ?></p>
        <p><strong>Total Amount:</strong> <?php echo htmlspecialchars(number_format($invoice_details['total_amount'], 2)); ?> TL</p>
        <p><strong>Billing Email:</strong> <?php echo htmlspecialchars($invoice_details['billing_email']); ?></p>
        <div style="background-color: #f8f9fa; padding: 1em; border: 1px solid #dee2e6; margin-top: 1em;">
            <h4>Shipping Address</h4>
            <p><?php echo nl2br(htmlspecialchars($invoice_details['shipping_address'])); ?></p>
            <br><p>This page uses an API. The action is yours!</p>
        </div>
    <?php else: ?>
        <h2>Invoice Not Found</h2>
        <p>No record was found matching the requested invoice number.</p>
    <?php endif; ?>
</main>

<?php 
include 'footer.php'; 
?>