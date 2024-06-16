
// Function to fetch subject details based on the selected subject ID
function getSubjectDetails(subjectId) {
    if (subjectId) {
        fetch("../ajax/get_subject_details.php?subject_id=" + subjectId)
            .then(response => response.json())
            .then(data => renderSubjectDetails(data));
    }
}

// Function to render subject details
function renderSubjectDetails(subject) {
    console.log(subject);
    document.getElementById("name").value=subject.name;
    document.getElementById("credit").value=subject.credit;
    document.getElementById("category_id").innerHTML=renderCategoryOptions(subject.categories, subject.category_id);
    document.getElementById("prerequisite_category_id").innerHTML=renderCategoryOptions(subject.categories);
}