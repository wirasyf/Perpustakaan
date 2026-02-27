<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Anggota | Edutech Library</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="register-page">

    <header class="header-logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
        <span>Edutech <span class="yellow">Library</span></span>
    </header>

    <div class="wrapper">
        <div class="auth-card">
            
            <!-- ILLUSTRATION SECTION (LEFT) -->
            <div class="auth-section illustration-section">
                <img src="{{ asset('img/register2.png') }}" alt="Register Illustration" onerror="this.src='{{ asset('img/register.png') }}'">
            </div>

            <!-- FORM SECTION (RIGHT) -->
            <div class="auth-section form-section">
                
                <form action="{{ route('registerAnggota') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- FOTO UPLOAD (TOP RIGHT of FORM AREA in image) -->
                    <div class="profile-upload-container">
                        <input type="file" id="photo" name="photo_profile" accept="image/*" hidden>
                        <label for="photo" class="profile-circle" id="photoCircle">
                            <i class="fa-solid fa-camera" id="cameraIcon"></i>
                            <img id="previewImage" alt="Preview">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="reg-username">Username</label>
                        <input type="text" name="username" id="reg-username" class="form-control" placeholder="Username" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">No. Telp</label>
                        <input type="text" name="telephone" id="telephone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="reg-password">Password</label>
                        <div class="input-container">
                            <input type="password" name="password" id="reg-password" class="form-control" placeholder="Password" required>
                            <i class="fa-solid fa-eye-slash pwd-toggle"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" required>
                    </div>

                    <!-- NIS & Kelas -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nis_nisn">NIS</label>
                            <input type="text" name="nis_nisn" id="nis_nisn" class="form-control" placeholder="NIS" value="{{ old('nis_nisn') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control" placeholder="Kelas" value="{{ old('kelas') }}" required>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="reg-remember">
                            <label for="reg-remember">Ingat Saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Masuk</button>

                    <div class="auth-footer">
                        Sudah Memiliki Akun? <a href="{{ route('login.show') }}">Login</a>
                    </div>

                </form>
            </div>

        </div>
    </div>

    @if(session('success'))
    <div class="toast success show">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="toast error show">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>Pendaftaran gagal. Silakan coba lagi.</span>
    </div>
    @endif

    <script>
        // Toggle Password Visibility
        document.querySelectorAll('.pwd-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    this.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });

        // Photo Preview
        const photoInput = document.getElementById('photo');
        const previewImage = document.getElementById('previewImage');
        const cameraIcon = document.getElementById('cameraIcon');

        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    cameraIcon.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        // Auto hide toast
        setTimeout(() => {
            const toast = document.querySelector('.toast');
            if (toast) toast.classList.remove('show');
        }, 4000);
    </script>
</body>
</html>