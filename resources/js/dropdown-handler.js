// Tambahkan kode ini ke file JavaScript Anda
document.addEventListener('DOMContentLoaded', function() {
    const menuButton = document.getElementById('menu-button');
    const dropdownMenu = menuButton.nextElementSibling;
    let isOpen = false;

    // Toggle dropdown saat tombol menu diklik
    menuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        isOpen = !isOpen;

        if (isOpen) {
            // Tampilkan dropdown
            dropdownMenu.classList.remove('hidden');
            // Tambahkan animasi
            dropdownMenu.classList.add('transform', 'opacity-100', 'scale-100');
            dropdownMenu.classList.remove('opacity-0', 'scale-95');
            // Update ARIA
            menuButton.setAttribute('aria-expanded', 'true');
        } else {
            // Sembunyikan dropdown
            closeDropdown();
        }
    });

    // Tutup dropdown saat mengklik di luar
    document.addEventListener('click', function(e) {
        if (isOpen && !dropdownMenu.contains(e.target)) {
            closeDropdown();
        }
    });

    // Tutup dropdown saat item menu diklik
    const menuItems = dropdownMenu.querySelectorAll('[role="menuitem"]');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            closeDropdown();
        });
    });

    // Fungsi untuk menutup dropdown
    function closeDropdown() {
        isOpen = false;
        // Tambahkan animasi keluar
        dropdownMenu.classList.remove('opacity-100', 'scale-100');
        dropdownMenu.classList.add('opacity-0', 'scale-95');
        // Sembunyikan setelah animasi selesai
        setTimeout(() => {
            dropdownMenu.classList.add('hidden');
        }, 100);
        // Update ARIA
        menuButton.setAttribute('aria-expanded', 'false');
    }
});
