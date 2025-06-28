<?php
require_once "init.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$user_id = null;
if (isset($_COOKIE['auth_token'])) {
    $secret_key = "secret_digit";
    try {
        $decoded = JWT::decode($_COOKIE['auth_token'], new Key($secret_key, 'HS256'));
        $user_id = $decoded->data->id;
    } catch (Exception $e) {
        header("location: login.php");
        exit;
    }
} else {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $user_id) {
    $submitted_flag = trim($_POST['flag'] ?? '');
    $sql_flag_check = "SELECT vuln_key FROM flags WHERE flag_value = '" . mysqli_real_escape_string($link, $submitted_flag) . "'";
    $result_flag = mysqli_query($link, $sql_flag_check);

    if ($result_flag && mysqli_num_rows($result_flag) == 1) {
        $found_vuln = mysqli_fetch_assoc($result_flag);
        $vuln_key = $found_vuln['vuln_key'];

        $sql_get_progress = "SELECT progress FROM users WHERE id = " . (int)$user_id;
        $result_progress = mysqli_query($link, $sql_get_progress);
        $progress_data = mysqli_fetch_assoc($result_progress);
        $user_progress = $progress_data['progress'] ? json_decode($progress_data['progress'], true) : [];

        $user_progress[$vuln_key] = true;
        $updated_progress_json = json_encode($user_progress);
        $sql_update = "UPDATE users SET progress = '" . mysqli_real_escape_string($link, $updated_progress_json) . "' WHERE id = " . (int)$user_id;
        
        if (mysqli_query($link, $sql_update)) {
            $message = "Correct flag! Your progress has been saved.";
            header("location: progress.php?status=success&msg=" . urlencode($message));
        } else {
            $message = "Error: Could not save your progress.";
            header("location: progress.php?status=error&msg=" . urlencode($message));
        }
        exit;
    } else {
        $message = "The flag you entered is incorrect or invalid.";
        header("location: progress.php?status=error&msg=" . urlencode($message));
        exit;
    }
}
header("location: index.php");
exit;
?>