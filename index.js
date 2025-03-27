document.addEventListener("DOMContentLoaded", function () {
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        let slides = document.querySelectorAll(".slide");
        let dots = document.querySelectorAll(".dot");

        slides.forEach(slide => slide.style.display = "none");
        dots.forEach(dot => dot.classList.remove("active"));

        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1; }

        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].classList.add("active");

        setTimeout(showSlides, 3000); // Change slide every 3 seconds
    }

    // Slideshow Navigation
    window.changeSlide = function (n) {
        slideIndex += n;
        if (slideIndex < 1) slideIndex = document.querySelectorAll(".slide").length;
        if (slideIndex > document.querySelectorAll(".slide").length) slideIndex = 1;
        showSlides();
    };

    window.currentSlide = function (n) {
        slideIndex = n + 1;
        showSlides();
    };

    // Toggle Gallery
    window.toggleGallery = function () {
        let gallery = document.getElementById("gallery");
        gallery.style.display = (gallery.style.display === "block") ? "none" : "block";
    };

    // Toggle History Section
    document.getElementById("historyBtn").addEventListener("click", function() {
        var historySection = document.getElementById("historySection");
        if (historySection.classList.contains("show")) {
            historySection.classList.remove("show");
            this.innerText = "Show Our History";
        } else {
            historySection.classList.add("show");
            this.innerText = "Hide Our History";
        }
    });
    
    // Accordion Functionality
    let acc = document.querySelectorAll(".accordion");
    acc.forEach(function (btn) {
        btn.addEventListener("click", function () {
            let panel = this.nextElementSibling;
            panel.style.display = (panel.style.display === "block") ? "none" : "block";
        });
    });
});
