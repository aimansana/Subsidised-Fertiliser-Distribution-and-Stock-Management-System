function showSection(sectionId) {
    // Hide all sections
    document.getElementById('profile').style.display = 'none';
    document.getElementById('field-officers').style.display = 'none';
    document.getElementById('requests').style.display = 'none';
    document.getElementById('analytics').style.display = 'none';

    // Show selected section
    document.getElementById(sectionId).style.display = 'block';

    // Load analytics chart when the Analytics tab is clicked
    if (sectionId === 'analytics') {
        loadAnalyticsChart();
    }
}

function loadAnalyticsChart() {
    var ctx = document.getElementById('requestChart').getContext('2d');
    
    // Destroy old chart instance if it exists
    if (window.requestChartInstance) {
        window.requestChartInstance.destroy();
    }

    window.requestChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Fertilizer Requests',
                data: [12, 19, 5], // Example data
                backgroundColor: ['orange', 'green', 'red'],
                borderColor: ['orange', 'green', 'red'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
