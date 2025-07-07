@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/manage_account/account/style.css') }}">
@endsection
@section('content')
<div class="row page-title-header">
  <div class="col-12">
    <div class="page-header d-flex justify-content-between align-items-center">
      <h4 class="page-title">Daftar Akun</h4>
      <div class="d-flex justify-content-start">
      	<div class="dropdown">
	        <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	          <i class="mdi mdi-filter-variant"></i>
	        </button>
	        <div class="dropdown-menu" aria-labelledby="dropdownMenuIconButton1">
	          <h6 class="dropdown-header">Urut Berdasarkan :</h6>
	          <div class="dropdown-divider"></div>
	          <a href="{{route('admin.account', ['filter' => 'nama'])}}" class="dropdown-item filter-btn" data-filter="nama">Nama</a>
            <a href="{{route('admin.account', ['filter' => 'email'])}}" class="dropdown-item filter-btn" data-filter="email">Email</a>
            <a href="{{route('admin.account', ['filter' => 'role'])}}" class="dropdown-item filter-btn" data-filter="role">Posisi</a>
	        </div>
	      </div>
        <div class="dropdown dropdown-search">
          <button class="btn btn-icons btn-inverse-primary btn-filter shadow-sm ml-2" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi mdi-magnify"></i>
          </button>
          <div class="dropdown-menu search-dropdown" aria-labelledby="dropdownMenuIconButton1">
            <div class="row">
              <div class="col-11">
                <input type="text" class="form-control" name="search" placeholder="Cari akun">
              </div>
            </div>
          </div>
        </div>
	      <a href="{{ url('/account/add') }}" class="btn btn-icons btn-inverse-primary btn-new ml-2">
	      	<i class="mdi mdi-plus"></i>
	      </a>
      </div>
    </div>
  </div>
</div>

<div class="row modal-group">
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="post" id="editUserForm" enctype="multipart/form-data" name="update_form">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Akun</h5>
            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              @csrf
              @if ($errors->any())
              <div class="alert alert-danger" role="alert">
                  @foreach($errors->all() as $error)
                  <ul>
                    <li>{{$error}}</li>
                  </ul>
                  @endforeach
                </div>
                @endif
              <div class="row">
                <div class="col-12 text-center">
                  <img src="" class="img-edit" id="previewFoto" >
                </div>
                <div class="col-12 text-center mt-2">
                  <input type="file" id="inputFoto" name="foto" hidden="">
                  <input type="hidden" name="nama_foto" value="{{ old('nama_foto') }}">
                  <button type="button" class="btn btn-primary font-weight-bold btn-upload">Ubah</button>
                  <button type="button" class="btn btn-delete-img">Hapus</button>
                </div>
                <input type="hidden" name="id" id="editUserId" value="{{old('id')}}">
              </div>
              <div class="form-group row mt-4">
                <label class="col-3 col-form-label font-weight-bold">Nama</label>
                <div class="col-9">
                  <input type="text" class="form-control" id="editNama" name="nama">
                </div>
                <div class="col-9 offset-3 error-notice" id="nama_error"></div>
              </div>
              <div class="form-group row">
                <label class="col-3 col-form-label font-weight-bold">Email</label>
                <div class="col-9">
                  <input type="email" class="form-control" id="editEmail" name="email">
                </div>
                <div class="col-9 offset-3 error-notice" id="email_error"></div>
              </div>
              <div class="form-group row">
                <label class="col-3 col-form-label font-weight-bold">No Karyawan</label>
                <div class="col-9">
                  <input type="text" class="form-control" id="editNoKaryawan" name="no_karyawan">
                </div>
                <div class="col-9 offset-3 error-notice" id="no_karyawan_error"></div>
              </div>
              <div class="form-group row">
                <label class="col-3 col-form-label font-weight-bold">Role</label>
                <div class="col-9">
                  <select class="form-control" id="editRole" name="role">
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                  </select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-update"><i class="mdi mdi-content-save"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="card card-noborder b-radius">
      <div class="card-body">
        <div class="row">
        	<div class="col-12 table-responsive">
        		<table class="table table-custom">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Posisi</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              	@foreach($users as $user)
                <tr>
                  <td>
                  	<img src="{{ asset('pictures/acounts/' . $user->foto) }}">
                  	<span class="ml-2">{{ $user->nama }}</span>
                  </td>
                  <td>{{ $user->email }}</td>
                  <td>
                    @if($user->role == 'admin')
                    <span class="btn admin-span">{{ $user->role }}</span>
                    @else
                    <span class="btn kasir-span">{{ $user->role }}</span>
                    @endif
                  </td>
                  @if($user->role != 'admin')
                  <td>
                  	<button type="button" class="btn btn-edit btn-icons btn-rounded btn-secondary"
                    data-id="{{ $user->id }}"
                    data-nama="{{ $user->nama }}"
                    data-email="{{ $user->email }}"
                    data-no_karyawan="{{ $user->no_karyawan }}"
                    data-role="{{ $user->role }}"
                    data-foto="{{ $user->foto}}"
                    >
                        <i class="mdi mdi-pencil"></i>
                    </button>
                    <form action="{{url('/account/delete/'. $user->id)}}" method="POST" onsubmit="return confirmDelete(event)" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete" style="background-color: rgb(255, 75, 75)" >
                          <i class="mdi mdi-close"></i>
                      </button>
                  </form>
                  </td>
                  @else
                  <td></td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
        	</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="{{ asset('js/manage_account/account/script.js') }}"></script>
