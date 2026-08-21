document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll(".animate-fade-in");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-show");
            }
        });
    }, {
        threshold: 0.2
    });
    elements.forEach(el => observer.observe(el));
});