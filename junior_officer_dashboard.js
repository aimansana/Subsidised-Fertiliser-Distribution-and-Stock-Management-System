// Approve Request Function
function approveRequest(requestId) {
    alert('Request ' + requestId + ' has been approved!');
    // Here, you can add functionality to update the database or status of the request
}

// Reject Request Function
function rejectRequest(requestId) {
    alert('Request ' + requestId + ' has been rejected!');
    // Similar to approveRequest, you can handle status updates here
}

// Filter Requests based on Search
function filterRequests() {
    let searchQuery = document.getElementById('searchField').value.toLowerCase();
    let rows = document.getElementById('request-table').getElementsByTagName('tr');
    
    for (let i = 1; i < rows.length; i++) {
        let cells = rows[i].getElementsByTagName('td');
        let farmerId = cells[0].textContent.toLowerCase();
        
        if (farmerId.indexOf(searchQuery) > -1) {
            rows[i].style.display = '';
        } else {
            rows[i].style.display = 'none';
        }
    }
}

// Request Data Analytics Chart
const ctx = document.getElementById('requestChart').getContext('2d');
const requestChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            label: 'Fertilizer Requests',
            data: [12, 19, 5], // Sample data, replace with real-time data
            backgroundColor: ['#A6D1B2', '#F39C12', '#E74C3C'],
            borderColor: ['#A6D1B2', '#F39C12', '#E74C3C'],
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
