// Validation function
function validate(input) {
    const regex = /^[a-zA-Z0-9\s]+$/;
    return regex.test(input);
}

function validateForm(id, name) {
    const nameInput = document.getElementById(id).value;
    if (!validate(nameInput)) {
        alert('Please enter a valid ' + name + ' name (alphabetic characters, numbers, and spaces only).');
        return false;
    }
    return true;
}
// Function to confirm deletion
function confirmDelete() {
    return confirm('Are you sure you want to delete this category?');
}

// Function to fetch subjects based on the selected category
function getSubjectList(subjectSelectId, categoryId) {
    if (categoryId) {
        fetch("../ajax/get_subject_from_category.php?category_id=" + categoryId)
            .then(response => response.json())
            .then(data => renderSubjectList(subjectSelectId, data));
    }
}

// Function to render subject list options
function renderSubjectList(subjectSelectId, subjects) {
    const subjectSelect = document.getElementById(subjectSelectId);
    subjectSelect.innerHTML = "<option value='' selected disabled hidden>Select Subject</option>";
    subjects.forEach(subject => {
        subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
    });
    subjectSelect.disabled = false;
}


// Function to render category options
function renderCategoryOptions(categories, selectedCategoryId = null) {
    let options = "<option value='' selected disabled hidden>Select Category</option>";
    
    // Check if categories is defined and not empty before iterating
    categories.forEach(category => {
        options += `<option value="${category.id}" ${category.id == selectedCategoryId ? 'selected' : ''}>${category.name}</option>`;
    });
    return options;
}

// script.js

function getSubjectList(selectId, categoryId) {
    if (categoryId == "") {
        document.getElementById(selectId).innerHTML = "<option value='' selected disabled hidden>Select Subject</option>";
        return;
    }
    
    fetch(`../ajax/get_subject_from_category.php?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            let select = document.getElementById(selectId);
            select.innerHTML = "<option value='' selected disabled hidden>Select Subject</option>";
            data.forEach(subject => {
                let option = document.createElement("option");
                option.value = subject.id;
                option.text = subject.name;
                select.add(option);
            });
        });
}

function getChapterList(selectId, subjectId) {
    if (subjectId == "") {
        document.getElementById(selectId).innerHTML = "<option value='' selected disabled hidden>Select Chapter</option>";
        return;
    }
    
    fetch(`../ajax/get_chapter_from_subject.php?subject_id=${subjectId}`)
        .then(response => response.json())
        .then(data => {
            let select = document.getElementById(selectId);
            select.innerHTML = "<option value='' selected disabled hidden>Select Chapter</option>";
            data.forEach(chapter => {
                let option = document.createElement("option");
                option.value = chapter.id;
                option.text = chapter.name;
                select.add(option);
            });
        });
}

function getChapterDetails(chapterId) {
    if (chapterId == "") {
        return;
    }

    fetch(`../ajax/get_chapter_details.php?chapter_id=${chapterId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('creditHours').value = data.creditHour;
            document.getElementById('order_no').value = data.order_no;
            document.getElementById('intro').value = data.intro;
        });
}

function getSectionList(selectId, chapterId) {
    if (chapterId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../ajax/get_section_from_chapter.php?chapter_id=' + chapterId, true);
        xhr.onload = function () {
            if (this.status == 200) {
                var sections = JSON.parse(this.responseText);
                var sectionSelect = document.getElementById(selectId);
                sectionSelect.innerHTML = '<option value="">Select a section</option>';
                sections.forEach(function(section) {
                    var option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }
}

function getSectionDetails(sectionId) {
    if (sectionId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../ajax/get_section_details.php?section_id=' + sectionId, true);
        xhr.onload = function () {
            if (this.status == 200) {
                var details = JSON.parse(this.responseText);
                document.getElementById('name').value = details.name;
                document.getElementById('order_no').value = details.order_no;
                document.getElementById('body').value = details.body;
                document.getElementById('new_category_id').value = details.category_id;
                document.getElementById('new_subject_id').value = details.subject_id;
                document.getElementById('new_chapter_id').value = details.chapter_id;
                
                document.getElementById('sectionDetails').style.display = 'block';
            }
        };
        xhr.send();
    } else {
        document.getElementById('sectionDetails').style.display = 'none';
    }
}
// document.addEventListener('DOMContentLoaded', function () {
//     // Initialize SCEditor with BBCode format
//     var textarea = document.getElementById('editor');
//     sceditor.create(textarea, {
//         format: 'bbcode',
//         style: 'https://cdn.jsdelivr.net/npm/sceditor@3/minified/themes/content/default.min.css',
//         plugins: 'mathjax',
//     });
// });