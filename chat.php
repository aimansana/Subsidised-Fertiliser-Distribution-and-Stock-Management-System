<?php
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user message
    $userMessage = isset($_POST["message"]) ? trim($_POST["message"]) : '';

    // Prevent XSS attacks
    $userMessage = htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8');

    // Predefined responses
    $responses = [
        "What services do you offer?" => "We provide AI-powered chatbot solutions, automation tools, and customer support services.",
        "How can I contact support?" => "You can reach us via email at support@example.com or call +123456789.",
        "What are your pricing plans?" => "Our pricing varies based on your needs. We offer free, standard, and premium plans.",
        "Can I integrate this with my website?" => "Yes! Our AI chatbot can be easily integrated with any website using APIs.",
        "Farmer Registration" => "To register as a farmer, visit the official portal, fill in your details, and upload required documents for verification.",
        "How to Apply for Fertilizer Request" => "Farmers can apply for a fertilizer request by logging in to the portal, selecting their land details, and submitting a request.",
        "Check Land Details" => "You can check land details by entering your registration number in the portal's 'Land Verification' section.",
        "Field Officer" => "Field officers are responsible for verifying farmer details, land registration, and submitting fertilizer requests on behalf of farmers.",
        "default" => "🤖 I'm here to help! Please select a question from the options below."
    ];

    // Get the AI response
    $responseText = $responses[$userMessage] ?? $responses["default"];

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(["reply" => $responseText]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🤖 AI Chat Support</title>
    <style>
        body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to right, #F5F5DC, #EDE8D9);
    display: flex;
    flex-direction: column;
    align-items: center;  /* Centers everything */
    justify-content: flex-start;
    height: 100vh;
    margin: 0;
}

/* Fixing header and navigation */
header {
    width: 100%;
    position: relative;
}

nav {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #2c3e50;
    padding: 15px 5%;
}

/* Chat Container */
.chat-container {
    width: 420px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    margin-top: 20px; /* Adds spacing from navbar */
    padding-bottom: 15px;
}

/* Ensuring the chat box fits inside */
.chat-box {
    height: 320px;
    overflow-y: auto;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    background: rgba(255, 255, 255, 0.7);
}


.chat-header {
        background: linear-gradient(135deg, #6B8E23, #556B2F);
        color: white;
        text-align: center;
        padding: 18px;
        font-size: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .message {
        max-width: 80%;
        padding: 12px;
        border-radius: 12px;
        font-size: 15px;
        line-height: 1.4;
        word-wrap: break-word;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transform: translateY(10px);
        animation: fadeIn 0.3s ease-in-out forwards;
    }
    .user {
        align-self: flex-end;
        background: #6B8E23;
        color: white;
    }
    .ai {
        align-self: flex-start;
        background: #EDE8D9;
        color: black;
    }
    .chat-input {
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .question-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }
    .question-buttons button {
        background: #6B8E23;
        color: white;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease-in-out;
    }
    .question-buttons button:hover {
        background: #556B2F;
        transform: scale(1.05);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* General Styling */
    body {
            background-color: #f5f5dc; /* Light beige for warmth */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            color: #333;
        }

        /* Top Bar */
        .top-bar {
            background-color: #4A772F;
            color: #f5deb3;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2c3e50;
            padding: 15px 5%;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        nav ul li a:hover, 
        nav ul li a.active {
            background-color: #008000;
        }

        .logo img {
            height: 50px;
        }
</style>

</head>
<body>

<!-- Header Section -->
<header>
        <div class="top-bar">
            <span><i class="fas fa-landmark"></i> भारत सरकार | Government of India, Ministry of Agriculture & Farmers Welfare</span>
        </div>
        <nav>
            <div class="logo">
                <img src="images/firm_logo1.png" alt="Left Logo">
            </div>
            <ul>
                <li><a href="./index.html"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="./aboutUs.html" class="active"><i class="fas fa-info-circle"></i> About</a></li>
                <li><a href="./FarmerLogin.php"><i class="fas fa-tractor"></i> Farmer</a></li>
                <li><a href="./OfficerLogin.php"><i class="fas fa-user-tie"></i> Officer</a></li>
                <li><a href="./contact.html"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
            <div class="logo">
                <img src="images/Bharat_logo.jpg" alt="Right Logo">
            </div>
        </nav>
    </header>

    <div class="chat-container">
        <div class="chat-header">
            
            🤖 AI Chat Support
        </div>
        <div class="chat-box" id="chat-box">
            <div class="message ai">👋 Welcome! How can I assist you? Please select a question below.</div>
        </div>
        <div class="chat-input">
            <div class="question-buttons">
                <button onclick="sendMessage('What services do you offer?')">Services</button>
                <button onclick="sendMessage('How can I contact support?')">Contact Support</button>
                <button onclick="sendMessage('What are your pricing plans?')">Pricing</button>
                <button onclick="sendMessage('Can I integrate this with my website?')">Integration</button>
                <button onclick="sendMessage('Farmer Registration')">Farmer Registration</button>
                <button onclick="sendMessage('How to Apply for Fertilizer Request')">Fertilizer Request</button>
                <button onclick="sendMessage('Check Land Details')">Land Details</button>
                <button onclick="sendMessage('Field Officer')">Field Officer</button>
            </div>
        </div>
    </div>

    <script>
        function sendMessage(message) {
            let chatBox = document.getElementById("chat-box");

            // Append user message
            appendMessage("user", message);

            // Typing Indicator
            setTimeout(() => {
                let typingElement = appendMessage("ai", "🤖 Typing...");

                // Fetch AI response
                fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "message=" + encodeURIComponent(message)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.reply) {
                        typingElement.innerHTML = `<p>🤖 ${data.reply}</p>`;
                    } else {
                        typingElement.innerHTML = `<p>⚠️ Error: No response received.</p>`;
                    }
                })
                .catch((error) => {
                    typingElement.innerHTML = `<p>⚠️ Oops! Something went wrong. (${error.message})</p>`;
                });

            }, 1000);
        }

        function appendMessage(type, text) {
            let chatBox = document.getElementById("chat-box");
            let messageDiv = document.createElement("div");
            messageDiv.classList.add("message", type);
            messageDiv.innerHTML = `<p>${text}</p>`;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            return messageDiv;
        }
    </script>

</body>
</html>
