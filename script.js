// ✅ Close Popup Messages
function closePopup(id) {
    document.getElementById(id).style.display = "none";
}

// ✅ Slideshow Functionality
let index = 0;
function slideShow() {
    let slides = document.querySelectorAll(".slide");
    slides.forEach(slide => {
        slide.style.display = "none";
    });
    index++;
    if (index > slides.length) { index = 1; }
    slides[index - 1].style.display = "block";
    setTimeout(slideShow, 3000); // Change image every 3 seconds
}
slideShow();

// ✅ Open Gallery Modal
function openModal(img) {
    let modal = document.getElementById("galleryModal");
    let modalImg = document.getElementById("modalImg");
    modal.style.display = "block";
    modalImg.src = img.src;
}

// ✅ Close Gallery Modal
function closeGallery() {
    document.getElementById("galleryModal").style.display = "none";
}

// ✅ Scroll to Gallery Section
function scrollToGallery() {
    document.getElementById("gallerySection").scrollIntoView({ behavior: "smooth" });
}
