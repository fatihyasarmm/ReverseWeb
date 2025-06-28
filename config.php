<?php

define('DB_SERVER', 'db');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root_password'); 
define('DB_NAME', 'reverseweb_db');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("ERROR: Could not connect to the database. " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8mb4");
?>