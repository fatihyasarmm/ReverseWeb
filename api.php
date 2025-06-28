<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');
$action = $_GET['action'] ?? '';

if ($action === 'get_user') {
    $user_id_to_get = $_GET['id'] ?? 0;
    $sql = "SELECT id, full_name, email, username, role FROM users WHERE id = " . (int)$user_id_to_get;
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) == 1) {
            echo json_encode(['status' => 'success', 'data' => mysqli_fetch_assoc($result)]);
            exit;
        }
    }
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'User not found']);

} elseif ($action === 'get_all_invoices_for_admin') {

    $all_invoices = [];
    $sql = "SELECT user_id, invoice_number FROM invoices";
    if ($result = mysqli_query($link, $sql)) {
        $all_invoices = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $all_invoices]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
    }

} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

mysqli_close($link);
exit;
?>