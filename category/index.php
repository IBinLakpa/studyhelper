<?php
    //category/XX
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = $_GET['id'];
    
    // Fetch category
    $stmt = $pdo->prepare("SELECT name,cover_url FROM category WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if category exists
    if (!$category) {
        header('Location: ../home/');
        exit();
    }

    // Fetch subjects
    $stmt = $pdo->prepare("SELECT id,name,code,credits FROM subject WHERE category_id = ?");
    $stmt->execute([$id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //main content

    //left sidebar
    $left_sidebar_options='
        <aside class="sidebar-left main">
            other categories here
        </aside>
    ';
    $right_sidebar_options=
    $admin_access
        ?
            '
            <aside class="sidebar-right main">
                <a href="subject/add/'. $id .'" title="Add Subject to this Category">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
                <a href="category/edit/'. $id .'" title="Edit Category">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="category/delete/'. $id .'" title="Delete Category">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </aside>
            '
        :
        ''
    ;

    $title=$category['name'];
    $form=false;
    $editor=false;
    $banner=true;

    //main article
    function main_article() {
        global $category;
        global $subjects;
        $id=$GLOBALS["id"];
        $main = "
            <article class='main'>
                <a href='category/all'>All Categories</a>
                <hr>
                <div class='banner'>
                    <img src='". htmlspecialchars($category['cover_url']) ."' alt='".htmlspecialchars($category['name'])."'>
                    <div class='banner_text'>
                        <h2>" . htmlspecialchars($category['name']) . "</h2>
                        <span class='side_content'>
                            # " . htmlspecialchars($id) . "
                        </span>
                    </div>
                </div>
                <hr>
        ";
    
        if ($subjects) {
            $main .= "
                <h3>Subjects</h3>
                <div class='banner_container'>
                ";
                foreach ($subjects as $subject) {
                    $main .= "
                    <div class='banner_border banner_text'>
                        <a href='subject/" . htmlspecialchars($subject['id']) . "'>
                            " . htmlspecialchars($subject['name']) . "
                        </a>
                        <div class='navigation-links'>
                            <span>" . htmlspecialchars($subject['code']) . "</span>
                            <span class='side_content'>Credits: " . htmlspecialchars($subject['credits']) . "</span>
                        </div>
                        <span class='side_content'>
                            # " . htmlspecialchars($subject['id']) . "
                        </span>
                    </div>";
                }
            $main .= "
                </div>
            ";
        } else {
            $main .= 'No subjects in this category';
        }
    
        $main .= "</article>";
    
        return $main;
    }
    
    require '../essentials/default.php';
