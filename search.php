<?php

header("X-XSS-Protection: 0");

$page_title = "Search Results";
require_once "init.php";

$search_query_raw = $_GET['query'] ?? '';
$search_query_temp = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $search_query_raw);
$search_query = preg_replace('/\s(onclick)\s*=/i', ' yasakli-ozellik=', $search_query_temp);

include 'header.php';
?>

<main>
    <h1>
        <span style="color: red;">A</span>ttackers 
        <span style="color: red;">T</span>ry 
        <span style="color: red;">O</span>bfuscating 
        <span style="color: red;">B</span>inary
    </h1>
    
    <div style="margin-top: 2em; margin-bottom: 2em; padding: 1em; border: 1px dashed #ccc;">
        <strong>Search Term:</strong> <?php echo $search_query; ?>
    </div>
    
    <hr>

    <script>
    const encodedFlag = "WFNTX0ZMQUdfaVNfSEVSRQ===";
    </script>
</main>

<?php
include 'footer.php';
?>