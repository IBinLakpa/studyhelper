<?php
    if (!$admin_access) {
        header('Location: ../home/');
        exit();
    }