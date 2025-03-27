document.getElementById("analytics").style.display = "block";

document.addEventListener("DOMContentLoaded", function () {
    // Function to switch sections
    function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });
        let targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.style.display = 'block';
        }
    }

    // Attach event listeners to sidebar links
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent page reload
            let sectionId = this.dataset.section; // Fetch section ID from data attribute
            showSection(sectionId);
        });
    });

    // Show the first section by default
    let firstSection = document.querySelector('.section');
    if (firstSection) {
        firstSection.style.display = 'block';
    }

    // Read the JSON data from the script tag
    const chartDataElement = document.getElementById("chart-data");
    if (chartDataElement) {
        const chartData = JSON.parse(chartDataElement.textContent);

        // Extract values
        const officerLabels = chartData.officerLabels;
        const requestData = chartData.requestData;
        const farmerData = chartData.farmerData;
        const statusLabels = chartData.statusLabels;
        const statusData = chartData.statusData;

        // Chart configuration
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false, // Prevents oversized graphs
            scales: { y: { beginAtZero: true } }
        };

        // Function to create a chart if the element exists
        function createChart(canvasId, labels, data, labelName, backgroundColor) {
            let canvas = document.getElementById(canvasId);
            if (canvas) {
                let ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: labelName,
                            data: data,
                            backgroundColor: backgroundColor
                        }]
                    },
                    options: chartOptions
                });
            }
        }

        // Initialize all charts
        createChart('requestsChart', officerLabels, requestData, 'Number of Requests', 'blue');
        createChart('farmersChart', officerLabels, farmerData, 'Number of Farmers', 'green');
        createChart('statusChart', statusLabels, statusData, 'Request Status', ['green', 'blue', 'red', 'orange', 'purple']);
    }
});
document.querySelector('a[href="logout.php"]').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'logout.php';
});

