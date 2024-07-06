//scripts/form_ajax.js
function loadSubjects(categoryId) {
    if (categoryId === "") {
        document.getElementById("subject").innerHTML = "<option value='' selected disabled hidden>Select Category First</option>";
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/ajax/get_subjects.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (this.status === 200) {
            console.log(this.response);
            var subjects = JSON.parse(this.responseText);
            var subjectSelect = document.getElementById("subject");
            subjectSelect.innerHTML = "<option value='' selected disabled hidden>Select Subject</option>";
            subjects.forEach(function(subject) {
                var option = document.createElement("option");
                option.value = subject.id;
                option.text = subject.name;
                subjectSelect.appendChild(option);
            });
        }
    };
    xhr.send("category_id=" + encodeURIComponent(categoryId));
}
