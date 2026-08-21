document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".animate-fade-in");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // entry.target.classList.toggle("animate-show", entry.isIntersecting);
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-show");
            } else {
                entry.target.classList.remove("animate-show");
            }
        });
    }, {
        threshold: 0.2
    });

    const hiddenElements = document.querySelectorAll(".animate-fade-in.hidden");
    hiddenElements.forEach(el => observer.observe(el));
    // elements.forEach(el => observer.observe(el));
});

// Tambahkan kode ini ke file JavaScript Anda
document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.querySelectorAll('.menu-button');
    let isOpen = false;

    menuButton.forEach(menubtn => {
        const dropdownMenu = menubtn.nextElementSibling;
        menubtn.addEventListener('click', function (e) {
            e.stopPropagation();
            isOpen = !isOpen;

            if (isOpen) {

                dropdownMenu.classList.remove('h-0');
                dropdownMenu.classList.add('h-[800px]');

                menubtn.setAttribute('aria-expanded', 'true');
            } else {
                dropdownMenu.classList.remove('h-[800px]');
                dropdownMenu.classList.add('h-0');
            }
        });
    })

    // Tutup dropdown saat mengklik di luar
    document.addEventListener('click', function (e) {
        if (isOpen && !dropdownMenu.contains(e.target)) {
            closeDropdown();
        }
    });

    // Tutup dropdown saat item menu diklik
    const menuItems = dropdownMenu.querySelectorAll('[role="menuitem"]');
    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            closeDropdown();
        });
    });

    function closeDropdown() {
        isOpen = false;
        // Tambahkan animasi keluar
        dropdownMenu.classList.remove('opacity-100', 'scale-100');
        dropdownMenu.classList.add('opacity-0', 'scale-95');
        menuButton.setAttribute('aria-expanded', 'false');
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Dapatkan semua section yang memiliki tab
    const sections = document.querySelectorAll('.flex.flex-col.items-center');

    sections.forEach((section, sectionIndex) => {
        // Buat ID unik untuk setiap section
        const sectionId = `section-${sectionIndex + 1}`;

        // Dapatkan tab dan content dalam section ini
        const tabs = section.querySelectorAll('.tab-link');
        const contents = section.querySelectorAll('.tab-content');

        // Update ID dan data-tab untuk menghindari konflik
        contents.forEach((content, contentIndex) => {
            const newId = `${sectionId}-tab-${contentIndex + 1}`;
            content.id = newId;
            tabs[contentIndex].setAttribute('data-tab', newId);
        });

        // Tambahkan event listener untuk setiap tab dalam section
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Reset semua tab dalam section ini saja
                tabs.forEach(t => {
                    t.classList.remove('text-black', 'active');
                    t.classList.add('text-slate-400');
                });

                // Aktifkan tab yang diklik
                this.classList.add('text-black', 'active');
                this.classList.remove('text-slate-400');

                // Sembunyikan semua content dalam section ini
                contents.forEach(content => {
                    content.classList.add('hidden');
                });

                // Tampilkan content yang sesuai
                const targetId = this.getAttribute('data-tab');
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }
            });
        });

        // Aktifkan tab pertama untuk setiap section
        if (tabs.length > 0) {
            tabs[0].click();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Get all tab sections
    const sections = document.querySelectorAll('.flex.flex-col.items-center');

    // Initialize each section
    sections.forEach((section, sectionIndex) => {
        const tabLinks = section.querySelectorAll('.tab-link');
        const tabContents = section.querySelectorAll('.tab-content');
        let isAnimating = false;

        // Add unique IDs to tab contents in this section
        tabLinks.forEach((link, tabIndex) => {
            const newId = `section${sectionIndex + 1}-tab${tabIndex + 1}`;
            link.setAttribute('data-tab', newId);
            if (tabContents[tabIndex]) {
                tabContents[tabIndex].id = newId;
            }
        });

        function fadeOut(element, duration) {
            return new Promise(resolve => {
                let opacity = 1;
                const interval = 10;
                const step = interval / duration;

                const fadeOutInterval = setInterval(() => {
                    opacity -= step;

                    if (opacity <= 0) {
                        opacity = 0;
                        element.style.opacity = '0';
                        element.classList.add('hidden');
                        clearInterval(fadeOutInterval);
                        resolve();
                    } else {
                        element.style.opacity = opacity.toString();
                    }
                }, interval);
            });
        }

        function fadeIn(element, duration) {
            return new Promise(resolve => {
                let opacity = 0;
                const interval = 10;
                const step = interval / duration;

                element.classList.remove('hidden');
                element.style.opacity = '0';

                const fadeInInterval = setInterval(() => {
                    opacity += step;

                    if (opacity >= 1) {
                        opacity = 1;
                        element.style.opacity = '1';
                        clearInterval(fadeInInterval);
                        resolve();
                    } else {
                        element.style.opacity = opacity.toString();
                    }
                }, interval);
            });
        }

        async function switchTab(e) {
            e.preventDefault();

            if (isAnimating) return;
            isAnimating = true;

            // Remove active states from all tabs in this section only
            tabLinks.forEach(link => {
                link.classList.remove('text-black');
                link.classList.add('text-slate-400');
            });

            // Add active state to clicked tab
            this.classList.remove('text-slate-400');
            this.classList.add('text-black');

            // Get the target tab content
            const tabId = this.getAttribute('data-tab');
            const targetContent = document.getElementById(tabId);

            // Find currently active content in this section only
            const activeContent = Array.from(tabContents).find(
                content => !content.classList.contains('hidden')
            );

            if (activeContent) {
                await fadeOut(activeContent, 300);
            }

            if (targetContent) {
                await fadeIn(targetContent, 300);
            }

            isAnimating = false;
        }

        // Add click event listeners to all tabs in this section
        tabLinks.forEach(link => {
            link.addEventListener('click', switchTab);
        });

        // Set initial active tab for this section
        if (tabLinks.length > 0) {
            tabLinks[0].click();
        }
    });

    // Add scroll animation observer
    const animatedSections = document.querySelectorAll('.animate-fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-show');
            }
        });
    });

    animatedSections.forEach(section => {
        observer.observe(section);
    });
});