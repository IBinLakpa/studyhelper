<?php
    if(!$admin_access){
        header($_SERVER["SERVER_PROTOCOL"], true, 403);
        echo "403 Forbidden - You do not have permission to access this page.";
        header('Location: ../home/');
    }