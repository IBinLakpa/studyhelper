<?php
// syllabus/add.php?id=XX or syllabus/add/XX
require '../essentials/db_access.php';
require '../essentials/access_check.php';
require '../essentials/admin_access.php';

$id = (int)$_GET['id']; // Ensure ID is an integer for security

// Fetch course
$stmt = $pdo->prepare("SELECT name FROM course WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if course exists
if (!$course) {
    header('Location: ../home/');
    exit();
}

// Fetch categories
$stmt = $pdo->prepare("SELECT id, name FROM category");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generating options for the categories
$category_options = '';
if ($categories) {
    $category_options .= '<option value="">None</option>';
    foreach ($categories as $category) {
        $category_options .= "
        <option value='" . htmlspecialchars($category['id'], ENT_QUOTES) . "'>
            " . htmlspecialchars($category['name'], ENT_QUOTES) . "
        </option>";
    }
} else {
    $category_options .= '<option value="" selected disabled hidden>No categories available</option>';
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = (int)$_POST['subject'];
    $semester = trim($_POST['semester']);

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO syllabus (course_id, subject_id, semester) VALUES (:course_id, :subject_id, :semester)");
    $stmt->bindParam(':course_id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->bindParam(':semester', $semester);

    if ($stmt->execute()) {
        header('Location: /course/' . $id);
        exit();
    } else {
        $message = "Error: Could not add syllabus.";
    }
}

$title = 'Add Subject to Course';
$banner = false;
$form = true;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options = '';

function main_article() {
    global $message, $course, $category_options;
    echo '
    <form class="main" method="post" action="">
        <div class="default">
            <h3>Add Subject Syllabus to ' . htmlspecialchars($course['name'], ENT_QUOTES) . '</h3>
        </div>
        <div class="default">
            ' . htmlspecialchars($message, ENT_QUOTES) . '
        </div>
        <div class="default">
            <select name="category" id="category" onchange="loadSubjects(this.value)">
                ' . $category_options . '
            </select>
            <label for="category">Category:</label>
        </div>
        <div class="default">
            <select name="subject" id="subject" required>
                <option value="" selected disabled hidden>Select Category First</option>
            </select>
            <label for="subject">Subject:</label>
        </div>
        <div class="default">
            <input type="number" id="semester" name="semester" min="1" max="12" step="1" required>
            <label for="semester">Semester:</label>
        </div>
        <div class="default">
            <button type="submit">Save</button>
        </div>
    </form>
    <script>
        
    </script>';
}

require '../essentials/default.php';
?>
