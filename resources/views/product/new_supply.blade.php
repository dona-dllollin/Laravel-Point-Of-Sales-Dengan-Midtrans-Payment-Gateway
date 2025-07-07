@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/manage_product/supply_product/new_supply/style.css') }}">
@endsection
@section('content')
<div class="row page-title-header">
  <div class="col-12">
    <div class="page-header d-flex justify-content-start align-items-center">
      <div class="quick-link-wrapper d-md-flex flex-md-wrap">
        <ul class="quick-links">
          <li><a href="{{ url('supply') }}">Riwayat Pasok</a></li>
          <li><a href="{{ url('supply/new') }}">Pasok Barang</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row modal-group">
  <div class="modal fade" id="scanModal" tabindex="-1" role="dialog" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="scanModalLabel">Scan Barcode</h5>
	        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	          <div class="row">
	          	<div class="col-12 text-center" id="area-scan">
	          	</div>
	          	<div class="col-12 barcode-result" hidden="">
	          		<h5 class="font-weight-bold">Hasil</h5>
	          		<div class="form-border">
	          			<p class="barcode-result-text"></p>
	          		</div>
	          	</div>
	          </div>
	      </div>
	      <div class="modal-footer" id="btn-scan-action" hidden="">
	        <button type="button" class="btn btn-primary btn-sm font-weight-bold rounded-0 btn-continue">Lanjutkan</button>
	        <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold rounded-0 btn-repeat">Ulangi</button>
	      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="tableModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="tableModalLabel">Daftar Barang</h5>
	        <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-12">
	      			<div class="form-group">
	      				<input type="text" class="form-control" name="search" placeholder="Cari barang">
	      			</div>	
	      		</div>
	      		<div class="col-12">
	      			<ul class="list-group product-list">
	      			  @foreach($products as $product)
					  <li class="list-group-item d-flex justify-content-between align-items-center active-list">
					    <div class="text-group" style="width: 60%;">
					    	<p class="m-0">{{ $product->kode_barang }}</p>
					    	<p class="m-0 txt-light text-truncate">{{ $product->nama_barang }}</p>
					    </div>
					    <div class="d-flex align-items-center">
					    	<span class="ammount-box bg-secondary mr-1"><i class="mdi mdi-cube-outline"></i></span>
					    	<p class="m-0">{{ $product->stok }}</p>
					    </div>
					    <a href="#" class="btn btn-icons btn-rounded btn-inverse-outline-primary font-weight-bold btn-pilih" role="button"><i class="mdi mdi-chevron-right"></i></a>
					  </li>
					  @endforeach
					</ul>
	      		</div>
	      	</div>
	      </div>
	  </div>
	</div>
  </div>
