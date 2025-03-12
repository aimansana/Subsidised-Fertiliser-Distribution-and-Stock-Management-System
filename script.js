""// Utility Functions
const toggleDisplay = (element, displayStyle = 'block') => {
    element.style.display = (element.style.display === 'none' || element.style.display === '') ? displayStyle : 'none';
};


 // Get all navigation links
const navLinks = document.querySelectorAll('nav ul li a');

// Loop through links and highlight the active one based on current page URL
navLinks.forEach(link => {
    // Check if the href matches the current page URL or if it's just the same filename
    if (window.location.pathname === link.getAttribute('href')) {
        link.classList.add('active');  // Add active class to the current link
    }
});


// Tabs Functionality
function openTab(event, tabName) {
    document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
    document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));

    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
}



// Popup Close Functionality
document.querySelectorAll('.popup-box .close-btn').forEach(btn => {
    btn.addEventListener('click', () => btn.parentElement.style.display = 'none');
});

// Display Popups Automatically After Page Loads
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        document.querySelectorAll(".popup-box").forEach(popup => popup.style.display = "block");
    }, 1000);
});

// Toggle Image Visibility
document.addEventListener("DOMContentLoaded", () => {
    const toggleButton = document.getElementById("toggle-images");
    const imageContainer = document.getElementById("image-container");

    if (toggleButton && imageContainer) {
        toggleButton.addEventListener("click", () => {
            toggleDisplay(imageContainer);
            toggleButton.textContent = imageContainer.style.display === "block" ? "Hide Images" : "Show Images";
        });
    }
});

// Show Popup on Page Load
window.onload = () => {
    toggleDisplay(document.getElementById("popup"));
    toggleDisplay(document.getElementById("popup-overlay"));
};

// Close Popup Function
function closePopup() {
    document.getElementById("popup").style.display = "none";
    document.getElementById("popup-overlay").style.display = "none";
}

function toggleHistory() {
    const history = document.getElementById("historySection");
    const button = document.querySelector("button");

    if (history.classList.contains("hidden")) {
        history.classList.remove("hidden");
        history.classList.add("opacity-100");
        button.textContent = "Hide Our History";
    } else {
        history.classList.add("hidden");
        history.classList.remove("opacity-100");
        button.textContent = "Show Our History";
    }
}
function toggleGallery() {
    const gallery = document.getElementById('gallery');
    // Toggle display between 'none' and 'block'
    gallery.style.display = gallery.style.display === 'none' ? 'block' : 'none';
}
function showGallery() {
    document.getElementById('gallery').style.display = 'block';
}
// Accordion Functionality
document.querySelectorAll(".accordion").forEach(accordion => {
    accordion.addEventListener("click", function () {
        this.classList.toggle("active");
        const panel = this.nextElementSibling;
        panel.style.maxHeight = panel.style.maxHeight ? null : `${panel.scrollHeight}px`;
    });
});
let slideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');

    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.style.display = (i === index) ? 'block' : 'none';
            dots[i].classList.toggle('active', i === index);
        });
    };

    const changeSlide = (n) => {
        slideIndex = (slideIndex + n + slides.length) % slides.length;
        showSlide(slideIndex);
    };

    const currentSlide = (n) => {
        slideIndex = n;
        showSlide(slideIndex);
    };

    // Auto-slide every 5 seconds
    setInterval(() => changeSlide(1), 5000);

    // Show the first slide initially
    showSlide(slideIndex);
