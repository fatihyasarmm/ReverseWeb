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
        $user_id = null;
    }
}

if ($user_id === null) {
    header("location: login.php");
    exit;
}

$upload_dir = "images/avatars/";
$error_message = "An error occurred during upload.";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar_file"]) && $_FILES["avatar_file"]["error"] == 0) {
    
    $file_name = $_FILES['avatar_file']['name'];
    $file_tmp_path = $_FILES['avatar_file']['tmp_name'];
    
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $new_file_name = $file_name;
    $target_file_path = $upload_dir . $new_file_name;
    
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif','htaccess'];

    if (!in_array($file_extension, $allowed_extensions)) {
        $error_message = "ERROR: Only JPG, JPEG, PNG & GIF files are allowed.d";
    } else {
        if (move_uploaded_file($file_tmp_path, $target_file_path)) {
            $new_avatar_path = $target_file_path;
            $sql = "UPDATE users SET avatar_path = '$new_avatar_path' WHERE id = $user_id";

            if (mysqli_query($link, $sql)) {
                $success_message = "Profile picture updated successfully!";
            } else {
                $error_message = "An error occurred while updating the database.";
            }
        } else {
            $error_message = "An error occurred while uploading the file to the server.";
        }
    }
} else {
     $error_message = "No file was selected or an error occurred during upload.";
}

mysqli_close($link);

$status = !empty($success_message) && $error_message === "An error occurred during upload." ? 'success' : 'error';
$message = ($status === 'success') ? $success_message : $error_message;

header("location: profile.php?status=" . $status . "&msg=" . urlencode($message));
exit;
?>