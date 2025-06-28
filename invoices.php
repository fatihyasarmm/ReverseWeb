<?php
$page_title = "My Invoices";
require_once "init.php";
include 'header.php';

if (!$is_user_loggedin) {
    header("location: login.php");
    exit;
}

$user_id = $current_user_data->id;
$invoices = [];
$sql = "SELECT invoice_number, invoice_date, total_amount FROM invoices WHERE user_id = " . (int)$user_id . " ORDER BY invoice_date DESC";

if ($result = mysqli_query($link, $sql)) {
    $invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<main class="content-page" style="max-width: 800px;">
    <h1>My Order Invoices</h1>
    <?php if (empty($invoices)): ?>
        <p>You do not have any saved invoices yet.</p>
    <?php else: ?>
        <table style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align:left;">Invoice Number</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align:left;">Date</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <a href="invoice_detail.php?invoice_id=<?php echo htmlspecialchars($invoice['invoice_number']); ?>">
                                <?php echo htmlspecialchars($invoice['invoice_number']); ?>
                            </a>
                        </td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($invoice['invoice_date']); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd; text-align:right;"><?php echo htmlspecialchars(number_format($invoice['total_amount'], 2)); ?> TL</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>