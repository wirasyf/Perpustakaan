<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Perpustakaan Digital SMKN 4 Bojonegoro - Pusat literasi dan sumber belajar untuk membentuk generasi cerdas">
    <title>Perpustakaan Digital SMKN 4 Bojonegoro</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header & Navigation -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
                    <span class="logo-text">
                        <span class="edutech">Edutech</span> <span class="library">Library</span>
                    </span>
                </div>
                <div class="nav-container">
                    <ul class="nav-menu">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#about">Tentang</a></li>
                        <li><a href="#services">Layanan</a></li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn-nav">Masuk</a>
                </div>
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="title-blue">Perpustakaan Digital</span>
                        <span class="title-black">SMKN 4 Bojonegoro</span>
                    </h1>
                    <p class="hero-description">Belajar jadi lebih mudah dengan Perpustakaan Digital SMKN 4 Bojonegoro. Temukan buku favoritmu, lakukan peminjaman, dan pantau riwayat bacaan hanya dalam beberapa klik. Semua dirancang dengan kebutuhan yang cepat, nyaman, dan sesuai dengan kebutuhan siswa di era digital.</p>
                    <div class="hero-buttons">
                        <a href="#about" class="btn-primary">
                            Mulai <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('registerAnggota.show') }}" class="btn-outline">
                            Daftar
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="{{ asset('img/hero.png') }}" alt="Siswa membaca di perpustakaan" class="hero-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Perpustakaan Digital (Layout: Image Left, Text Right) -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="{{ asset('img/detail.png') }}" alt="Mengenal Perpustakaan Digital">
                </div>
                <div class="about-text">
                    <h2 class="section-title">Mengenal Perpustakaan Digital</h2>
                    <p>Perpustakaan Digital SMKN 4 Bojonegoro merupakan sistem layanan perpustakaan berbasis digital yang dirancang untuk meningkatkan efisiensi pengelolaan dan akses informasi di lingkungan sekolah. Melalui platform ini, kamu dapat melakukan pencarian koleksi, peminjaman, serta pemantauan status buku secara sistematis dan terintegrasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Perpustakaan Digital -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <h2>Layanan Perpustakaan Digital</h2>
                <p>Nikmati berbagai layanan perpustakaan digital yang dirancang untuk memudahkan akses informasi dan mendukung kegiatan belajar secara efisien.</p>
            </div>
            <div class="services-grid">
                <!-- Peminjaman -->
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <h3>Peminjaman Buku</h3>
                    <p>Mengelola proses peminjaman buku oleh anggota, mulai dari pencatatan data hingga penentuan tanggal pengembalian.</p>
                </div>
                <!-- Pengembalian -->
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3>Pengembalian Buku</h3>
                    <p>Mengatur proses pengembalian buku yang dipinjam serta memperbarui status ketersediaan buku di sistem.</p>
                </div>
                <!-- Pengelolaan -->
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-plus-square"></i>
                    </div>
                    <h3>Pengelolaan Buku</h3>
                    <p>Mengelola data koleksi buku seperti menambah, mengubah, atau menghapus informasi buku dalam perpustakaan.</p>
                </div>
                <!-- Kunjungan -->
                <div class="service-card">
                    <div class="icon-box">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Kunjungan Perpustakaan</h3>
                    <p>Mencatat dan memantau data kunjungan anggota yang datang ke perpustakaan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Register CTA Section -->
    <section class="register" id="register">
        <div class="container">
            <div class="register-content">
                <div class="register-text">
                    <h2 class="section-title">Jadilah Bagian dari Anggota Perpustakaan Kami!</h2>
                    <p>Daftarkan dirimu sebagai anggota perpustakaan dan nikmati kemudahan meminjam buku, akses koleksi lengkap, serta pengalaman belajar yang lebih nyaman dan terorganisir.</p>
                    <a href="{{ route('registerAnggota.show') }}" class="btn-outline">
                        Daftar
                    </a>
                </div>
                <div class="register-image">
                    <img src="{{ asset('img/register.png') }}" alt="Daftar Anggota">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-new">
        <div class="container">
            <div class="footer-top-wrap">
                <div class="footer-left">
                    <div class="footer-logo">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="footer-logo-img">
                        <div class="footer-logo-text">
                            Edutech Liblary
                        </div>
                    </div>
                    <div class="footer-brand-title">Perpustakaan Digital SMK Negeri 4 Bojonegoro</div>
                    <p class="footer-description">
                        Tingkatkan minat baca, perluas wawasan, dan<br>
                        dukung prestasi belajar dengan memanfaatkan<br>
                        layanan Perpustakaan SMKN 4 Bojonegoro.
                    </p>
                    <div class="footer-social-new">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-right">
                    <div class="footer-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jalan Raya Surabaya, Sukowati, Kab Bojonegoro 62181</span>
                    </div>
                    <div class="footer-info-item-noicon">
                        <span>Senin - Jumat: 07.30 - 15.30 WIB</span>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom-new">
                <p>&copy; 2026 Perpustakaan SMKN 4 Bojonegoro - Membangun Generasi Cerdas Dan Literat</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#home" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // Hamburger menu toggle
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        const navContainer = document.querySelector('.nav-container');

        if (hamburger) {
            hamburger.addEventListener('click', () => {
                navContainer.classList.toggle('active');
            });
        }

        // Close menu on link click
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navContainer.classList.remove('active');
            });
        });

        // Back to top button
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });

        // Header shadow on scroll
        const header = document.querySelector('.header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Active link on scroll (Intersection Observer)
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-menu a');

        const options = {
            threshold: 0,
            rootMargin: '-150px 0px -70% 0px' // Detect when section is near top
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, options);

        sections.forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>