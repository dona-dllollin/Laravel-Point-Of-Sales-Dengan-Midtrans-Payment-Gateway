<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ionicons/css/ionicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/typicons/src/font/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/css/sweetalert.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('icons/LOGO_dlollin.png') }}"/>
  <style>
    .login-container {
      background: #fff !important;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    .login-title {
      font-weight: 700;
      font-size: 24px;
      text-align: center;
      margin-bottom: 10px;
    }
    .login-subtitle {
      text-align: center;
      color: #777;
      margin-bottom: 20px;
    }
    .logo {
      display: block;
      margin: 0 auto 20px;
      max-width: 80px;
    }
    @media (min-width: 992px) {
      .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .illustration {
        max-width: 100%;
        height: auto;
      }
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth login-page theme-one">
        <div class="row w-100 login-wrapper mx-auto">
          <div class="col-lg-6 d-none d-lg-block text-center">
            <img src="{{ asset('images/illustrations/undraw_visual_data_b1wx.svg') }}" width="90%" alt="Login Illustration" class="illustration">
          </div>
          <div class="col-lg-4 mx-auto login-container auto-form-wrapper">
            <img src="{{ asset('icons//LOGO_dlollin.png') }}" width="150%" alt="Logo" class="logo">
            <h3 class="login-title">Selamat Datang Kembali</h3>
            <p class="login-subtitle">Silakan masuk untuk melanjutkan</p>
              <form action="{{ url('/login') }}" method="post" name="login_form">
                @csrf
                <div class="form-group">
                  <label class="label">Email</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="email" placeholder="Email">
                    <div class="input-group-append">
                      <span class="input-group-text check-value" id="username_error"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="password" placeholder="*********">
                    <div class="input-group-append">
                      <span class="input-group-text check-value" id="password_error"></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Masuk</button>
                </div>
                <div class="text-center p-t-46 p-b-20">
                  <span class="txt2">
                    Belum punya akun? <a href="{{ route('registrasi') }}" class="text-primary">Register</a>
                  </span>
                </div>
              </form>
            <p class="mt-3 footer-text text-center">&copy; {{ now()->year }} Bootstrapdash. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Javascript -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>
  <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
  <script src="{{ asset('plugins/js/jquery.form-validator.min.js') }}"></script>
  <script src="{{ asset('plugins/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('js/login/script.js') }}"></script>
  <script type="text/javascript">
    @if ($message = Session::get('create_success'))
      swal("Berhasil!", "{{ $message }}", "success");
    @endif

    @if ($message = Session::get('login_failed'))
      swal("Gagal!", "{{ $message }}", "error");
    @endif
  </script>
</body>
</html>
