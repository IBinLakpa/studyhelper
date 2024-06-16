// Function to update the input field with the selected option text
function populateName() {
    const select = document.getElementById('id');
    const input = document.getElementById('name');
    input.value = select.options[select.selectedIndex].text;
}