document.addEventListener("DOMContentLoaded", () => {
    // ----------------- Tab Switching -----------------
    const tabs = document.querySelectorAll('a[data-target]');
    const sections = document.querySelectorAll('section');

    function switchTab(targetId) {
        sections.forEach(section => {
            section.style.display = section.id === targetId ? 'block' : 'none';
        });
        tabs.forEach(tab => tab.classList.toggle('active', tab.getAttribute('data-target') === targetId));
    }

    // Handle tab switching and URL hash update
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = tab.getAttribute('data-target');
            switchTab(targetId);
            window.location.hash = targetId; // Update URL
        });
    });

    // Set default tab based on URL hash or first tab
    const initialTab = window.location.hash.substring(1) || tabs[0].getAttribute('data-target');
    switchTab(initialTab);

    // ----------------- Initialize Chart -----------------
    const ctx = document.getElementById('analytics-chart');
    if (ctx) {
        ctx.width = 600;
        ctx.height = 400;
    }
    
    let analyticsChart;

    function initAnalyticsChart() {
        if (ctx) {
            analyticsChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Farmers', 'Lands', 'Fertilizer Requests'],
                    datasets: [{
                        label: 'Total Count',
                        data: [farmerCount, landCount, requestCount],
                        backgroundColor: ['#4CAF50', '#6B8E23', '#FFA500']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    }

    initAnalyticsChart();

    // ----------------- Update Chart -----------------
    async function fetchUpdatedData() {
        try {
            const response = await fetch('/path-to-api-endpoint'); // Replace with actual API
            const data = await response.json();
            
            farmerCount = data.farmer_count || 0;
            landCount = data.land_count || 0;
            requestCount = data.request_count || 0;
            
            if (analyticsChart) {
                analyticsChart.data.datasets[0].data = [farmerCount, landCount, requestCount];
                analyticsChart.update();
            }
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    }
    
    // Auto-refresh every 60 seconds
    setInterval(fetchUpdatedData, 60000);
});
