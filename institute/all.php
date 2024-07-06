<?php
    //institute/all
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';
    
    // Fetch institutes
    $stmt = $pdo->prepare("SELECT * FROM institute");
    $stmt->execute();
    $institutes = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
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
                <a href="institute/add" title="Add Institute">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
            </aside>
            '
        :
        ''
    ;

    $title='All Institutes';
    $form=false;
    $editor=false;
    $banner=true;

    //main article
    function main_article() {
        global $institutes;
        $main = "
            <article class='main'>
                <a href='home'>Home</a>
                <hr>
                <h2>All Institutes</h2>
                <hr>
        ";
    
        if ($institutes) {
            $main .= "
                <div class='banner_container'>
                ";
                foreach ($institutes as $institute) {
                    $main .= "
                    <a href='institute/" . htmlspecialchars($institute['id']) . "'>
                        <div class='banner banner_border'>
                            <img src='". htmlspecialchars($institute['cover_url']) ."' alt='".htmlspecialchars($institute['name'])."'>
                            <div class='banner_text'>
                                <h2>" . htmlspecialchars($institute['name']) . "</h2>
                                <span class='side_content'>
                                    # " . htmlspecialchars($institute['id']) . "
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
            $main .= 'No institutes';
        }
    
        $main .= "</article>";
    
        return $main;
    }
    
    require '../essentials/default.php';
