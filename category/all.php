<?php
    //category/all
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';
    
    // Fetch categories
    $stmt = $pdo->prepare("SELECT * FROM category");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
    //main content

    //left sidebar
    $left_sidebar_options='
        <aside class="sidebar-left main">
            other roots here
        </aside>
    ';
    $right_sidebar_options=
    $admin_access
        ?
            '
            <aside class="sidebar-right main">
                <a href="category/add" title="Add Category">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </aside>
            '
        :
        ''
    ;

    $title='All Categories';
    $form=false;
    $editor=false;
    $banner=true;

    //main article
    function main_article() {
        global $categories;
        $main = "
            <article class='main'>
                <a href='home'>Home</a>
                <hr>
                <h2>All Categories</h2>
                <hr>
        ";
    
        if ($categories) {
            $main .= "
                <div class='banner_container'>
                ";
                foreach ($categories as $category) {
                    $main .= "
                    <a href='category/" . htmlspecialchars($category['id']) . "'>
                        <div class='banner banner_border'>
                            <img src='". htmlspecialchars($category['cover_url']) ."' alt='".htmlspecialchars($category['name'])."'>
                            <div class='banner_text'>
                                <h2>" . htmlspecialchars($category['name']) . "</h2>
                                <span class='side_content'>
                                    # " . htmlspecialchars($category['id']) . "
                                </span>
                            </div>
                        </div>
                    </a>
                    ";
                }
            $main .= "
                </div>
            ";
        } else {
            $main .= 'No categories';
        }
    
        $main .= "</article>";
    
        return $main;
    }
    
    require '../essentials/default.php';
