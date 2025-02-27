document.getElementById("officerLoginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    let officerType = document.getElementById("officerType").value;
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("errorMsg");

    if (officerType === "" || username === "" || password === "") {
        errorMsg.textContent = "All fields are required!";
        return;
    }

    // Dummy authentication (Replace this with real authentication later)
    if (username === "admin" && password === "password") {
        errorMsg.textContent = "";
        
        // Store officer type in session storage
        sessionStorage.setItem("officerType", officerType);
        sessionStorage.setItem("officerName", username);

        // Redirect to the main officer dashboard
        window.location.href = "officer_dashboard.html";
    } else {
        errorMsg.textContent = "Invalid username or password!";
    }
});
