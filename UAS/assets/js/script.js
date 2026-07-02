// Otomatis menghilangkan notifikasi alert setelah 3 detik
document.addEventListener("DOMContentLoaded", function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});

// ==========================================
// LOGIKA UTAMA FITUR DARK & LIGHT MODE
// ==========================================
document.addEventListener("DOMContentLoaded", function() {
    const toggleSwitch = document.querySelector('#darkModeToggle');
    const themeLabel = document.querySelector('#themeLabel');
    
    // 1. Cek konfigurasi tema yang tersimpan sebelumnya di browser
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

    if (currentTheme) {
        document.documentElement.setAttribute('data-theme', currentTheme);

        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
            themeLabel.innerHTML = "☀️ Light Mode";
        }
    }

    // 2. Fungsi merubah tema saat tombol saklar diklik
    toggleSwitch.addEventListener('change', function(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark'); // Simpan pilihan ke memori browser
            themeLabel.innerHTML = "☀️ Light Mode";
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light'); // Simpan pilihan ke memori browser
            themeLabel.innerHTML = "🌙 Dark Mode";
        }
    });
});


// Kode pencopot otomatis alert info sebelumnya (tetap biarkan di file js Anda)
document.addEventListener("DOMContentLoaded", function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});