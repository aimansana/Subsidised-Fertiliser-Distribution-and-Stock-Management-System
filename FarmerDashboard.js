document.addEventListener('DOMContentLoaded', function () {
    showSection('profile'); // Default section on load
});

// Function to switch between sections dynamically
function showSection(sectionId) {
    let sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));

    document.getElementById(sectionId).classList.add('active');
}

