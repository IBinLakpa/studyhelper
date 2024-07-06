<?php
    // subject/XX or subject/?id=XX
    require '../essentials/db_access.php';
    require '../essentials/access_check.php';

    $id = $_GET['id'];
    
    // Fetch subject details
    $stmt = $pdo->prepare("SELECT * FROM subject_category WHERE id = ?");
    $stmt->execute([$id]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if subject exists
    if (!$subject) {
        header('Location: ../home/');
        exit();
    }
    
    // Fetch similar subjects
    $stmt = $pdo->prepare("SELECT id, name FROM subject WHERE category_id = ? AND id != ? LIMIT 5");
    $stmt->execute([$subject['category_id'], $id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch chapters for the subject
    $stmt = $pdo->prepare("SELECT * FROM chapter WHERE subject_id = ? ORDER BY order_no");
    $stmt->execute([$id]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Left sidebar options
    $left_sidebar_options = '
        <aside class="sidebar-left main">
            <h4>Other subjects</h4>
        '
    ;

    if ($subjects) {
        $left_sidebar_options .='
            <ul>
        ';
        foreach ($subjects as $subj) {
            $left_sidebar_options .= '
                <li>
                    <a href="subject/' . $subj['id'] . '">
                        ' . htmlspecialchars($subj['name']) . '
                    </a>
                </li>';
        }
        $left_sidebar_options .='
            </ul>
        ';
    } else {
        $left_sidebar_options .= 'No other subjects';
    }

    
    if($subject['prerequisite_id']){
        // Fetch prerequisite subject
        $stmt = $pdo->prepare("SELECT id,name FROM subject_category WHERE id = ?");
        $stmt->execute([$id]);
        $prerequisite = $stmt->fetch(PDO::FETCH_ASSOC);
        $left_sidebar_options .= '
        <hr/>
            Prerequisite:
            <a href="subject/' . $prerequisite['id'] . '">
                ' . htmlspecialchars($prerequisite['name']) . '
            </a>
        ';
    }

    $left_sidebar_options .= '
        </aside>
    ';

    // Right sidebar options (admin access)
    $right_sidebar_options = '';
    if ($admin_access) {
        $right_sidebar_options = '
            <aside class="sidebar-right main">
                <a href="chapter/add/' . $id . '" title="Add Chapter to this Subject">
                    <i class="fa-solid fa-circle-plus"></i>
                </a>
                <a href="subject/edit/' . $id . '" title="Edit Subject">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="subject/delete/' . $id . '" title="Delete Subject">
                    <i class="fa-solid fa-trash"></i>
                </a>
            </aside>';
    }

    $title = $subject['category_name'];
    $form = false;
    $editor = false;
    $banner = true;

    // Main article function
    function main_article() {
        global $subject, $chapters, $id;
        
        $main = '
            <article class="main">
                <a href="category/' . $subject['category_id'] . '">' . htmlspecialchars($subject['category_name']) . '</a>
                >>
                <a href="subject/' . $id . '">' . htmlspecialchars($subject['name']) . '</a>
                
                <hr>
                <div class="banner">
                    <img src="' . htmlspecialchars($subject['cover_url']) . '" alt="' . htmlspecialchars($subject['category_name']) . '">
                    <div class="banner_text">
                        <h2>' . htmlspecialchars($subject['name']) . '</h2>
                        <span class="side_content">Credits: ' . htmlspecialchars($subject['credits']) . '</span>
                        <span class="side_content">' . htmlspecialchars($subject['code']) . '</span>
                        <span class="side_content"># ' . htmlspecialchars($id) . '</span>
                    </div>
                </div>
                <hr>';
    
        if ($chapters) {
            $main .= '
                <h3>Chapters</h3>
                <ol>';
    
            foreach ($chapters as $chapter) {
                $main .= '
                    <li>
                        <a href="chapter/' . htmlspecialchars($chapter['id']) . '">' . htmlspecialchars($chapter['name']) . '</a>
                        <span class="side_content"># ' . htmlspecialchars($chapter['id']) . '</span>
                    </li>';
            }
    
            $main .= '
                </ol>';
        } else {
            $main .= '<p>No chapters in this subject</p>';
        }
    
        $main .= '
            </article>';
    
        return $main;
    }
    
    require '../essentials/default.php';
