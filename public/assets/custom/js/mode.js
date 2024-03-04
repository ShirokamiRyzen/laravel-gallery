document.addEventListener('DOMContentLoaded', function () {
    const bodyElement = document.body;
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    const lightModeIcon = document.getElementById('lightModeIcon');
    const darkModeIcon = document.getElementById('darkModeIcon');

    // Function untuk mengatur tema
    const setTheme = (darkMode) => {
        bodyElement.setAttribute('data-bs-theme', darkMode ? 'dark' : 'light');
        localStorage.setItem('darkMode', darkMode);
        if (darkMode) {
            lightModeIcon.style.display = 'none'; // Sembunyikan ikon matahari
            darkModeIcon.style.display = 'inline'; // Tampilkan ikon bulan
        } else {
            darkModeIcon.style.display = 'none'; // Sembunyikan ikon bulan
            lightModeIcon.style.display = 'inline'; // Tampilkan ikon matahari
        }
    };

    // Fungsi untuk memuat tema dari localStorage
    const loadTheme = () => {
        const darkMode = localStorage.getItem('darkMode') === 'true';
        darkModeSwitch.checked = darkMode;
        setTheme(darkMode);
        // Hapus kelas preload dari body setelah dokumen dimuat
        bodyElement.classList.remove('preload');
        // Tambahkan kelas loaded untuk menampilkan konten
        bodyElement.classList.add('loaded');
    };

    // Memuat tema saat dokumen selesai dimuat
    loadTheme();

    // Menambahkan event listener untuk switch tema
    darkModeSwitch.addEventListener('change', function () {
        setTheme(this.checked);
    });
});
