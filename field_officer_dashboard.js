document.addEventListener("DOMContentLoaded", function () {
    // **TABS MANAGEMENT**
    const sections = document.querySelectorAll(".section");
    const sidebarLinks = document.querySelectorAll(".sidebar ul li a");

    sidebarLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("data-target");
            showSection(targetId);
        });
    });

    function showSection(sectionId) {
        sections.forEach(section => section.classList.remove("active"));
        document.getElementById(sectionId)?.classList.add("active");
    }

    // **DUMMY DATA**
    const farmersData = [
        { id: "F201", name: "Raj Sharma", land: "5 acres", crop: "Wheat" },
        { id: "F202", name: "Amit Verma", land: "3 acres", crop: "Rice" },
        { id: "F203", name: "Suresh Kumar", land: "4 acres", crop: "Maize" }
    ];

    const requestsData = [
        { id: "R101", farmer: "Raj Sharma", status: "Pending" },
        { id: "R102", farmer: "Amit Verma", status: "Approved" },
        { id: "R103", farmer: "Suresh Kumar", status: "Pending" }
    ];

    // **TABLE RENDERING FUNCTION**
    function renderTable(tableId, data, headers) {
        const tableBody = document.getElementById(tableId);
        if (!tableBody) return;

        tableBody.innerHTML = data.length
            ? data.map(item => `<tr>${headers.map(header => `<td>${item[header]}</td>`).join("")}</tr>`).join("")
            : "<tr><td colspan='3'>No records found</td></tr>";
    }

    // **LOAD INITIAL DATA**
    renderTable("farmer-list", farmersData, ["id", "name", "land", "crop"]);
    renderTable("request-table-body", requestsData, ["id", "farmer", "status"]);

    // **TOGGLE FORMS**
    function setupFormToggle(buttonId, formId) {
        const button = document.getElementById(buttonId);
        const form = document.getElementById(formId);
        if (!button || !form) return;

        button.addEventListener("click", () => form.style.display = "block");
        form.querySelector(".close-btn").addEventListener("click", () => form.style.display = "none");
    }

    setupFormToggle("add-farmer-btn", "add-farmer-form");
    setupFormToggle("search-farmer-btn", "search-farmer-form");

    // **ADD FARMER FUNCTIONALITY**
    document.getElementById("submit-farmer")?.addEventListener("click", function () {
        const name = document.getElementById("farmer-name").value.trim();
        const land = document.getElementById("farmer-land").value.trim();
        const crop = document.getElementById("farmer-crop").value.trim();

        if (!name || !land || !crop) {
            alert("Please fill in all fields.");
            return;
        }

        farmersData.push({ id: `F${farmersData.length + 201}`, name, land, crop });
        renderTable("farmer-list", farmersData, ["id", "name", "land", "crop"]);
        document.getElementById("add-farmer-form").style.display = "none";
    });

    // **SEARCH FARMER FUNCTIONALITY**
    document.getElementById("submit-search")?.addEventListener("click", function () {
        const searchId = document.getElementById("search-farmer-id").value.trim();
        const farmer = farmersData.find(f => f.id === searchId);

        alert(farmer ? `Farmer Found!\nName: ${farmer.name}\nLand: ${farmer.land}\nCrop: ${farmer.crop}` : "Farmer not found!");
        document.getElementById("search-farmer-form").style.display = "none";
    });

    // **LOGOUT**
    document.getElementById("logout")?.addEventListener("click", function () {
        alert("Logging out...");
        window.location.href = "login.html";
    });

    // **SET DEFAULT ACTIVE SECTION**
    showSection("profile-section");
});
