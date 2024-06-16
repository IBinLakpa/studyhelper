
// Configure MathJax
window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
    },
    startup: {
        ready: function() {
            MathJax.startup.defaultReady();
            document.querySelectorAll(".bbcode").forEach(function(element) {
                element.innerHTML = BBCode.parse(element.textContent);
            });
            MathJax.typesetPromise();
        }
    }
};
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".bbcode").forEach(function(element) {
        element.innerHTML = BBCode.parse(element.textContent);
    });
    document.querySelectorAll(".bb").forEach(function(element) {
        element.innerHTML = BBCode.parse(element.textContent);
    });
});
    
    function toggleDisplay(x,y=null) {
        const content = document.getElementById(x);
        content.classList.toggle('spoiler');
        if(y!=null){
            if(y.style.transform!="rotate(90deg)"){
                y.style.transform="rotate(90deg)";
            }
            else{
                y.style.transform="rotate(0)";
            }
        }
    }

// Toggle spoiler function
function toggleSpoiler(button) {
    const spoilerContent = button.nextElementSibling;
    spoilerContent.classList.toggle('spoiler');
}