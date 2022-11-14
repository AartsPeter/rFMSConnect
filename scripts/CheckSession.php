<?php 
    require_once '../users/init.php';
    if (!securePage($_SERVER['PHP_SELF'])){
        header("HTTP/1.1 401 Unauthorized");
    }

?> 