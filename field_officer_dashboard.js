
document.addEventListener("DOMContentLoaded", () => {
    // ----------------- Tab Switching -----------------
    const tabs = document.querySelectorAll('a[data-target]');
    const sections = document.querySelectorAll('section');

    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = tab.getAttribute('data-target');

            sections.forEach(section => {
                section.style.display = section.id === targetId ? 'block' : 'none';
            });
        });
    });

    // ----------------- Sample Data -----------------
    let farmers = [
        { id: 'F001', name: 'John Doe', landArea: '5 acres', cropType: 'Wheat' },
    ];
    let lands = [
        { id: 'L001', location: 'Village A', soilType: 'Loamy' },
    ];

    // ----------------- Render Table -----------------
    function renderTable(data, tableBodyId, headers) {
        const tableBody = document.getElementById(tableBodyId);
        if (!tableBody) return;
        tableBody.innerHTML = data.length
            ? data.map(item => `<tr>${headers.map(h => `<td>${item[h]}</td>`).join('')}</tr>`).join('')
            : `<tr><td colspan='${headers.length}'>No records found</td></tr>`;
    }

    renderTable(farmers, 'farmer-list', ['id', 'name', 'landArea', 'cropType']);
    renderTable(lands, 'land-list', ['id', 'location', 'soilType']);

    // ----------------- Initialize Chart -----------------
    let analyticsChart;
    const ctx = document.getElementById('analytics-chart');
    if (ctx) {
        analyticsChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Farmers', 'Lands'],
                datasets: [{
                    label: 'Count',
                    data: [farmers.length, lands.length],
                    backgroundColor: ['#4CAF50', '#6B8E23']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // ----------------- Update Chart -----------------
    function updateAnalyticsChart() {
        if (analyticsChart) {
            analyticsChart.data.datasets[0].data = [farmers.length, lands.length];
            analyticsChart.update();
        }
    }

    // ----------------- Add Farmer -----------------
    document.getElementById("add-farmer-btn")?.addEventListener("click", () => {
        const id = prompt("Enter Farmer ID:");
        const name = prompt("Enter Farmer Name:");
        const landArea = prompt("Enter Land Area:");
        const cropType = prompt("Enter Crop Type:");

        if (id && name && landArea && cropType) {
            farmers.push({ id, name, landArea, cropType });
            renderTable(farmers, 'farmer-list', ['id', 'name', 'landArea', 'cropType']);
            updateAnalyticsChart();
            alert("Farmer added successfully!");
        } else {
            alert("All fields are required!");
        }
    });

    // ----------------- Add Land -----------------
    document.getElementById("add-land-btn")?.addEventListener("click", () => {
        const id = prompt("Enter Land ID:");
        const location = prompt("Enter Location:");
        const soilType = prompt("Enter Soil Type:");

        if (id && location && soilType) {
            lands.push({ id, location, soilType });
            renderTable(lands, 'land-list', ['id', 'location', 'soilType']);
            updateAnalyticsChart();
            alert("Land added successfully!");
        } else {
            alert("All fields are required!");
        }
    });

    // ----------------- Search by ID -----------------
    document.getElementById("search-btn")?.addEventListener("click", () => {
        const searchId = prompt("Enter ID to Search:");

        const farmer = farmers.find(f => f.id === searchId);
        const land = lands.find(l => l.id === searchId);

        if (farmer) {
            alert(`Farmer Found:\nID: ${farmer.id}\nName: ${farmer.name}\nLand Area: ${farmer.landArea}\nCrop Type: ${farmer.cropType}`);
        } else if (land) {
            alert(`Land Found:\nID: ${land.id}\nLocation: ${land.location}\nSoil Type: ${land.soilType}`);
        } else {
            alert("No record found with this ID.");
        }
    });

    // ----------------- Logout Functionality -----------------
    document.getElementById("logout-button")?.addEventListener("click", () => {
        window.location.href = 'index.html';
    });

    // Show the first tab by default
    if (sections.length > 0) {
        sections.forEach((section, index) => {
            section.style.display = index === 0 ? 'block' : 'none';
        });
    }
});
