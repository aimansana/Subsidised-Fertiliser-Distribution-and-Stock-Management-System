document.addEventListener("DOMContentLoaded", function() {
    let ctx = document.getElementById("requestsChart").getContext("2d");
    
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Total Requests", "Pending", "Approved", "Rejected"],
            datasets: [{
                label: "Requests Overview",
                data: [150, 30, 100, 20],
                backgroundColor: ["#3498db", "#f39c12", "#2ecc71", "#e74c3c"]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    fetch("off3.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("profile-img").src = data.profile_image || "default.jpg";
            document.getElementById("officer-img").src = data.profile_image || "default.jpg";
            document.getElementById("officer-name").textContent = data.name;
            document.getElementById("officer-name-detail").textContent = data.name;
            document.getElementById("officer-designation").textContent = data.designation;
            document.getElementById("officer-email").textContent = data.email;
            document.getElementById("officer-phone").textContent = data.phone;
        })
        .catch(error => console.error("Error fetching officer data:", error));
});
