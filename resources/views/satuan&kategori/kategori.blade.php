@extends('templates/main')
@section('css')
 <!-- Custom styles for this page -->
 <link href="{{asset('sbAdmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">  
@endsection
@section('content')
    


  {{-- kategori modal --}}
  <div class="row modal-group">
    <div class="modal fade" id="karyawanModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
      
          <form method="post" id="karyawanForm" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTitle">Tambah / Edit Kategori</h5>
              <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="tambah-modal-body">
                @csrf
                  <div class="col-12">
                    <input type="hidden" name="_method" id="method" value="POST">
                    <input type="hidden" name="id" id="karyawanId">
                </div>
                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    @foreach($errors->all() as $error)
                    <ul>
                      <li>{{$error}}</li>
                    </ul>
                    @endforeach
                  </div>
                  @endif

                <div class="form-group row">
                  <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold">Nama</label>
                  <div class="col-lg-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control" name="name" id="name" value="{{old('nama')}}">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold">Gambar</label>
                  <div class="col-lg-9 col-md-9 col-sm-12">
                    <input name="gambar" id="gambar" type="file" class="custom-file-input"
                    accept="image/*"
                    onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0]); document.getElementById('fileLabel').textContent = this.files[0].name;">
                    <label class="custom-file-label" id="fileLabel">Choose File</label>
        
                    <div class="col-sm-12 text-center mt-3" ><img id="output" src="{{asset('pictures/kategori.png')}}" class="img-fluid" style="width: 50%"></div>
                  </div>
                </div>

            </div>
            <div class="modal-footer" id="edit-modal-footer">
              <button type="submit" id="modalSubmit" class="btn btn-primary"><i class="mdi mdi-content-save"></i>Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- DataTales Example -->
<div class="card shadow mb-4 mx-auto w-50">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Kategori</h6>
        
            <a href="" class="btn btn-icons btn-primary btn-new ml-2 btnTambah" id="btnTambah" >
                <i class="mdi mdi-plus"></i>
            </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="text-align: center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1
                    @endphp
                    @foreach ($categories as $item)
                    <tr>
                        <td>{{$no++}}</td>
                        <td><img src="{{asset('pictures/'. $item->gambar)}}" alt=""></td>
                        <td>{{$item->name}}</td>
                        <td>
                            <button type="button" class="btn btn-edit btn-icons btn-rounded btn-secondary btnEdit" style="background-color: yellow"
                              data-id="{{ $item->id }}" 
                              data-name="{{ $item->name }}"
                              data-gambar="{{ $item->gambar }}"
                              >
                                <i class="mdi mdi-pencil"></i>
                            </button>
                         <form action="{{url('/kategori/delete/'. $item->id)}}" method="POST" onsubmit="return confirmDelete(event)" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete" style="background-color: rgb(255, 75, 75)" >
                                <i class="mdi mdi-close"></i>
                            </button>
                        </form>
                          </td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
    
@endsection

@section('script')

<script>
   @if ($message = Session::get('success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif

//   @if ($errors->any())
//   @foreach($errors->all() as $error)
  
//       swal(
//           "",
//           "{{ $error }}",
//           "error"
//       );
  
//   @endforeach
// @endif

function confirmDelete(event) {
        event.preventDefault(); // Menghentikan form dari pengiriman langsung

        swal({
      title: "Apa Anda Yakin?",
      text: "Data Kategori akan terhapus, klik oke untuk melanjutkan",
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

</script>

@if ($errors->any())
    <script>
        $( document ).ready(function() {

          @if (session('modal_mode')  === 'edit')
          // Set data ke form
        $('#karyawanForm').attr('action', '/kategori/edit'); // URL untuk edit
        $('#method').val('POST'); // Method untuk edit
        $('#modalTitle').text('Edit Kategori');
        $('#modalSubmit').text('Update');
        $('#karyawanId').val('{{session('modal_data.id')}}');
        $('#name').val('{{session('modal_data.nama')}}');

        const btnEdit = $(`.btnEdit[data-id="{{ session('modal_data.id') }}"]`);// Pilih tombol yang diinginkan
        const gambar = btnEdit.data('gambar');
            // Atur label dan gambar
        const gambarPath = `{{ asset('pictures') }}/${gambar}`; // Path gambar dengan Laravel asset helper
        $('#gambar').val(''); // Kosongkan input file
        $('#fileLabel').text(gambar); // Set nama file sebagai label
        $('#output').attr('src', gambarPath); // Set src untuk preview gambar
          @else
        $('#karyawanForm').attr('action', '/kategori'); // URL untuk tambah
        $('#method').val('POST'); // Method untuk tambah
        $('#modalTitle').text('Tambah Kategori');
        $('#modalSubmit').text('Tambah');
        $('#karyawanForm')[0].reset(); // Reset semua field
          @endif
            $('#karyawanModal').modal('show');
        });
    </script>
@endif


<script>
  $(document).ready(function () {
    // Fungsi untuk membuka modal tambah
    $('.btnTambah').click(function (e) {
        e.preventDefault()
        $('#karyawanForm').attr('action', '/kategori'); // URL untuk tambah
        $('#method').val('POST'); // Method untuk tambah
        $('#modalTitle').text('Tambah Kategori');
        $('#modalSubmit').text('Tambah');
        $('#karyawanForm')[0].reset(); // Reset semua field
        $('#karyawanModal').modal('show');
    });

    // Fungsi untuk membuka modal edit
    $('.btnEdit').click(function () {
        const id = $(this).data('id');
        const url = `/kategori/edit`;
        
        // Ambil data karyawan dari tombol edit
        const name = $(this).data('name');
        const gambar = $(this).data('gambar');

        // Set data ke form
        $('#karyawanForm').attr('action', url); // URL untuk edit
        $('#method').val('POST'); // Method untuk edit
        $('#modalTitle').text('Edit Kategori');
        $('#modalSubmit').text('Update');
        $('#karyawanId').val(id);
        $('#name').val(name);

            // Atur label dan gambar
        const gambarPath = `{{ asset('pictures') }}/${gambar}`; // Path gambar dengan Laravel asset helper
        $('#gambar').val(''); // Kosongkan input file
        $('#fileLabel').text(gambar); // Set nama file sebagai label
        $('#output').attr('src', gambarPath); // Set src untuk preview gambar

        $('#karyawanModal').modal('show');
    });

});

</script>





        {{-- <!-- Bootstrap core JavaScript-->
        <script src="{{asset('sbAdmin/vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('sbAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{asset('sbAdmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{asset('sbAdmin/js/sb-admin-2.min.js')}}"></script> --}}

      <!-- Page level plugins -->
      <script src="{{asset('sbAdmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('sbAdmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  
      <!-- Page level custom scripts -->
      <script src="{{asset('sbAdmin/js/demo/datatables-demo.js')}}"></script>
@endsection