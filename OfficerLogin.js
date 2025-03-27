document.getElementById("officerLoginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    let officerType = document.getElementById("officerType").value;
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("errorMsg");

    if (officerType === "" || username === "" || password === "") {
        errorMsg.textContent = "All fields are required!";
        return;
    }

    // Send login data to the server using fetch()
    fetch("officerLogin.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `officerType=${encodeURIComponent(officerType)}&txtUName=${encodeURIComponent(username)}&txtPsw=${encodeURIComponent(password)}&btnLogin=1`
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("Invalid") || data.includes("Error")) {
            errorMsg.textContent = data; // Show error message from PHP
        } else {
            // Store officer type in session storage (optional)
            sessionStorage.setItem("officerType", officerType);
            sessionStorage.setItem("officerName", username);

            // Redirect to the dashboard
            window.location.href = data; // PHP should return the dashboard URL
        }
    })
    .catch(error => {
        console.error("Login Error:", error);
        errorMsg.textContent = "Something went wrong. Try again!";
    });
});