<script type="text/javascript">
  @if ($message = Session::get('create_success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif

  @if ($message = Session::get('update_success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif

  @if ($message = Session::get('delete_success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif

  @if ($message = Session::get('both_error'))
    swal(
    "",
    "{{ $message }}",
    "error"
    );
  @endif

  @if ($message = Session::get('email_error'))
    swal(
    "",
    "{{ $message }}",
    "error"
    );
  @endif

  @if ($message = Session::get('username_error'))
    swal(
    "",
    "{{ $message }}",
    "error"
    );
  @endif


  function confirmDelete(event) {
        event.preventDefault(); // Menghentikan form dari pengiriman langsung

        swal({
      title: "Apa Anda Yakin?",
      text: "Data user akan terhapus, klik oke untuk melanjutkan",
      icon: "warning",
      buttons: true,
      dangerMode: true,
         }).then((willDelete) => {
            if (willDelete) {
                event.target.submit();
            } else {
                swal('batal menghapus data satuan');
            }
        });
    }

$(document).on('click', '.btn-edit', function () {
    let btn = $(this);

    // Ambil data dari tombol
    let id = btn.data('id');
    let nama = btn.data('nama');
    let email = btn.data('email');
    let no_karyawan = btn.data('no_karyawan');
    let role = btn.data('role');
    let foto = btn.data('foto');

    // Isi form modal
    $('#editUserForm').attr('action', '/account/update'); // Atur action-nya ke route yang sesuai
    $('#editUserId').val(id);
    $('#editNama').val(nama);
    $('#editEmail').val(email);
    $('#editNoKaryawan').val(no_karyawan);
    $('#editRole').val(role);

    const gambarPath = `{{asset('pictures/acounts')}}/${foto}`
    $('#previewFoto').attr('src', gambarPath);

    $('#editModal').modal('show');
});

let fotoDefault = '{{ asset('pictures/acounts/default.png') }}';

$('.btn-delete-img').click(function () {
    // Ganti preview ke default
    $('#previewFoto').attr('src', fotoDefault);

    // Hapus file input agar tidak mengirim file
    $('#inputFoto').val(null); // atau bisa juga pakai .replaceWith kalau perlu

    // Simpan info bahwa user ingin pakai default
    $('input[name="nama_foto"]').val('default.png');
    
  
});

// Trigger file input saat klik tombol "Ubah"
$(document).on('click', '.btn-upload', function () {
    $('#inputFoto').trigger('click');
});

// Initial bind event change (saat modal pertama kali muncul)
$(document).on('change', '#inputFoto', function () {
    const [file] = this.files;
    if (file) {
        $('#previewFoto').attr('src', URL.createObjectURL(file));
        $('input[name=nama_foto]').val('default.png');
    }
});


</script>

@if ($errors->any())
    <script>
        $( document ).ready(function() {   
          // Set data ke form
        $('#editUserForm').attr('action', '/account/update'); // URL untuk edit
        $('#method').val('POST'); // Method untuk edit
        $('#editUserId').val('{{session('modal_data.id')}}');
        $('#editNama').val('{{session('modal_data.nama')}}');
        $('#editEmail').val('{{session('modal_data.email')}}');
        $('#editNoKaryawan').val('{{session('modal_data.no_karyawan')}}');
        $('#editRole').val('{{session('modal_data.role')}}');

        const btnEdit = $(`.btn-edit[data-id="{{ session('modal_data.id') }}"]`);// Pilih tombol yang diinginkan
        const gambar = btnEdit.data('foto');
            // Atur label dan gambar
        const gambarPath = `{{ asset('pictures/acounts') }}/${gambar}`; // Path gambar dengan Laravel asset helper
        $('#inputFoto').val(''); // Kosongkan input file
        $('#fileLabel').text(gambar); // Set nama file sebagai label
        $('#previewFoto').attr('src', gambarPath); // Set src untuk preview gambar
            $('#editModal').modal('show');
        });
    </script>
@endif
@endsection