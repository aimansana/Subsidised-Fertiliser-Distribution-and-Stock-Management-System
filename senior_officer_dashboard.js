// Request Trends - Bar Chart
var ctx = document.getElementById('requestTrendsChart').getContext('2d');
var requestTrendsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
            label: 'Approved Requests',
            data: [120, 150, 170, 180, 200],
            backgroundColor: '#4CAF50',
        }, {
            label: 'Pending Requests',
            data: [40, 30, 50, 60, 70],
            backgroundColor: '#FFC107',
        }, {
            label: 'Rejected Requests',
            data: [10, 15, 5, 8, 12],
            backgroundColor: '#F44336',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// Stock Management: Allocate stock dynamically
let availableStock = 500;

function allocateStock() {
    if (availableStock > 0) {
        availableStock -= 50; // Allocate 50 units
        document.getElementById('availableStock').textContent = availableStock;
        alert("50 units allocated to Junior Officers.");
    } else {
        alert("No stock available!");
    }
}
