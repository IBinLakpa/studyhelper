function populateQuestions(sectionId) {
    // Clear existing options
    var questionDropdown = document.getElementById('question_id');
    questionDropdown.innerHTML = '<option value="" selected disabled hidden>Select Question</option>';
    
    // Fetch related question IDs from the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var questions = JSON.parse(xhr.responseText);
                // Populate the question dropdown with the fetched question IDs
                questions.forEach(function(question) {
                    var option = document.createElement('option');
                    option.value = question.id;
                    option.textContent = question.id; // You can change this to question text if needed
                    questionDropdown.appendChild(option);
                });
            } else {
                // Handle errors
                console.error('Error fetching questions:', xhr.status);
            }
        }
    };
    xhr.open('GET', '../ajax/get_related_questions.php?section_id=' + sectionId);
    xhr.send();
}