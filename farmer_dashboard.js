document.addEventListener('DOMContentLoaded', function () {
    showSection('profile'); // Default section on load
});

// Function to switch between sections dynamically
function showSection(sectionId) {
    let sections = document.querySelectorAll('.section');
    sections.forEach(section => section.classList.remove('active'));

    document.getElementById(sectionId).classList.add('active');
}

// Handle Apply Request Form Submission
document.addEventListener('DOMContentLoaded', function () {
    let applyForm = document.querySelector('#apply-request form');

    applyForm.addEventListener('submit', function (event) {
        event.preventDefault();

        let landId = applyForm.querySelector('input[placeholder="Enter Land ID"]').value;
        let fertilizer = applyForm.querySelector('input[placeholder="Enter Fertilizer Name"]').value;
        let quantity = applyForm.querySelector('input[placeholder="Enter Quantity"]').value;

        if (landId === "" || fertilizer === "" || quantity === "") {
            alert("Please fill all fields before submitting!");
            return;
        }

        // Simulating request submission
        let requestTable = document.querySelector("#requests table");
        let newRow = requestTable.insertRow(-1);

        newRow.innerHTML = `
            <td>NEW-${Math.floor(Math.random() * 10000)}</td>
            <td>${landId}</td>
            <td>${fertilizer}</td>
            <td>${quantity} kg</td>
            <td>${new Date().toISOString().split('T')[0]}</td>
            <td>Pending</td>
        `;

        alert("Fertilizer request submitted successfully!");

        // Clear form fields
        applyForm.reset();
        showSection('requests'); // Redirect to requests section
    });
});

// Toggle sidebar for better mobile responsiveness
document.addEventListener('DOMContentLoaded', function () {
    let sidebar = document.querySelector('.sidebar');
    let toggleButton = document.createElement('button');
    
    toggleButton.innerHTML = "☰";
    toggleButton.classList.add('sidebar-toggle');
    document.body.prepend(toggleButton);

    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
    });
});