</div>
<div class="row">
	<div class="col-lg-4 col-md-12 col-sm-12 mb-4">
		<div class="row">
			<div class="col-12">
				<div class="card card-noborder b-radius">
					<div class="card-body">
						<div class="row">
							<div class="col-12 d-flex">
								<h4>Masukkan Barang</h4>
							</div>
							<div class="col-12 mt-3">
								<form method="post" name="manual_form">
									<div class="form-group row">
										<label class="col-12 font-weight-bold col-form-label">Kode Barang</label>
										<div class="col-8">
											<input type="text" class="form-control" name="kode_barang" readonly="">
										</div>
										<div class="col-4 left-min d-flex">
											<div class="btn-group">
												<button class="btn btn-search" data-toggle="modal" data-target="#tableModal" type="button">
													<i class="mdi mdi-magnify"></i>
												</button>
												<button class="btn btn-scan" data-toggle="modal" data-target="#scanModal" type="button">
													<i class="mdi mdi-crop-free"></i>
												</button>
											</div>
										</div>
										<div class="col-12 error-notice" id="kode_barang_error"></div>
									</div>
								<div id="detail-barang" hidden="">
									<div class="form-group row top-min">
										<label class="col-12 font-weight-bold col-form-label">Nama Barang</label>
										<div class="col-12">
											<input type="text" class="form-control number-input input-notzero" name="nama_barang" readonly="">
										</div>
										<div class="col-12 error-notice" id="nama_error"></div>
									</div>
									<div class="form-group row top-min">
										<label class="col-12 font-weight-bold col-form-label">Harga Satuan Lama</label>
										<div class="col-12">
											<input type="text" class="form-control number-input input-notzero" name="harga_satuan" readonly="">
										</div>
										<div class="col-12 error-notice" id="harga_satuan_error"></div>
									</div>
									<div class="form-group row top-min">
										<label class="col-12 font-weight-bold col-form-label">Harga Jual Barang</label>
										<div class="col-12">
											<input type="text" class="form-control number-input input-notzero" name="harga_jual" readonly="">
										</div>
										<div class="col-12 error-notice" id="harga_beli_error"></div>
									</div>
								</div>
									<div class="form-group row top-min">
										<label class="col-12 font-weight-bold col-form-label">Jumlah Barang</label>
										<div class="col-12">
											<input type="text" class="form-control number-input input-notzero" name="jumlah" placeholder="Masukkan Jumlah">
										</div>
										<div class="col-12 error-notice" id="jumlah_error"></div>
									</div>
									<div class="form-group row top-min">
										<label class="col-12 font-weight-bold col-form-label">Harga Satuan Baru (Opsional)</label>
										<div class="col-12">
											<div class="input-group">
												<div class="input-group-prepend">
													<div class="input-group-text">Rp.</div>
												</div>
												<input type="text" class="form-control number-input input-notzero" name="harga_beli" placeholder="Boleh Kosong">
											</div>
										</div>
										<div class="col-12 error-notice" id="harga_beli_error"></div>
									</div>
									<div class="row">
										<div class="col-12 d-flex justify-content-end">
											<button class="btn font-weight-bold btn-tambah" type="button">Tambah</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-12 col-sm-12">
		<div class="card card-noborder b-radius">
			<div class="card-body">
				{{-- <form action="" method="post">
					@csrf --}}
					<div class="row">
						<div class="col-12 table-responsive mb-4">
							<table class="table table-custom">
								<thead>
									<tr>
										<th>Barang</th>
										<th>Jumlah</th>
										<th>Harga Satuan</th>
										<th>Total</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<div class="col-12 d-flex justify-content-end">
							<button class="btn btn-simpan btn-sm" type="submit" hidden=""><i class="mdi mdi-content-save"></i> Simpan</button>
						</div>
					</div>
				{{-- </form> --}}
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script src="{{ asset('plugins/js/quagga.min.js') }}"></script>
<script src="{{ asset('js/manage_product/supply_product/new_supply/script.js') }}"></script>
<script type="text/javascript">


	$(document).on('click', '.btn-continue', function(){
	  var kode_barang = $('.barcode-result-text').text();
	  $.ajax({
	  	url: "{{ url('/supply/check') }}/" + kode_barang,
	  	method: "GET",
	  	success:function(data){
	  		if(data.message === 'Data found'){
				$('input[name=kode_barang]').val(kode_barang);
				$('#btn-scan-action').prop('hidden', true);
				$('#detail-barang').prop('hidden', false);
				$("input[name=nama_barang]").val(data.product.nama_barang);
            	$("input[name=harga_satuan]").val(data.product.harga_beli);
           		$("input[name=harga_jual]").val(data.product.harga_jual);
				$('#area-scan').prop('hidden', true);
				$('.barcode-result').prop('hidden', true);
				$('.close-btn').click();
				$('input[name=kode_barang]').valid();
				stopScan();
	  		}else{
	  			swal(
			        "",
			        "Kode barang tidak tersedia",
			        "error"
			    );
	  		}
	  	},
		  error: function(xhr, status, error) {
            if (xhr.status === 404) {
                swal("", "Kode barang tidak tersedia", "error");
            } else {
                swal("", "Terjadi kesalahan saat memeriksa kode barang", "error");
            }
        }
	  });
	});

	$(document).on('click', '.btn-tambah', function(e){
		e.preventDefault();
		$('form[name=manual_form]').valid();
		var kode_barang = $('input[name=kode_barang]').val();
		var jumlah = $('input[name=jumlah]').val();
		let harga_beli = $('input[name=harga_beli]').val();
		const harga_satuan = $("input[name=harga_satuan]").val();
		let total = 0;
		if (harga_beli == "") {
			harga_beli = harga_satuan;
			total = parseInt(jumlah) * parseInt(harga_satuan);
		} else {
			total = parseInt(jumlah) * parseInt(harga_beli);
		}
		if(validator.valid() == true){
			$.ajax({
				url: "{{ url('/supply/take') }}/" + kode_barang,
				method: "GET",
				success:function(response){
					var check = $('.kd-barang-field:contains('+ response.product.kode_barang +')').length;
					if(check == 0){
						$('input[name=kode_barang]').val('');
						$('input[name=jumlah]').val('');
						$('input[name=harga_beli]').val('');
						$("input[name=nama_barang]").val('');
           				$("input[name=harga_satuan]").val('');
           				$("input[name=harga_jual]").val('');
           				$("#detail-barang").prop('hidden', true);
						$('tbody').append(
							'<tr><td><span class="kd-barang-field">'+ response.product.kode_barang +
							'</span><span class="nama-barang-field">'+ response.product.nama_barang +
							'</span></td><td>'+ jumlah +
							'</td><td>Rp. '+ parseInt(harga_beli).toLocaleString() +
							'</td><td class="text-success">Rp. '+ parseInt(total).toLocaleString() +
							'</td><td><button type="button" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete"><i class="mdi mdi-close"></i></button><div class="form-group" hidden=""><input type="text" class="form-control" name="kode_barang_supply[]" value="'+ response.product.kode_barang +'"><input type="text" class="form-control" name="jumlah_supply[]" value="'+ jumlah +'"><input type="text" class="form-control" name="harga_beli_supply[]" value="'+ harga_beli +'"></div></td></tr>');
						$('.btn-simpan').prop('hidden', false);


                        // Simpan data ke localStorage
                        var supplyData = JSON.parse(localStorage.getItem("supply")) || [];
                        supplyData.push({
                            kode_barang: response.product.kode_barang,
                            nama_barang: response.product.nama_barang,
                            jumlah: jumlah,
                            harga_beli: harga_beli,
                            total: total
                        });
                        localStorage.setItem("supply", JSON.stringify(supplyData));

					}else{
						swal(
					        "",
					        "Barang telah ditambahkan",
					        "error"
					    );
					}
				}
			});
		}
	});

	$(document).ready(function () {
    var supplyData = JSON.parse(localStorage.getItem("supply")) || [];
    if (supplyData.length > 0) {
        $(".btn-simpan").prop("hidden", false);
        supplyData.forEach(function (item) {
			$('tbody').append(
				'<tr><td><span class="kd-barang-field">'+ item.kode_barang +
				'</span><span class="nama-barang-field">'+ item.nama_barang +
				'</span></td><td>'+ item.jumlah +
				'</td><td>Rp. '+ parseInt(item.harga_beli).toLocaleString() +
				'</td><td class="text-success">Rp. '+ parseInt(item.total).toLocaleString() +
				'</td><td><button type="button" class="btn btn-icons btn-rounded btn-secondary ml-1 btn-delete"><i class="mdi mdi-close"></i></button><div class="form-group" hidden=""><input type="text" class="form-control" name="kode_barang_supply[]" value="'+ item.kode_barang +'"><input type="text" class="form-control" name="jumlah_supply[]" value="'+ item.jumlah +'"><input type="text" class="form-control" name="harga_beli_supply[]" value="'+ item.harga_beli +'"></div></td></tr>');
        });
    }
});

$(document).on("click", ".btn-simpan", function () {
    var supplyData = JSON.parse(localStorage.getItem("supply")) || [];

    if (supplyData.length === 0) {
        swal("", "Tidak ada data untuk disimpan!", "warning");
        return;
    }

    $.ajax({
        url: "{{ url('/supply/store') }}", 
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}", 
            supply: supplyData, 
        },
        success: function (response) {
            swal("", "Data berhasil disimpan!", "success");
            localStorage.removeItem("supply"); 
            $("tbody").empty(); 
            $(".btn-simpan").prop("hidden", true);
        },
        error: function () {
            swal("", "Terjadi kesalahan saat menyimpan data!", "error");
        },
    });
});


</script>
@endsection