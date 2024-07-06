<?php
    //essentials/access_check.php
    session_start();
    $user_id=isset($_SESSION['user_id'])?$_SESSION['user_id']:null;
    $edit_access=isset($_SESSION['access'])?$_SESSION['access']>1:false;
    $admin_access=$edit_access?$_SESSION['access']>2:false;