<?php
$page_title = "Admin Panel";
require_once 'init.php';
include 'header.php';

$access_granted = false;
$error_message = "Only users with admin privileges can access this page.";

// 1. Check if the user is a legitimate admin via a valid token
if ($is_user_loggedin === true && isset($current_user_data->role) && $current_user_data->role === 'admin') {
    $access_granted = true;
}

// 2. If not, check for the alg:none attack
if ($access_granted === false && isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    $token_parts = explode('.', $token);

    if (count($token_parts) >= 2) {
        try {
            $header_json = base64_decode(str_replace(['-', '_'], ['+', '/'], $token_parts[0]));
            $header_data = json_decode($header_json);

            if (isset($header_data->alg) && strtolower($header_data->alg) === 'none') {
                $payload_json = base64_decode(str_replace(['-', '_'], ['+', '/'], $token_parts[1]));
                $payload_data = json_decode($payload_json);
                
                if (isset($payload_data->data->role) && $payload_data->data->role === 'admin') {
                    $access_granted = true; // Attack successful!
                } else {
                    $error_message = "Unsigned token accepted, but admin privilege not found.";
                }
            }
        } catch (Exception $e) {
            $error_message = "An error occurred while parsing the token.";
        }
    }
}
?>

<main class="content-page" style="max-width: 800px;">
    <h1>Admin Panel</h1>
    
    <?php if ($access_granted): ?>
        <div style="background-color:#d4edda; color:#155724; padding:1.5em; border:1px solid #c3e6cb; border-radius:5px; text-align: center;">
            <h2 style="margin-top:0;">✅ Access Granted!</h2>
            <p>Congratulations! You have reached admin privileges by manipulating the JWT token.</p>
            <p>This panel contains top-secret company secrets.</p>
            <hr>
            <pre style="font-size: 1.2em; font-weight: bold;">FLAG{JWT_PRIVILEGE_ESCALATION_COMPLETE}</pre>
        </div>
    <?php else: ?>
        <div style="background-color:#f8d7da; color:#721c24; padding:1.5em; border:1px solid #f5c6cb; border-radius:5px;">
            <h2 style="margin-top:0;">❌ Access Denied!</h2>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    <?php endif; ?>
</main>

<?php
include 'footer.php';
?>