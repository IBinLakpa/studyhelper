<?php
    if (!$edit_access) {
        header('Location: ../home/');
        exit();
    }