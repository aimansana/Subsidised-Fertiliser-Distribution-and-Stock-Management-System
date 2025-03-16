// Show selected section and hide others
function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(sectionId).classList.add('active');
}

// Default view - show profile section
document.addEventListener("DOMContentLoaded", () => {
    showSection('profileSection');
});

// Stock Chart
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['District A', 'District B'],
        datasets: [{
            label: 'Stock Available',
            data: [200, 300],
            backgroundColor: ['#4CAF50', '#FFC107'],
        }]
    },
    options: {
        responsive: true,
    }
});

// Allocate Stock Function
function allocateStock(districtId) {
    let currentStock = parseInt(document.getElementById(districtId).textContent);
    if (currentStock >= 50) {
        document.getElementById(districtId).textContent = currentStock - 50;
        alert("50 units allocated.");
    } else {
        alert("Not enough stock available!");
    }
}

// Approve Request
function approveRequest() {
    alert("Request Approved.");
}

// Reject Request
function rejectRequest() {
    alert("Request Rejected.");
}

// Assign Officer
function assignOfficer() {
    const district = document.getElementById('districtSelect').value;
    const officerName = document.getElementById('officerName').value;
    if (officerName) {
        document.getElementById('assignmentResult').textContent = `${officerName} assigned to ${district}.`;
    } else {
        alert("Please enter an officer name.");
    }
}

// Logout
function logout() {
    window.location.href = "index.html";
}
