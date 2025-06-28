<?php
$page_title = "Homepage - ReverseWeb";
require_once "init.php";
include 'header.php'; 

$user_progress = [
    'sqli_login' => false, 'sqli_union' => false, 'xss_reflected' => false,
    'xss_stored' => false, 'file_upload' => false, 'jwt_privesc' => false,
    'idor_invoice' => false, 'race_condition' => false
];
if ($is_user_loggedin) {
    $user_id = $current_user_data->id;
    $sql_progress = "SELECT progress FROM users WHERE id = " . (int)$user_id;
    if ($result_progress = mysqli_query($link, $sql_progress)) {
        $progress_data = mysqli_fetch_assoc($result_progress);
        if ($progress_data && !empty($progress_data['progress'])) {
            $decoded_progress = json_decode($progress_data['progress'], true);
            if (is_array($decoded_progress)) {
                $user_progress = array_merge($user_progress, $decoded_progress);
            }
        }
    }
}
$categories = [];
$sql_categories = "SELECT id, name FROM categories ORDER BY name ASC";
if($result_cat = mysqli_query($link, $sql_categories)){
    $categories = mysqli_fetch_all($result_cat, MYSQLI_ASSOC);
}

$sql_products = "SELECT p.id, p.name, p.price, p.image_path FROM products p";
if (isset($_GET['category_id']) && $_GET['category_id'] !== '') {
    $category_id = $_GET['category_id'];
    $sql_products .= " WHERE p.category_id = " . $category_id; 
}
$products_to_display = [];
if($result_prod = mysqli_query($link, $sql_products)){
    $products_to_display = mysqli_fetch_all($result_prod, MYSQLI_ASSOC);
}
?>

<main>
    <div class="progress-container">
        <h2>Vulnerability Progress</h2>
        <div class="vuln-cards-wrapper">
            <div class="vuln-card">
                <div class="vuln-card-header"><h3>SQL Injection</h3></div>
                <ul class="vuln-list">
                    <li class="<?php echo ($user_progress['sqli_union']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['sqli_union']) ? '✅' : '🔒'; ?></span> Level 1: UNION Attack</li>
                </ul>
            </div>
            <div class="vuln-card">
                <div class="vuln-card-header"><h3>Cross-Site Scripting</h3></div>
                <ul class="vuln-list">
                    <li class="<?php echo ($user_progress['xss_reflected']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['xss_reflected']) ? '✅' : '🔒'; ?></span> Reflected XSS</li>
                    <li class="<?php echo ($user_progress['xss_stored']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['xss_stored']) ? '✅' : '🔒'; ?></span> Stored XSS</li>
                </ul>
            </div>
            <div class="vuln-card">
                <div class="vuln-card-header"><h3>Other Vulnerabilities</h3></div>
                <ul class="vuln-list">
                    <li class="<?php echo ($user_progress['file_upload']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['file_upload']) ? '✅' : '🔒'; ?></span> File Upload (RCE)</li>
                    <li class="<?php echo ($user_progress['jwt_privesc']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['jwt_privesc']) ? '✅' : '🔒'; ?></span> JWT Privilege Escalation</li>
                    <li class="<?php echo ($user_progress['idor_invoice']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['idor_invoice']) ? '✅' : '🔒'; ?></span> IDOR</li>
                    <li class="<?php echo ($user_progress['race_condition']) ? 'found' : ''; ?>"><span class="status-icon"><?php echo ($user_progress['race_condition']) ? '✅' : '🔒'; ?></span> Race Condition</li>
                </ul>
            </div>
        </div>
    </div>
    
    <nav class="categories">
        <?php foreach ($categories as $category): ?>
            <a href="index.php?category_id=<?php echo htmlspecialchars($category['id']); ?>"><?php echo htmlspecialchars($category['name']); ?></a>
        <?php endforeach; ?>
        <a href="index.php">All Products</a>
    </nav>

    <div class="product-grid">
        <?php foreach ($products_to_display as $row): ?>
            <div class="product-card">
                <?php if(!empty($row['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p class="price"><?php echo htmlspecialchars($row['price']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php 
include 'footer.php'; 
?>