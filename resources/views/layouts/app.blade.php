<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduTech Library') | EduTech</title>

    
    {{-- FAVICON --}}
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    {{-- GOOGLE FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS GLOBAL --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        .app-container {
            overflow-x: hidden;
            width: 100%;
        }

        .table-card,
        .table-wrapper,
        .table-responsive {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0; /* Important for flex/grid parents */
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .table-card table,
            .table-wrapper table,
            .table-responsive table {
                min-width: 800px;
            }
        }
    </style>

    {{-- CSS TAMBAHAN PER HALAMAN --}}
    @stack('styles')

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app-container">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    <div class="user">
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <small class="user-role">{{ ucfirst(Auth::user()->role) }}</small>
        </div>

        <div class="user-wrapper">
            <div class="user-trigger" onclick="toggleUserPopup(event)">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="avatar">
                @else
                    <div class="avatar-default">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
            </div>

            <!-- POPUP -->
            <div class="user-popup" id="userPopup">
                <div class="popup-header">
                    @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="avatar">
                @else
                    <div class="avatar-default">
                        <i class="fa fa-user"></i>
                    </div>
                @endif
                    <div class="popup-user-info">
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ Auth::user()->username }}</small>
                    </div>
                </div>

                <a href="{{ route('profile.show') }}" class="btn-profile">
                    <i class="fa fa-user"></i> Profile Saya
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>



    {{-- CONTENT --}}
    <main class="content">
        @yield('content')
    </main>

</div>

{{-- TOAST GLOBAL --}}
@if(session('success'))
<div class="toast success" id="toast">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="toast error" id="toast">
    <i class="fa-solid fa-circle-xmark"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

@if(session('warning'))
<div class="toast warning" id="toast">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>{{ session('warning') }}</span>
</div>
@endif

{{-- TOAST DYNAMIC CONTAINER --}}
<div id="toast-container"></div>

{{-- SCRIPT GLOBAL --}}
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'fa-circle-check';
    if(type === 'error') icon = 'fa-circle-xmark';
    if(type === 'warning') icon = 'fa-triangle-exclamation';

    toast.innerHTML = `
        <i class="fa-solid ${icon}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);

    // Show toast
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    // Hide and remove toast
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 4000);
}


function toggleUserPopup(event) {
    event.stopPropagation();
    const popup = document.getElementById('userPopup');
    popup.classList.toggle('show');
}

// Tutup popup saat klik di luar
document.addEventListener('click', function (e) {
    const popup = document.getElementById('userPopup');
    const userWrapper = document.querySelector('.user-wrapper');

    if (userWrapper && !userWrapper.contains(e.target)) {
        popup.classList.remove('show');
    }
});

// Tutup popup saat klik menu
document.querySelectorAll('.btn-profile').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('userPopup').classList.remove('show');
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const toast = document.getElementById("toast");
    if (toast) {

        setTimeout(() => {
            toast.classList.add("show");
        }, 200);

        setTimeout(() => {
            toast.classList.remove("show");
        }, 4000);
    }
});

</script>

@stack('scripts')

</body>
</html>
