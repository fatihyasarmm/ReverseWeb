<?php
$page_title = "Progress Status";
require_once "init.php";
include 'header.php';

if (!$is_user_loggedin) {
    header("location: login.php");
    exit;
}

// Zafiyetlerin açıklamalarını ve düzeltmelerini içeren veri dizisi
$fixes_data = [
    'sqli_union' => ['description' => "The application directly inserts user input into a SQL query, allowing an attacker to break out of the query and join it with other tables (like 'users').", 'vulnerable_code' => '$category_id = $_GET["category_id"];'."\n".'$sql = "... WHERE p.category_id = " . $category_id;', 'fixed_code' => '$category_id = (int)$_GET["category_id"];'."\n".'$sql = "... WHERE p.category_id = " . $category_id;'],
    'xss_reflected' => ['description' => "User input is echoed directly back to the page without being sanitized, allowing an attacker to inject and execute JavaScript code.", 'vulnerable_code' => 'echo "You searched for: " . $_GET[\'query\'];', 'fixed_code' => 'echo "You searched for: " . htmlspecialchars($_GET[\'query\']);'],
    'xss_stored' => ['description' => "User input (e.g., a full name) is saved to the database without sanitization. When this data is later displayed, the embedded script executes.", 'vulnerable_code' => '$fullname = $_POST["fullname"];'."\n".'$sql = "INSERT ... VALUES (\'$fullname\', ...)";', 'fixed_code' => '$fullname_safe = htmlspecialchars($_POST["fullname"]);'."\n".'$sql = "INSERT ... VALUES (\'$fullname_safe\', ...)";'],
    'file_upload' => ['description' => "The upload mechanism only checks for a valid image extension but doesn't verify the file's actual content or prevent dangerous files like '.htaccess'.", 'vulnerable_code' => '$allowed = [\'jpg\', \'png\', \'\'];'."\n".'if(in_array($ext, $allowed)) { move_uploaded_file(...); }', 'fixed_code' => '// 1. Verify MIME type: mime_content_type()'."\n".'// 2. Re-create the image: imagecreatefromjpeg()'."\n".'// 3. Store with a new random name.'],
    'idor_invoice' => ['description' => "The page fetches an invoice by its ID, but it never checks if that invoice belongs to the currently logged-in user.", 'vulnerable_code' => '$invoice_id = $_GET["invoice_id"];'."\n".'$sql = "SELECT * FROM invoices WHERE invoice_number = \'$invoice_id\'";', 'fixed_code' => '$invoice_id = $_GET["invoice_id"];'."\n".'$user_id = $current_user_data->id;'."\n".'$sql = "SELECT * FROM ... WHERE ... AND user_id = $user_id";'],
    'jwt_privesc' => ['description' => "The code foolishly trusts the 'alg' header in the JWT. An attacker can change it to 'none' and modify the payload to bypass the signature check.", 'vulnerable_code' => 'if ($header->alg === \'none\') { /* Trust payload */ }', 'fixed_code' => '// Enforce a specific algorithm during decoding.'."\n".'$decoded = JWT::decode($token, new Key($key, \'HS256\'));'],
    'race_condition' => ['description' => "The system first checks if a coupon is valid and then, after a delay, uses it. An attacker can send many requests simultaneously within this delay.", 'vulnerable_code' => '// 1. CHECK if coupon is valid'."\n".'sleep(2);'."\n".'// 2. ACT by using the coupon', 'fixed_code' => '// Use a database transaction with FOR UPDATE lock.'."\n".'mysqli_begin_transaction($link);'."\n".'// ...']
];

$user_id = $current_user_data->id;
$user_progress = [];
$sql_progress = "SELECT progress FROM users WHERE id = " . (int)$user_id;
if ($result_progress = mysqli_query($link, $sql_progress)) {
    $progress_data = mysqli_fetch_assoc($result_progress);
    if ($progress_data && !empty($progress_data['progress'])) {
        $user_progress = json_decode($progress_data['progress'], true) ?: [];
    }
}
$all_vulnerabilities = [];
$sql_vulns = "SELECT vuln_key, vuln_name FROM flags ORDER BY id";
if ($result_vulns = mysqli_query($link, $sql_vulns)) {
    $all_vulnerabilities = mysqli_fetch_all($result_vulns, MYSQLI_ASSOC);
}
?>

<main class="content-page" style="max-width: 900px;">
    <h1>Progress Status & Flag Submission</h1>

    <div class="progress-container">
        <div class="vuln-cards-wrapper">
            <div class="vuln-card" style="grid-column: 1 / -1;">
                <div class="vuln-card-header"><h3>Found Vulnerabilities</h3></div>
                <ul class="vuln-list">
                    <?php foreach ($all_vulnerabilities as $vuln): 
                        $vulnKey = $vuln['vuln_key'];
                        $is_found = $user_progress[$vulnKey] ?? false;
                    ?>
                        <li>
                            <div class="vuln-item-header">
                                <span>
                                    <span class="status-icon"><?php echo $is_found ? '✅' : '🔒'; ?></span>
                                    <?php echo htmlspecialchars($vuln['vuln_name']); ?>
                                </span>
                                <?php if (isset($fixes_data[$vulnKey])): ?>
                                    <button class="show-fix-btn" data-vuln-key="<?php echo $vulnKey; ?>">How to fix?</button>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($fixes_data[$vulnKey])): ?>
                            <div class="fix-details" id="fix-<?php echo $vulnKey; ?>" style="display: none;">
                                <div class="code-comparison">
                                    <div class="code-block vulnerable">
                                        <h5>Vulnerable Code</h5>
                                        <pre><code><?php echo htmlspecialchars($fixes_data[$vulnKey]['vulnerable_code']); ?></code></pre>
                                    </div>
                                    <div class="code-block fixed">
                                        <h5>Fixed Code</h5>
                                        <pre><code><?php echo htmlspecialchars($fixes_data[$vulnKey]['fixed_code']); ?></code></pre>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <hr style="margin: 2em 0;">

    <div class="auth-form" style="max-width: 900px; margin: 0; box-shadow: none; padding: 0;">
        <h2>Submit New Flag</h2>
        <?php if(isset($_GET['status'])): ?>
            <p style="padding:10px; border-radius:5px; background-color: <?php echo $_GET['status'] == 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $_GET['status'] == 'success' ? '#155724' : '#721c24'; ?>;">
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </p>
        <?php endif; ?>
        <form action="flag_check.php" method="POST">
            <div>
                <label for="flag">Flag:</label>
                <input type="text" id="flag" name="flag" placeholder="FLAG{...}" required>
            </div>
            <button type="submit">Check</button>
        </form>
    </div>
</main>

<script>
// Bu JavaScript kodu, "How to fix?" butonlarının çalışmasını sağlar.
document.addEventListener('DOMContentLoaded', function() {
    const fixButtons = document.querySelectorAll('.show-fix-btn');
    fixButtons.forEach(button => {
        button.addEventListener('click', function() {
            const vulnKey = this.dataset.vulnKey;
            const targetDiv = document.getElementById('fix-' + vulnKey);
            
            const isCurrentlyVisible = window.getComputedStyle(targetDiv).display === 'block';

            document.querySelectorAll('.fix-details').forEach(div => {
                div.style.display = 'none';
            });

            if (!isCurrentlyVisible) {
                targetDiv.style.display = 'block';
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>