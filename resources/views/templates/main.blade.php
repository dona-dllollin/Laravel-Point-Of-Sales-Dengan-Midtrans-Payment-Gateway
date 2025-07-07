<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Website POS</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/ionicons/css/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/typicons/src/font/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('icons/LOGO_dlollin.png') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
  
    @yield('css')
    <!-- End-CSS -->

  </head>
  <body>
    <div class="container-scroller">
      <!-- TopNav -->
      <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
          <a class="navbar-brand brand-logo" style="padding-top: 10%" href="{{ Auth::user()->role === 'admin' ? url('/dashboard') : route('kasir.dashboard', ['slug_market' => session('slug_market')])}}">
            {{-- <img src="{{ asset('icons/logo.png') }}" alt="logo" /> </a> --}}
            <h1 style="font-family: Arial, Helvetica, sans-serif; font-weight:bold; font-size:100%">Point Of Sales</h1>
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ Auth::user()->role === 'admin' ? url('/dashboard') : route('kasir.dashboard', ['slug_market' => session('slug_market')])}}">
            <img src="{{ asset('icons/logo-mini.png') }}" alt="logo" /> </a>
          </div>

          <div class="navbar-menu-wrapper d-flex align-items-center">
         
            <button class="navbar-toggler toggle-sidebar align-self-center" type="button">
              <span class="mdi mdi-menu"></span>
            </button>  
          {{-- <form class="search-form d-none d-md-block" action="#">
            <div class="form-group">
              <input type="search" class="form-control" name="search_page" placeholder="Cari Halaman">
            </div>
          </form> --}}
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              @php
              // $cek_supply_system = \App\Supply_system::first();
              if (Auth::user()->role === 'admin') {
                
                $jumlah_notif = \App\Models\Product::where('stok', '<', 10)
                ->count();
                $notifications = \App\Models\Product::where('stok', '<', 10)
                ->get();
                $notification = \App\Models\Product::where('stok', '<', 10)
                ->take(3)
                ->get();
              } else if (Auth::user()->role === 'kasir'){
                $market_id = Auth::user()->market_id; // Ambil market_id dari user
               $jumlah_notif = \App\Models\Product::where('stok', '<', 10)
                    ->where('market_id', $market_id)
                    ->count();
                $notifications = \App\Models\Product::where('stok', '<', 10)
                    ->where('market_id', $market_id)
                    ->get();
                $notification = \App\Models\Product::where('stok', '<', 10)
                    ->where('market_id', $market_id)
                    ->take(3)
                    ->get();
              }
              @endphp
              <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                {{-- @if($cek_supply_system->status == 1) --}}
                  @if($jumlah_notif != 0)
                  <span class="count bg-success">{{ $jumlah_notif }}</span>
                  @endif
                {{-- @endif --}}
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                <div class="dropdown-item py-3 border-bottom">
                  {{-- @if($cek_supply_system->status == 1) --}}
                  <p class="mb-0 font-weight-medium float-left">Anda Memiliki {{ $jumlah_notif }} Pemberitahuan</p>
                  {{-- @else
                  <p class="mb-0 font-weight-medium float-left">Anda Memiliki 0 Pemberitahuan</p>
                  @endif --}}
                  <a href="#" role="button" data-toggle="modal" data-target="#notificationModal"><span class="badge badge-pill badge-primary float-right">Semua</span></a>
                </div>
                {{-- @if($cek_supply_system->status == 1) --}}
                  @foreach($notification as $notif)
                  @if($notif->stok != 0)
                  <a class="dropdown-item preview-item py-3">
                    <div class="preview-thumbnail">
                      <i class="mdi mdi-alert m-auto text-warning"></i>
                    </div>
                    <div class="preview-item-content">
                      <h6 class="preview-subject font-weight-normal text-dark mb-1">Barang Hampir Habis</h6>
                      <p class="font-weight-light small-text mb-0"> Stok {{ $notif->nama_barang }} tersisa {{ $notif->stok }} </p>
                    </div>
                  </a>
                  @else
                  <a class="dropdown-item preview-item py-3">
                    <div class="preview-thumbnail">
                      <i class="mdi mdi-alert m-auto text-danger"></i>
                    </div>
                    <div class="preview-item-content">
                      <h6 class="preview-subject font-weight-normal text-dark mb-1">Barang Telah Habis</h6>
                      <p class="font-weight-light small-text mb-0"> Stok barang {{ $notif->nama_barang }} telah habis</p>
                    </div>
                  </a>
                  @endif
                  @endforeach
                {{-- @endif --}}
              </div>
            </li>
            <li class="nav-item dropdown d-none d-xl-inline-block user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="{{ asset('pictures/acounts/' . auth()->user()->foto) }}" alt="Profile image"> </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="{{ asset('pictures/acounts/' . auth()->user()->foto) }}" alt="Profile image">
                  <p class="mb-1 mt-3 font-weight-semibold">{{ auth()->user()->nama }}</p>
                  <p class="font-weight-light text-muted mb-0">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ url('/profile') }}" class="dropdown-item">Profil</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Log Out</a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>  
        </div>
      </nav>
      <!-- End-TopNav -->

      <div class="container-fluid page-body-wrapper">
          <!-- Logout Modal-->
          <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                      </button>
                  </div>
                  <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                  <div class="modal-footer">
                      <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                      <form action="{{ url('/logout') }}" method="post">
                      @csrf
                        <button class="btn btn-primary" type="submit">Logout</button>

                      </form>
                  </div>
              </div>
            </div>
           </div>
        <!-- SideNav -->
        <nav class="sidebar sidebar-visible sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <a href="{{url('/profile')}}" class="nav-link">
                <div class="profile-image">
                  <img class="img-xs rounded-circle" src="{{ asset('pictures/acounts/' . auth()->user()->foto) }}" alt="profile image">
                  <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                  @php
                  $user_name = auth()->user()->nama;
                  if(strlen($user_name) > 12){
                    $nama = substr($user_name, 0, 12) . '..';
                  }else{
                    $nama = $user_name;
                  }
                  @endphp
                  <p class="profile-name">{{ $nama }}</p>
                  <p class="designation">{{ auth()->user()->role }}</p>
                </div>
              </a>
            </li>
            <li class="nav-item nav-category">Daftar Menu</li>
            <li class="nav-item">
              <a class="nav-link" href="{{ Auth::user()->role === 'admin' ? url('/dashboard') : route('kasir.dashboard', ['slug_market' => session('slug_market')])}} ">
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
            {{-- @php
            $access = \App\Acces::where('user', auth()->user()->id)
            ->first();
            @endphp --}}
            @if(auth()->user()->role === 'admin')
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#kelola_akun" aria-expanded="false" aria-controls="kelola_akun">
                <span class="menu-title">Kelola Karyawan</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="kelola_akun">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('/account')}}">Daftar Akun</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.karyawan')}}">Daftar Karyawan</a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#kelola_ks" aria-expanded="false" aria-controls="kelola_ks">
                <span class="menu-title">Kategori & satuan</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="kelola_ks">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.kategori')}}">Kategori</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.satuan')}}">Satuan</a>
                  </li>
                </ul>
              </div>
            </li>
            @endif
           
            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#kelola_barang" aria-expanded="false" aria-controls="kelola_barang">
                <span class="menu-title">Kelola Barang</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="kelola_barang">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{Auth::user()->role === 'admin' ? route('admin.product') : route('kasir.product', ['slug_market' => session('slug_market')]) }}">Daftar Barang</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('/supply')}}">Pasok Barang</a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#kelola_utang" aria-expanded="false" aria-controls="kelola_utang">
                <span class="menu-title">Kelola Utang</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="kelola_utang">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('/debt')}}">Daftar Utang</a>
                  </li>
                  @if(auth()->user()->role === 'admin')
                  <li class="nav-item">
                    <a class="nav-link" href="{{url('/debt/payment/history')}}">Histori Angsuran</a>
                  </li>
                  @endif
                </ul>
              </div>
            </li>
          
            <li class="nav-item">
              <a class="nav-link" href="{{Auth::user()->role === 'admin' ? route('admin.transaction') : route('kasir.transaction', ['slug_market' => session('slug_market')] )}}">
                <span class="menu-title">Transaksi</span>
              </a>
            </li>

            @if(auth()->user()->role === 'admin')
            <li class="nav-item">
              <a class="nav-link" href="{{route('admin.toko')}}">
                <span class="menu-title">Kelola Toko</span>
              </a>
            </li>
            @endif
         
           
            {{-- <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#kelola_laporan" aria-expanded="false" aria-controls="kelola_laporan">
                <span class="menu-title">Kelola Laporan</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="kelola_laporan">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item">
                    <a class="nav-link" href="{{route("report.transaction")}}">Laporan Transaksi</a>
                  </li>
                </ul>
              </div>
            </li> --}}
            @if(auth()->user()->role === 'admin')
            <li class="nav-item">
              <a class="nav-link"  href="{{route("report.transaction")}}">
                <span class="menu-title"> Laporan Transaksi</span>
              </a>
            </li>
            @endif
          </ul>
          
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">
    </nav>
        <!-- End-SideNav -->

        <div class="main-panel">
          <div class="row">
            <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Daftar Notifikasi</h5>
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-12">
                        
                          @foreach($notifications as $notif)
                          @if($notif->stok != 0)
                          <div class="d-flex justify-content-start align-items-center mb-3">
                            <div class="icon-notification">
                              <i class="mdi mdi-alert m-auto text-warning"></i>
                            </div>
                            <div class="text-group ml-3">
                              <p class="m-0 title-notification">Barang Hampir Habis</p>
                              <p class="m-0 description-notification">Stok {{ $notif->nama_barang }} tersisa {{ $notif->stok }}</p>
                            </div>
                          </div>
                          @else
                          <div class="d-flex justify-content-start align-items-center mb-3">
                            <div class="icon-notification">
                              <i class="mdi mdi-alert m-auto text-danger"></i>
                            </div>
                            <div class="text-group ml-3">
                              <p class="m-0 title-notification">Barang Telah Habis</p>
                              <p class="m-0 description-notification">Stok barang {{ $notif->nama_barang }} telah habis</p>
                            </div>
                          </div>
                          @endif
                          @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="content-wrapper" id="content-web-page">
            @yield('content')
          </div>
          <div class="content-wrapper" id="content-web-search" hidden="">
            <div class="row">
              <div class="col-12 text-left">
                <h3 class="d-block">Cari Halaman</h3>
                <h5 class="mt-3 d-block"><span class="result-1"></span> <span class="result-2"></span></h5>
              </div>
              <div class="col-12 mt-3">
                <div class="row" id="page-result-parent">
                </div>
              </div>
            </div>
          </div>
          <footer class="footer" id="footer-content">
            <div class="container-fluid clearfix">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2019 <a href="http://www.bootstrapdash.com/" target="_blank">Bootstrapdash</a>. All rights reserved.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i>
              </span>
            </div>
          </footer>
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
    <script src="{{ asset('plugins/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/templates/script.js') }}"></script>
    {{-- <script type="text/javascript">
      $(document).on('input', 'input[name=search_page]', function(){
        if($(this).val() != ''){
          $('#content-web-page').prop('hidden', true);
          $('#content-web-search').prop('hidden', false);
          var search_word = $(this).val();
          $.ajax({
            url: "{{ url('/search') }}/" + search_word,
            method: "GET",
            success:function(response){
              $('.result-1').html(response.length + ' Hasil Pencarian');
              $('.result-2').html('dari "' + search_word + '"');
              var lengthLoop = response.length - 1;
              var searchResultList = '';
              for(var i = 0; i <= lengthLoop; i++){
                var page_url = "{{ url('/', ':id') }}";
                page_url = page_url.replace('%3Aid', response[i].page_url);
                searchResultList += '<a href="'+ page_url +'" class="page-result-child mb-4 w-100"><div class="col-12"><div class="card card-noborder b-radius"><div class="card-body"><div class="row"><div class="col-12"><h5 class="d-block page_url">'+ response[i].page_name +'</h5><p class="align-items-center d-flex justify-content-start"><span class="icon-in-search mr-2"><i class="mdi mdi-chevron-double-right"></i></span> <span class="breadcrumbs-search page_url">'+ response[i].page_title +'</span></p><div class="search-description"><p class="m-0 p-0 page_url">'+ response[i].page_content.substring(0, 500) +'...</p></div></div></div></div></div></div></a>';
              }
              $('#page-result-parent').html(searchResultList);
              $('.page_url:contains("'+search_word+'")').each(function(){
                  var regex = new RegExp(search_word, 'gi');
                  $(this).html($(this).text().replace(regex, '<span style="background-color: #607df3;">'+search_word+'</span>'));
              });
            }
          });
        }else{
          $('#content-web-page').prop('hidden', false);
          $('#content-web-search').prop('hidden', true);
        }
      });
    </script> --}}
    
    @yield('script')
    <!-- End-Javascript -->

    <script>
      // Script untuk toggle sidebar
      document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.querySelector('.toggle-sidebar');

        toggleButton.addEventListener('click', () => {
          if (sidebar.classList.contains('sidebar-visible')) {
            sidebar.classList.remove('sidebar-visible');
            sidebar.classList.add('sidebar-hidden');
          } else {
            sidebar.classList.remove('sidebar-hidden');
            sidebar.classList.add('sidebar-visible');
          }
        });
      });

    </script>

    <style>
      /* Tambahan class untuk menyembunyikan dan menampilkan sidebar */
      .sidebar-hidden {
        width: 0;
        overflow: hidden;
        transition: width 0.25s ease;
      }

      .sidebar-visible {
        width: 270px; /* Sesuaikan dengan lebar default sidebar */
        transition: width 0.25s ease;
      }

      /* Menyembunyikan teks pada sidebar saat disembunyikan */
      .sidebar-hidden .menu-title {
        display: none;
      }

    </style>
  </body>
</html>