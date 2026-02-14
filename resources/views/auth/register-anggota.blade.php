<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Anggota</title>

  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="wrapper">
  <div class="card">

    <!-- LEFT -->
    <div class="left">
      <h2>Register Anggota</h2>
      <img src="{{ asset('img/login.png') }}" alt="Register">
    </div>

   <!-- RIGHT -->
   <div class="right">
   <form action="{{ route('registerAnggota') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- FOTO -->
    <div class="photo-upload">
      <input type="file" id="photo" name="photo" hidden>
      <label for="photo" class="photo-circle">
        <i class="fa-solid fa-camera"></i>
      </label>
    </div>

    <label>Nama Lengkap</label>
    <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}">

    <label>Username</label>
    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">

    <label>No. Telp</label>
    <input type="text" name="telephone" placeholder="08xxxxxxxxxx" value="{{ old('telephone') }}">

    <label>Password</label>
    <div class="password">
      <input type="password" name="password" placeholder="Password" class="pwd-input">
      <i class="fa-solid fa-eye-slash pwd-toggle"></i>
    </div>

    <label>Alamat</label>
    <input type="text" name="alamat" placeholder="Alamat">

    <!-- NIS & Kelas -->
    <div class="row-input">
      <div>
        <label>NIS</label>
        <input type="text" id="nis_field" placeholder="NIS">
      </div>
      <div>
        <label>Kelas</label>
        <input type="text" name="kelas" placeholder="Kelas">
      </div>
    </div>

    <div class="remember">
      <label>
        <input type="checkbox" name="remember">
        Ingat Saya
      </label>
    </div>

    <button type="submit">Masuk</button>

    <p class="register">
      Belum Memiliki Akun ?
      <a href="#">Daftar</a>
    </p>

  </form>
</div>


  </div>
</div>

<script>
    // Fungsi untuk toggle password visibility
    document.querySelectorAll('.pwd-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            }
        });
    });

    // Fungsi untuk menggabungkan NIS dan NISN
    function updateNisNisn() {
        const nisField = document.getElementById('nis_field').value;
        const nisnField = document.getElementById('nisn_field').value;
        const combined = (nisField || nisnField) ? nisField + '-' + nisnField : '';
        document.getElementById('nis_nisn_combined').value = combined;
    }

    // Update saat input berubah
    document.getElementById('nis_field').addEventListener('input', updateNisNisn);
    document.getElementById('nisn_field').addEventListener('input', updateNisNisn);

    // Update saat form di-submit
    document.querySelector('form').addEventListener('submit', updateNisNisn);
</script>

</body>
</html>