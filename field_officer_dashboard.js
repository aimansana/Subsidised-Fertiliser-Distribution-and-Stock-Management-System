document.addEventListener("DOMContentLoaded", function () {
    // Sidebar Toggle Functionality (for mobile screens)
    const sidebar = document.querySelector(".sidebar");
    const toggleBtn = document.createElement("button");
    toggleBtn.textContent = "☰";
    toggleBtn.classList.add("toggle-btn");
    document.body.insertBefore(toggleBtn, sidebar);
    
    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("active");
    });

    // Farmer Management (Basic CRUD)
    const farmers = [];

    function displayFarmers() {
        const farmerList = document.getElementById("farmerList");
        farmerList.innerHTML = "";

        farmers.forEach((farmer, index) => {
            const farmerItem = document.createElement("div");
            farmerItem.classList.add("farmer-item");
            farmerItem.innerHTML = `
                <p><strong>Name:</strong> ${farmer.name}</p>
                <p><strong>Land Size:</strong> ${farmer.land} acres</p>
                <button onclick="editFarmer(${index})">Edit</button>
                <button onclick="deleteFarmer(${index})" style="background:red">Delete</button>
            `;
            farmerList.appendChild(farmerItem);
        });
    }

    window.addFarmer = function () {
        const name = document.getElementById("farmerName").value.trim();
        const land = document.getElementById("farmerLand").value.trim();

        if (name === "" || land === "") {
            alert("All fields are required!");
            return;
        }

        farmers.push({ name, land });
        displayFarmers();
        document.getElementById("farmerForm").reset();
    };

    window.deleteFarmer = function (index) {
        if (confirm("Are you sure you want to delete this farmer?")) {
            farmers.splice(index, 1);
            displayFarmers();
        }
    };

    window.editFarmer = function (index) {
        const newName = prompt("Enter new farmer name:", farmers[index].name);
        const newLand = prompt("Enter new land size (acres):", farmers[index].land);

        if (newName && newLand) {
            farmers[index] = { name: newName, land: newLand };
            displayFarmers();
        }
    };
});
