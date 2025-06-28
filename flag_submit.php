<?php

$page_title = "Bayrak Gönder";
require_once "init.php";
include 'header.php'; 
?>
<main class="auth-form">
    <h2>Zafiyet Bayrağını Gönder</h2>
    <p>Bulduğun bayrağı (FLAG) buraya girerek ilerlemeni kaydet.</p>
    <form action="flag_check.php" method="POST">
        <div>
            <label for="vuln_key">Hangi Zafiyetin Bayrağı?</label>
            <select name="vuln_key" id="vuln_key" required>
                <option value="sqli_union">SQLi - UNION Attack</option>
                <option value="sqli_search">SQLi - Search Bypass</option>
                <option value="xss_reflected">XSS - Reflected</option>
                <option value="xss_stored">XSS - Stored</option>
                <option value="file_upload">File Upload - RCE</option>
            </select>
        </div>
        <div>
            <label for="flag">Bayrak (Flag):</label>
            <input type="text" id="flag" name="flag" placeholder="FLAG{...}" required>
        </div>
        <button type="submit">Kontrol Et</button>
    </form>
    <?php if(isset($_GET['error'])): ?>
        <p class="error-text">Girdiğin bayrak yanlış veya bu zafiyet için değil!</p>
    <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
```php
