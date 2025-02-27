// Function to switch sections dynamically
function showSection(sectionId) {
    // Hide all sections
    let sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));

    // Show selected section
    document.getElementById(sectionId).classList.add('active');
}

// Set default active section to "home"
document.addEventListener('DOMContentLoaded', function () {
    showSection('home');
});
