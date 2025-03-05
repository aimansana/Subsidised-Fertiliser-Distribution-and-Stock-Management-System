document.addEventListener("DOMContentLoaded", function () {
    // Selecting navigation links
    const profileLink = document.getElementById("profile-link");
    const farmersLink = document.getElementById("manage-farmers-link");
    const landLink = document.getElementById("manage-land-link");
    const requestsLink = document.getElementById("manage-requests-link");
    const analyticsLink = document.getElementById("analytics-link");
    const logoutLink = document.getElementById("logout");

    // Selecting sections
    const sections = document.querySelectorAll(".section");
    const profileSection = document.getElementById("profile-section");
    const farmersSection = document.getElementById("manage-farmers-section");
    const landSection = document.getElementById("manage-land-section");
    const requestsSection = document.getElementById("manage-requests-section");
    const analyticsSection = document.getElementById("analytics-section");

    // Function to hide all sections and show only the selected one
    function showSection(section) {
        sections.forEach((sec) => sec.classList.remove("active"));
        section.classList.add("active");
    }

    // Event listeners for tab navigation
    profileLink.addEventListener("click", () => showSection(profileSection));
    farmersLink.addEventListener("click", () => showSection(farmersSection));
    landLink.addEventListener("click", () => showSection(landSection));
    requestsLink.addEventListener("click", () => showSection(requestsSection));
    analyticsLink.addEventListener("click", () => {
        showSection(analyticsSection);
        updateChart(); // Load the chart when Analytics is opened
    });

    // Logout Event (Temporary)
    logoutLink.addEventListener("click", () => {
        alert("Logging out...");
        window.location.href = "login.html"; // Redirect to login page
    });

    // Sample data for Farmers, Land & Requests tables
    const farmersData = [
        { name: "Ram Singh", land: "5 acres", crop: "Wheat" },
        { name: "Mohan Patel", land: "3 acres", crop: "Rice" }
    ];
    const landData = [
        { id: "L001", location: "Village A", soil: "Clay" },
        { id: "L002", location: "Village B", soil: "Sandy" }
    ];
    const requestsData = [
        { id: "R101", landId: "L001", fertilizer: "Urea", qty: "50kg", status: "Approved" },
        { id: "R102", landId: "L002", fertilizer: "DAP", qty: "30kg", status: "Pending" }
    ];

    // Function to populate a table
    function populateTable(data, tableId) {
        const tableBody = document.getElementById(tableId);
        tableBody.innerHTML = "";
        data.forEach((row) => {
            const tr = document.createElement("tr");
            Object.values(row).forEach((val) => {
                const td = document.createElement("td");
                td.textContent = val;
                tr.appendChild(td);
            });
            tableBody.appendChild(tr);
        });
    }

    // Populate the tables on page load
    populateTable(farmersData, "farmer-list");
    populateTable(landData, "land-list");
    populateTable(requestsData, "request-list");

    // Chart.js for Analytics
    let chartInstance;
    function updateChart() {
        const ctx = document.getElementById("request-chart").getContext("2d");

        if (chartInstance) {
            chartInstance.destroy(); // Destroy the existing chart before creating a new one
        }

        chartInstance = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Farmers", "Lands", "Requests"],
                datasets: [
                    {
                        label: "Total Count",
                        data: [45, 20, 55], // Dummy data
                        backgroundColor: ["#4CAF50", "#2196F3", "#FFC107"]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
