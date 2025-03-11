// Function to switch sections
function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(section => {
        section.style.display = 'none';
    });
    document.getElementById(sectionId).style.display = 'block';
}

// Function to approve request
function approveRequest(button) {
    let row = button.closest("tr");
    row.cells[2].innerHTML = '<i class="fas fa-check-circle"></i> Approved';
    button.parentElement.innerHTML = '<button disabled class="disabled-btn"><i class="fas fa-ban"></i> N/A</button>';
}

// Function to reject request
function rejectRequest(button) {
    let row = button.closest("tr");
    row.cells[2].innerHTML = '<i class="fas fa-times-circle"></i> Rejected';
    button.parentElement.innerHTML = '<button disabled class="disabled-btn"><i class="fas fa-ban"></i> N/A</button>';
}

// Chart.js Analytics
let ctx = document.getElementById('requestsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            data: [12, 7, 3],
            backgroundColor: ['green', 'blue', 'red']
        }]
    }
});
