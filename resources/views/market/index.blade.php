@extends('templates/main')
@section('css')
 <!-- Custom styles for this page -->
 <link href="{{asset('sbAdmin/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">  
@endsection
@section('content')

@foreach ($markets as $item)
    
{{-- Detail Modal --}}
<div class="modal fade" id="detailModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{$item->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Detail Toko</h5>
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body m-auto table-responsive">
           <table style="text-align:justify;"  class="table" width="100%">
            <tr>
                <td>
                    Nama Toko
                </td>
                <td>
                    :
                </td>
                <td>
                    {{$item->nama_toko}}
                </td>
            </tr>
            <tr>
                <td>
                    Nomor HP
                </td>
                <td>:</td>
                <td>
                    {{$item->no_telp}}
                </td>
            </tr>
            <tr>
                <td>
                    Alamat
                </td>
                <td>:</td>
                <td>
                    {{$item->alamat}}
                </td>
            </tr>
           </table>
          </div>
          <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
   </div>
   @endforeach


  {{-- karyawan modal --}}
  <div class="row modal-group">
    <div class="modal fade" id="tokoModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
      
          <form method="post" id="tokoForm">
            <div class="modal-header">
              <h5 class="modal-title" id="modalTitle">Tambah / Edit Toko</h5>
              <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="tambah-modal-body">
                @csrf
                  <div class="col-12">
                    <input type="hidden" name="_method" id="method" value="POST">
                    <input type="hidden" name="id" id="tokoId">
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
                  <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold">Nama Toko</label>
                  <div class="col-lg-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control" name="nama_toko" id="nama_toko" value="{{old('nama_toko')}}">
                  </div>
                </div>
                
                
                <div class="form-group row">
                  <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold">Nomor Telepon</label>
                  <div class="col-lg-9 col-md-9 col-sm-12">
                    <input type="text" class="form-control" name="no_telp" id="no_telp" value="{{old('no_telp')}}">
                  </div>
                </div>
                
                <div class="form-group row">
                  <label class="col-lg-3 col-md-3 col-sm-12 col-form-label font-weight-bold">Alamat</label>
                  <div class="col-lg-9 col-md-9 col-sm-12">
                    {{-- <input type="text" class="form-control" name="email" value=""> --}}
                    <textarea name="alamat" class="form-control" rows="3" id="alamat">{{old('alamat')}}</textarea>
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
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Data Toko</h6>
            @if ($markets->count() < 1)
            <a href="" class="btn btn-icons btn-primary btn-new ml-2 btnTambah" id="btnTambah" >
                <i class="mdi mdi-plus"></i>
            </a>
            @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="text-align: center">
                <thead>
                    <tr>
                        <th>Nama Toko</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($markets as $item)
                    <tr>
                        <td>{{$item->nama_toko}}</td>
                        <td>{{$item->no_telp}}</td>
                        <td>{{$item->alamat}}</td>
                        <td>
                            <button type="button" class="btn btn-edit btn-icons btn-rounded btn-secondary btnEdit" style="background-color: yellow"
                              data-id="{{ $item->id }}" 
                              data-nama_toko="{{ $item->nama_toko }}"
                              data-no_telp="{{ $item->no_telp }}"
                              data-alamat="{{ $item->alamat }}"
                              >
                                <i class="mdi mdi-pencil"></i>
                            </button>

                            <button type="button" class="btn btn-view btn-icons btn-rounded" style="background-color: rgb(0, 190, 79)" data-toggle="modal" data-target="#detailModal{{$item->id}}" data-detail="{{ $item->id }}">
                                <i class="mdi mdi-eye"></i>
                            </button>
        
                         
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


</script>

@if ($errors->any())
    <script>
        $( document ).ready(function() {
              // Cek mode modal
              @if (session('modal_mode') === 'edit')
                // Jika mode edit
                $('#tokoForm').attr('action', '/toko/update'); // URL untuk edit
                $('#method').val('POST'); // Method untuk edit
                $('#modalTitle').text('Edit Toko');
                $('#modalSubmit').text('Update');
                $('#tokoId').val('{{ session('modal_data.id') }}');
                $('#nama_toko').val('{{ session('modal_data.nama_toko') }}');
                $('#no_telp').val('{{ session('modal_data.no_telp') }}');
                $('#alamat').val('{{ session('modal_data.alamat') }}');
          
            @endif
            $('#tokoModal').modal('show');
        });
    </script>
@endif


<script>
  $(document).ready(function () {
  
    // Fungsi untuk membuka modal edit
    $('.btnEdit').click(function () {
        const id = $(this).data('id');
        const url = `/toko/update`;
        
        // Ambil data karyawan dari tombol edit
        const nama_toko = $(this).data('nama_toko');
      
        const no_telp = $(this).data('no_telp');
        const alamat = $(this).data('alamat');

        // Set data ke form
        $('#tokoForm').attr('action', url); // URL untuk edit
        $('#method').val('POST'); // Method untuk edit
        $('#modalTitle').text('Edit Toko');
        $('#modalSubmit').text('Update');
        $('#tokoId').val(id);
        $('#nama_toko').val(nama_toko);
      
        $('#no_telp').val(no_telp);
        $('#alamat').val(alamat);

        $('#tokoModal').modal('show');
    });

});

</script>

      <!-- Page level plugins -->
      <script src="{{asset('sbAdmin/vendor/datatables/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('sbAdmin/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  
      <!-- Page level custom scripts -->
      <script src="{{asset('sbAdmin/js/demo/datatables-demo.js')}}"></script>
@endsection