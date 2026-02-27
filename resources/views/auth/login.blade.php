<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Edutech Library</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="login-page">

    <header class="header-logo">
        <img src="{{ asset('img/logo.png') }}" alt="Logo">
        <span>Edutech <span class="yellow">Library</span></span>
    </header>

    <div class="wrapper">
        <div class="auth-card">
            
            <!-- FORM SECTION (LEFT) -->
            <div class="auth-section form-section">
                <h2>Login</h2>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- USERNAME --}}
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username"
                            class="form-control {{ $errors->has('username') ? 'error' : '' }}" 
                            placeholder="Masukkan username Anda"
                            value="{{ old('username') }}"
                            required
                        >
                        @error('username')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-container">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control {{ $errors->has('password') ? 'error' : '' }}" 
                                placeholder="Masukkan password Anda"
                                required
                            >
                            <i class="fa-solid fa-eye-slash pwd-toggle"></i>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- OPTIONS --}}
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ingatkan Saya</label>
                        </div>
                        <a href="#" class="forgot-pwd">Lupa Kata Sandi</a>
                    </div>

                    <button type="submit" class="btn-submit">Masuk</button>

                    <div class="auth-footer">
                        Belum Memiliki Akun? <a href="{{ route('registerAnggota.show') }}">Daftar</a>
                    </div>
                </form>
            </div>

            <!-- ILLUSTRATION SECTION (RIGHT) -->
            <div class="auth-section illustration-section">
                <img src="{{ asset('img/login_new.png') }}" alt="Login Illustration" onerror="this.src='{{ asset('img/login.png') }}'">
            </div>

        </div>
    </div>

    @if(session('success'))
    <div class="toast success show">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div class="toast error show">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>{{ session('error') ?? 'Silakan periksa kembali inputan Anda.' }}</span>
    </div>
    @endif

    <script>
        // Toggle Password Visibility
        document.querySelector('.pwd-toggle').addEventListener('click', function() {
            const input = document.querySelector('#password');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye', 'fa-eye-slash');
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
