<?php
    //institute/XX
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = $_GET['id'];
    
    // Fetch institute
    $stmt = $pdo->prepare("SELECT name,cover_url FROM institute WHERE id = ?");
    $stmt->execute([$id]);
    $institute = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if institute exists
    if (!$institute) {
        header('Location: ../home/');
        exit();
    }

    // Fetch courses
    $stmt = $pdo->prepare("SELECT id,name,level FROM course WHERE institute_id = ?");
    $stmt->execute([$id]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //main content

    //left sidebar
    $left_sidebar_options='
        <aside class="sidebar-left main">
            other institutes here
        </aside>
    ';
    $right_sidebar_options=
    $admin_access
        ?
            '
            <aside class="sidebar-right main">
                <a href="course/add/'. $id .'" title="Add Course to this Category">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
                <a href="institute/edit/'. $id .'" title="Edit Institute">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="institute/delete/'. $id .'" title="Delete Institute">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </aside>
            '
        :
        ''
    ;

    $title=$institute['name'];
    $form=false;
    $editor=false;
    $banner=true;

    //main article
    function main_article() {
        global $institute;
        global $courses;
        global $id;
        $main = "
            <article class='main'>
                <a href='institute/all'>All Institutes</a>
                <hr>
                <div class='banner'>
                    <img src='". htmlspecialchars($institute['cover_url']) ."' alt='".htmlspecialchars($institute['name'])."'>
                    <div class='banner_text'>
                        <h2>" . htmlspecialchars($institute['name']) . "</h2>
                        <span class='side_content'>
                            # " . htmlspecialchars($id) . "
                        </span>
                    </div>
                </div>
                <hr>
        ";
    
        if ($courses) {
            $main .= "
                <h3>Courses</h3>
                <div class='banner_container'>
                ";
                foreach ($courses as $course) {
                    $main .= "
                    <div class='banner_border banner_text'>
                        <a href='course/" . htmlspecialchars($course['id']) . "'>
                            " . htmlspecialchars($course['name']) . "
                        </a>
                        <div class='navigation-links'>
                            <span>" . htmlspecialchars($course['level']) . "</span>
                        </div>
                        <span class='side_content'>
                            # " . htmlspecialchars($course['id']) . "
                        </span>
                    </div>";
                }
            $main .= "
                </div>
            ";
        } else {
            $main .= 'No courses in this institute';
        }
    
        $main .= "</article>";
    
        return $main;
    }
    
    require '../essentials/default.php';
