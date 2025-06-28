<?php
setcookie("auth_token", "", time() - 3600, "/");
 header("location: index.php");
exit;
?>