<?php
    //course/XX
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = $_GET['id'];
    
    // Fetch course
    $stmt = $pdo->prepare("SELECT * FROM course_institute  WHERE id = ?");
    $stmt->execute([$id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if course exists
    if (!$course) {
        header('Location: ../home/');
        exit();
    }

    // Fetch syllabus_subject
    $stmt = $pdo->prepare("SELECT * FROM syllabus_subject WHERE course_id = ? order by semester");
    $stmt->execute([$id]);
    $syllabus_subject = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //main content

    //left sidebar
    $left_sidebar_options='
        <aside class="sidebar-left main">
            other courses here
        </aside>
    ';
    $right_sidebar_options=
    $admin_access
        ?
            '
            <aside class="sidebar-right main">
                <a href="syllabus/add/'. $id .'" title="Add Subject to this Course">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
                <a href="course/edit/'. $id .'" title="Edit Course">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="course/delete/'. $id .'" title="Delete Course">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </aside>
            '
        :
        ''
    ;

    $title=$course['name'];
    $form=false;
    $editor=false;
    $banner=true;

    //main article
    function main_article() {
        global $course;
        global $syllabus_subject;
        global $id;
        $main = "
            <article class='main'>
                <a href='institute/".htmlspecialchars($course['institute_id'])."'>".htmlspecialchars($course['institute_name'])."</a>
                <hr>
                <div class='banner'>
                    <img src='". htmlspecialchars($course['cover_url']) ."' alt='".htmlspecialchars($course['institute_name'])."'>
                    <div class='banner_text'>
                        <h2>" . htmlspecialchars($course['name']) . "</h2>
                        <span class='side_content'>
                            Level: " . htmlspecialchars($course['level']) . "
                        </span>
                        <span class='side_content'>
                            # " . htmlspecialchars($id) . "
                        </span>
                    </div>
                </div>
                <hr>
        ";
    
        if ($syllabus_subject) {
            $main .= "
                <div class='content'>
                ";
                $semester=0;
                foreach ($syllabus_subject as $syllabus) {
                    if($semester!=$syllabus['semester'] && $semester>0){
                        $main .="</ul>";
                    }
                    if($semester!=$syllabus['semester']){

                        $main .="
                        <h3>Semester ".$syllabus['semester'].":</h3>
                        ";
                        $semester=$syllabus['semester'];
                        $main .="<ul>";
                    }
                    $main .= "
                        <li>
                            <a href='subject/" . htmlspecialchars($syllabus['id']) . "'>
                                " . htmlspecialchars($syllabus['subject_name']) . "
                            </a>
                            <span class='side_content'>
                                " . htmlspecialchars($syllabus['subject_code']) . "
                            </span>
                        </li>
                    ";
                }
            $main .= "
                    </ul>
                </div>
            ";
        } else {
            $main .= 'No subjects in this course';
        }
    
        $main .= "</article>";
    
        return $main;
    }
    
    require '../essentials/default.php';
