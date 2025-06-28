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
    'sqli_union' => [
        'description' => "The application directly inserts user input into a SQL query, allowing an attacker to break out of the query and join it with other tables (like 'users').",
        'vulnerable_code' => '$category_id = $_GET["category_id"];'."\n".'$sql = "... WHERE p.category_id = " . $category_id;',
        'fixed_code' => '$category_id = (int)$_GET["category_id"];'."\n".'$sql = "... WHERE p.category_id = " . $category_id;'
    ],
    'xss_reflected' => [
        'description' => "User input from the search bar is echoed directly back to the page without being sanitized. This allows an attacker to inject and execute JavaScript code in the user's browser.",
        'vulnerable_code' => '$search_query = $_GET["query"];'."\n".'echo "You searched for: " . $search_query;',
        'fixed_code' => '$search_query = $_GET["query"];'."\n".'echo "You searched for: " . htmlspecialchars($search_query);'
    ],
    'xss_stored' => [
        'description' => "User input (e.g., a full name during registration) is saved to the database without sanitization. When this data is later displayed on a page, the embedded script executes.",
        'vulnerable_code' => '$fullname = $_POST["fullname"];'."\n".'$sql = "INSERT INTO users (full_name, ...) VALUES (\'$fullname\', ...)";',
        'fixed_code' => '$fullname_safe = htmlspecialchars($_POST["fullname"]);'."\n".'$sql = "INSERT INTO users (full_name, ...) VALUES (\'$fullname_safe\', ...)";'
    ],
    'file_upload' => [
        'description' => "The file upload mechanism only checks for a valid image extension but doesn't verify the file's actual content or prevent dangerous files like '.htaccess' from being uploaded.",
        'vulnerable_code' => '$allowed_extensions = [\'jpg\', \'png\', \'\'];'."\n".'if(in_array($ext, $allowed_extensions)) { move_uploaded_file(...); }',
        'fixed_code' => '// 1. Check MIME type: mime_content_type($file_path) == \'image/jpeg\''."\n".'// 2. Re-create the image: imagecreatefromjpeg/png(...)'."\n".'// 3. Store file with a new random name and fixed extension.'
    ],
    'idor_invoice' => [
        'description' => "The invoice detail page fetches an invoice by its ID from the URL, but it never checks if that invoice actually belongs to the user who is currently logged in.",
        'vulnerable_code' => '$invoice_id = $_GET["invoice_id"];'."\n".'$sql = "SELECT * FROM invoices WHERE invoice_number = \'$invoice_id\'";',
        'fixed_code' => '$invoice_id = $_GET["invoice_id"];'."\n".'$user_id = $current_user_data->id;'."\n".'$sql = "SELECT * FROM invoices WHERE invoice_number = \'$invoice_id\' AND user_id = $user_id";'
    ],
    'jwt_privesc' => [
        'description' => "The admin panel code foolishly trusts the 'alg' (algorithm) header in the JWT. An attacker can change it to 'none' and modify the payload (e.g., 'role':'admin') to bypass the signature check.",
        'vulnerable_code' => 'if ($header_data->alg === \'none\') {'."\n".'    $payload = json_decode(base64_url_decode($parts[1]));'."\n".'    // Trust the payload without signature check'."\n".'}',
        'fixed_code' => '// NEVER trust the "alg" header from the token itself.'."\n".'// Always enforce a specific algorithm during decoding.'."\n".'$decoded = JWT::decode($token, new Key($key, \'HS256\'));'
    ],
    'race_condition' => [
        'description' => "The system first checks if a coupon is valid and then, after a delay, updates the database. An attacker can send many requests simultaneously within this delay, causing all checks to pass before the coupon is marked as used.",
        'vulnerable_code' => '// 1. CHECK if coupon is valid'."\n".'sleep(2);'."\n".'// 2. ACT by using the coupon',
        'fixed_code' => '// Use a database transaction with FOR UPDATE lock'."\n".'mysqli_begin_transaction($link);'."\n".'$sql = "SELECT ... FOR UPDATE";'."\n".'// ...check and update logic...'. "\n" . 'mysqli_commit($link);'
    ]
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
$sql_vulns = "SELECT vuln_key, vuln_name FROM flags";
if ($result_vulns = mysqli_query($link, $sql_vulns)) {
    $all_vulnerabilities = mysqli_fetch_all($result_vulns, MYSQLI_ASSOC);
}
?>

<main class="content-page" style="max-width: 900px;">
    <h1>Progress Status & Flag Submission</h1>

    <div style="background-color: #e7f3fe; border-left: 5px solid #2196F3; padding: 15px; margin-bottom: 2em; color: #333;">
        <p style="margin: 0;">This page shows your progress in the challenge. Exploit vulnerabilities to find flags, then submit them here. For vulnerabilities that don't directly provide a flag, the challenge is to successfully perform the attack. Once you succeed, submit the corresponding flag from the `flags` table in the database.</p>
    </div>

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
                            <div style="flex-grow: 1; display: flex; align-items: center; justify-content: space-between;">
                                <span>
                                    <span class="status-icon"><?php echo $is_found ? '✅' : '🔒'; ?></span>
                                    <?php echo htmlspecialchars($vuln['vuln_name']); ?>
                                </span>
                                <?php if (isset($fixes_data[$vulnKey])): ?>
                                    <button class="show-fix-btn" data-vuln-key="<?php echo $vulnKey; ?>">How to fix?</button>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php if (isset($fixes_data[$vulnKey])): ?>
                        <li class="fix-details-container" data-fix-for="<?php echo $vulnKey; ?>" style="display: none; list-style-type: none;">
                            <div class="fix-details">
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
                        </li>
                        <?php endif; ?>
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
document.addEventListener('DOMContentLoaded', function() {
    const fixButtons = document.querySelectorAll('.show-fix-btn');
    
    fixButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const vulnKey = this.dataset.vulnKey;
            const targetContainer = document.querySelector('.fix-details-container[data-fix-for="' + vulnKey + '"]');

            if (!targetContainer) return;
            
            const isCurrentlyVisible = targetContainer.style.display === 'list-item';

            document.querySelectorAll('.fix-details-container').forEach(div => {
                div.style.display = 'none';
            });

            if (!isCurrentlyVisible) {
                targetContainer.style.display = 'list-item';
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>