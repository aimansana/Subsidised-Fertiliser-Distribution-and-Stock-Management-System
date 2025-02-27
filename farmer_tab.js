document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    if (username === "farmer123" && password === "password") {
        alert("Login Successful! Redirecting...");
        window.location.href = "farmer_dashboard.html"; // Redirect after login
    } else {
        alert("Invalid Credentials! Please try again.");
    }
});
