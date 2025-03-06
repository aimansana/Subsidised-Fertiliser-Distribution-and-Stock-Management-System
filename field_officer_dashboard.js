document.addEventListener("DOMContentLoaded", function () {
    let farmers = [
        { id: "F001", name: "Ram Singh", land: "2 Acres", crop: "Wheat" },
        { id: "F002", name: "Sita Devi", land: "3 Acres", crop: "Rice" }
    ];

    let lands = [
        { id: "L101", location: "Village A", soil: "Loamy" },
        { id: "L102", location: "Village B", soil: "Sandy" }
    ];

    let requests = [
        { id: "R001", farmer: "Ram Singh", status: "Pending" },
        { id: "R002", farmer: "Sita Devi", status: "Approved" },
        { id: "R003", farmer: "Raj Kumar", status: "Rejected" }
    ];

    function switchTab(tabId) {
        document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
        document.getElementById(tabId).classList.add('active');
    }

    document.getElementById("profile-link").addEventListener("click", () => switchTab("profile-section"));
    document.getElementById("manage-farmers-link").addEventListener("click", () => switchTab("manage-farmers-section"));
    document.getElementById("manage-land-link").addEventListener("click", () => switchTab("manage-land-section"));
    document.getElementById("manage-requests-link").addEventListener("click", () => switchTab("manage-requests-section"));
    document.getElementById("analytics-link").addEventListener("click", () => {
        switchTab("analytics-section");
        updateAnalyticsTable();
        updateChart();
    });

    function updateFarmerTable(filteredFarmers = farmers) {
        const farmerList = document.getElementById("farmer-list");
        farmerList.innerHTML = "";
        filteredFarmers.forEach(farmer => {
            farmerList.innerHTML += `<tr><td>${farmer.id}</td><td>${farmer.name}</td><td>${farmer.land}</td><td>${farmer.crop}</td></tr>`;
        });
    }

    function updateLandTable(filteredLands = lands) {
        const landList = document.getElementById("land-list");
        landList.innerHTML = "";
        filteredLands.forEach(land => {
            landList.innerHTML += `<tr><td>${land.id}</td><td>${land.location}</td><td>${land.soil}</td></tr>`;
        });
    }

    function updateRequestTable() {
        const requestTable = document.getElementById("request-table-body");
        requestTable.innerHTML = "";
        requests.forEach(req => {
            requestTable.innerHTML += `<tr><td>${req.id}</td><td>${req.farmer}</td><td>${req.status}</td></tr>`;
        });
    }

    document.getElementById("add-farmer-btn").addEventListener("click", function () {
        document.getElementById("farmer-form").style.display = "block";
    });

    document.getElementById("farmer-form-submit").addEventListener("click", function () {
        let id = document.getElementById("farmer-id").value.trim();
        let name = document.getElementById("farmer-name").value.trim();
        let land = document.getElementById("farmer-land").value.trim();
        let crop = document.getElementById("farmer-crop").value.trim();

        if (id && name && land && crop) {
            farmers.push({ id, name, land, crop });
            updateFarmerTable();
            document.getElementById("farmer-form").style.display = "none";
        } else {
            alert("Please fill all fields.");
        }
    });

    document.getElementById("add-land-btn").addEventListener("click", function () {
        document.getElementById("land-form").style.display = "block";
    });

    document.getElementById("land-form-submit").addEventListener("click", function () {
        let id = document.getElementById("land-id").value.trim();
        let location = document.getElementById("land-location").value.trim();
        let soil = document.getElementById("land-soil").value.trim();

        if (id && location && soil) {
            lands.push({ id, location, soil });
            updateLandTable();
            document.getElementById("land-form").style.display = "none";
        } else {
            alert("Please fill all fields.");
        }
    });

    // ✅ **SEARCH FUNCTIONALITY FOR FARMERS**
    document.getElementById("search-farmer-btn").addEventListener("click", function () {
        let searchId = document.getElementById("search-farmer").value.trim().toUpperCase();

        if (searchId === "") {
            alert("Please enter a Farmer ID to search.");
            return;
        }

        let filteredFarmers = farmers.filter(farmer => farmer.id.toUpperCase() === searchId);

        if (filteredFarmers.length > 0) {
            updateFarmerTable(filteredFarmers);
        } else {
            alert("Farmer ID not found.");
            updateFarmerTable([]);
        }
    });

    document.getElementById("search-farmer").addEventListener("input", function () {
        if (this.value.trim() === "") {
            updateFarmerTable();
        }
    });

    // ✅ **SEARCH FUNCTIONALITY FOR LANDS**
    document.getElementById("search-land-btn").addEventListener("click", function () {
        let searchId = document.getElementById("search-land").value.trim().toUpperCase();

        if (searchId === "") {
            alert("Please enter a Land ID to search.");
            return;
        }

        let filteredLands = lands.filter(land => land.id.toUpperCase() === searchId);

        if (filteredLands.length > 0) {
            updateLandTable(filteredLands);
        } else {
            alert("Land ID not found.");
            updateLandTable([]);
        }
    });

    document.getElementById("search-land").addEventListener("input", function () {
        if (this.value.trim() === "") {
            updateLandTable();
        }
    });

    // ✅ **ANALYTICS - CHARTJS**
    function updateChart() {
        let ctx = document.getElementById("analytics-chart").getContext("2d");

        let statusCounts = { Pending: 0, Approved: 0, Rejected: 0 };
        requests.forEach(req => statusCounts[req.status]++);

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Pending", "Approved", "Rejected"],
                datasets: [{
                    label: "Fertilizer Requests",
                    data: [statusCounts.Pending, statusCounts.Approved, statusCounts.Rejected],
                    backgroundColor: ["orange", "green", "red"]
                }]
            }
        });
    }

    // ✅ **ANALYTICS - TABLE VIEW**
    document.getElementById("show-farmers").addEventListener("click", function() {
        showTableWithData("farmers");
    });

    document.getElementById("show-requests").addEventListener("click", function() {
        showTableWithData("pending");
    });

    document.getElementById("show-approved").addEventListener("click", function() {
        showTableWithData("approved");
    });

    function showTableWithData(type) {
        let table = document.getElementById("analytics-table");
        let tableBody = document.getElementById("analytics-table-body");

        // Show the table
        table.style.display = "table";

        // Clear previous data
        tableBody.innerHTML = "";

        // Example Data (Replace with actual fetched data)
        let data = [];

        if (type === "farmers") {
            data = [
                { id: "F001", name: "John Doe", status: "Farmer" },
                { id: "F002", name: "Jane Smith", status: "Farmer" }
            ];
        } else if (type === "pending") {
            data = [
                { id: "R101", name: "John Doe", status: "Pending" },
                { id: "R102", name: "Jane Smith", status: "Pending" }
            ];
        } else if (type === "approved") {
            data = [
                { id: "R201", name: "John Doe", status: "Approved" },
                { id: "R202", name: "Jane Smith", status: "Approved" }
            ];
        }

        // Populate the table with the selected data
        data.forEach(item => {
            let row = `<tr>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.status}</td>
            </tr>`;
            tableBody.innerHTML += row;
        });
    }

    updateFarmerTable();
    updateLandTable();
    updateRequestTable();
});
