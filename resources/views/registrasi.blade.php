<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/css/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('icons/LOGO_dlollin.png') }}"/>

  <style>
    .avatar-wrapper {
      position: relative;
      width: 120px;
      height: 120px;
      margin: auto;
    }
    .avatar-wrapper img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      border: 3px solid #ddd;
      object-fit: cover;
    }
    .avatar-wrapper label {
      position: absolute;
      bottom: 0;
      right: 0;
      cursor: pointer;
    }
    .avatar-wrapper i {
      font-size: 24px;
      color: #007bff;
      background-color: #fff;
      border-radius: 50%;
      padding: 4px;
      border: 1px solid #ccc;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.1rem rgba(0,123,255,.25);
    }
    .check-value {
      color: red;
      font-size: 0.8rem;
    }
    .alert ul {
      margin-bottom: 0;
    }
    .svg-illustration {
      max-width: 100%;
      height: auto;
    }
    .left-column {
      background-color: #f8f9fa;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .login-page .row {
      min-height: 100vh;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row my-3">
      <div class="col-md-7 left-column">
        <img src="{{ asset('images/illustrations/undraw_uploading_go67.svg') }}" alt="Ilustrasi" class="svg-illustration mb-4">
      </div>
      <div class="col-md-5 d-flex align-items-center">
        <div class="w-100 px-4">
          <div class="auto-form-wrapper p-4 shadow rounded bg-white">
            <div class="text-center mb-4">
              <h3 class="mt-3">Selamat Datang!</h3>
              <p class="text-muted text-center">Silakan daftar untuk membuat akun Anda.</p>
            </div>

            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $item)
                    <li>{{ $item }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            @if (Session::get('success'))
              <div class="alert alert-success alert-dismissible fade show">
                <ul>
                  <li>{{ Session::get('success') }}</li>
                </ul>
              </div>
            @endif

            <form action="{{ url('/register') }}" id="registerForm" method="post" enctype="multipart/form-data">
              @csrf
              <div class="mb-4 text-center">
                <div class="avatar-wrapper">
                  <img src="{{ asset('pictures/default.jpg') }}" alt="Preview" id="gambarPreview">
                  <label for="foto">
                    <i class="mdi mdi-camera"></i>
                    <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="previewImage(this)">
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama">
              </div>

              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email">
              </div>

              <div class="form-group">
                <label>Nomor Karyawan</label>
                <input type="text" class="form-control" name="no_karyawan" placeholder="nomor karyawan" >
              </div>

              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="*********">
              </div>

              <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="*********">
              </div>

              {{-- <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="setuju" required>
                  <label class="form-check-label" for="setuju">
                    Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>
                  </label>
                </div>
              </div> --}}

              <div class="form-group mt-4">
                <button class="btn btn-primary btn-block">Buat Akun</button>
              </div>

              {{-- <div class="text-center mt-3">
                <p class="small text-muted">Atau daftar dengan</p>
                <button type="button" class="btn btn-outline-danger btn-sm">Google</button>
                <button type="button" class="btn btn-outline-primary btn-sm">Facebook</button>
              </div> --}}

              <div class="text-center mt-3">
                <p class="small">Sudah punya akun? <a href="{{ url('/') }}">Masuk</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
  <script src="{{ asset('plugins/js/sweetalert.min.js') }}"></script>

  <script>
    @if ($message = Session::get('create_success'))
      swal("Berhasil!", "{{ $message }}", "success");
    @endif
    @if ($message = Session::get('error'))
      swal("Gagal!", "{{ $message }}", "error");
    @endif

    document.getElementById('registerForm').addEventListener('submit', function(event) {
    const passwordInput = document.querySelector('input[name="password"]').value;
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]').value;

    if (passwordInput !== confirmPasswordInput) {
      event.preventDefault(); 
      swal("Gagal!", "Password dan Konfirmasi Password tidak cocok.", "error");
    }
  });

    function previewImage(input) {
      const preview = document.getElementById('gambarPreview');
      const file = input.files[0];
      const reader = new FileReader();

      reader.onloadend = function () {
        preview.src = reader.result;
      }

      if (file) {
        reader.readAsDataURL(file);
      } else {
        preview.src = "{{ asset('pictures/default.jpg') }}";
      }
    }
  </script>
</body>
</html>