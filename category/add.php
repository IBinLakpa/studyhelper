<?php
    // category/add.php
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';
    require '../essentials/admin_access.php';

    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['name']);
        $cover = trim($_POST['cover_url']);

        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO category (name, cover_url) VALUES (:name, :cover)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':cover', $cover);

        if ($stmt->execute()) {
            $lastId = $pdo->lastInsertId();
            header('Location: /category/'.$lastId);
        } else {
            $message = "Error: Could not add category.";
        }
    }
    $title='Add Category';
    $banner=false;
    $form=true;
    $editor=false;
    $edit_options='';
    $right_sidebar_options='';
    $left_sidebar_options='';
    function main_article(){
        echo'
        <form class="main" method="post" action="">
          <div class="default">
            <h3>Add Category</h3>
          </div>
          '.$GLOBALS['message'].'
          <div class="default">
            <input type="text" id="name" maxlength="255" name="name" pattern="[A-Za-z0-9\- ]+" required>
            <label for="name">Category Name:</label>
          </div>
          <div class="default">
            <input type="text" id="cover" maxlength="255" name="cover_url" required>
            <label for="cover_url">Cover URL:</label>
          </div>
          <div class="default">
            <button type="submit">Save?</button>
          </div>
        </form>';
    }
    require '../essentials/default.php';
