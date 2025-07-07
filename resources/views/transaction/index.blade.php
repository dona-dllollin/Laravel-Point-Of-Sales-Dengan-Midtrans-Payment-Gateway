@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/transaction/pagination.css') }}">
@endsection
@section('content')
<div class="row page-title-header">
  <div class="col-12">
    <div class="page-header d-flex justify-content-start align-items-center">
      <h4 class="page-title">Daftar Barang</h4>
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
              <div class="col-12">
                <div class="alert alert-danger kode_barang_error" role="alert" hidden="">
                  <i class="mdi mdi-information-outline"></i> Kode barang tidak tersedia
                </div>
              </div>
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
                @if($product->stok != 0)
                <li class="list-group-item d-flex justify-content-between align-items-center active-list">
                  <div class="text-group">
                    <p class="m-0">{{ $product->kode_barang }}</p>
                    <p class="m-0 txt-light">{{ $product->nama_barang }}</p>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="ammount-box bg-secondary mr-1"><i class="mdi mdi-cube-outline"></i></span>
                    <p class="m-0">{{ $product->stok }}</p>
                  </div>
                  <a href="#" class="btn btn-icons btn-rounded btn-inverse-outline-primary font-weight-bold btn-pilih" role="button"><i class="mdi mdi-chevron-right"></i></a>
                </li>
                @else 
                <li class="list-group-item d-flex justify-content-between align-items-center active-list">
                    <div class="text-group">
                      <p class="m-0">{{ $product->kode_barang }}</p>
                      <p class="m-0 txt-light">{{ $product->nama_barang }}</p>
                    </div>
                    <div class="d-flex align-items-center">
                      <span class="ammount-box bg-secondary mr-1"><i class="mdi mdi-cube-outline"></i></span>
                      <p class="m-0">{{ $product->stok }}</p>
                    </div>
                    <a href="#" class="btn btn-icons btn-rounded btn-inverse-outline-primary font-weight-bold btn-pilih disabled" role="button"><i class="mdi mdi-chevron-right"></i></a>
                  </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if (Session('transaction_success'))
  @php $message = session('transaction_success'); @endphp
  <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body bg-grey">
          <div class="row">
            <div class="col-12 text-center mb-4">
              <img src="{{ asset('gif/success4.gif') }}">
              <h4 class="transaction-success-text">Transaksi Berhasil</h4>
            </div>
            <div class="col-12">
              <table class="table-receipt">
                <tr>
                  <td>
                    <span class="d-block little-td">Kode Transaksi</span>
                    <span class="d-block font-weight-bold">{{ $message->kode_transaksi }}</span>
                  </td>
                  <td>
                    <span class="d-block little-td">Tanggal</span>
                    <span class="d-block font-weight-bold">{{ date('d M, Y', strtotime($message->created_at)) }}</span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <span class="d-block little-td">Kasir</span>
                    <span class="d-block font-weight-bold">{{ $message->kasir->nama }}</span>
                  </td>
                  <td>
                      @php
                       $diskon = $message->total_harga * $message->diskon / 100;
                       $total = $message->total_harga - $diskon;
                      @endphp
                    <span class="d-block little-td">Total</span>
                    <span class="d-block font-weight-bold text-success">Rp. {{ number_format($total,2,',','.') }}</span>
                  </td>
                </tr>
              </table>
              <table class="table-summary mt-3">
                <tr>
                  <td class="line-td" colspan="2"></td>
                </tr>
                <tr>
                  <td class="little-td big-td">Bayar</td>
                  <td>Rp. {{ number_format($message->bayar,2,',','.') }}</td>
                </tr>
                <tr>
                  <td class="little-td big-td">Kembali</td>
                  <td>Rp. {{ number_format($message->kembali,2,',','.') }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-close-modal" data-dismiss="modal">Tutup</button>
          <a href="{{ url('/transaction/cetak/' . $message->id) }}" target="_blank" class="btn btn-sm btn-cetak-pdf">Cetak Struk</a>
        </div>
      </div>
    </div>
  </div>
  @endif

</div>

  <div class="row">
    <div class="col-lg-7 col-md-7 col-sm-12 mb-4">
      <div class="row">
        <div class="col-12 mb-4 bg-dark-blue">
          <div class="card card-noborder b-radius">
            <div class="card-body">
              <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center transaction-header">
                  <div class="d-flex justify-content-start align-items-center">
                    <div class="icon-holder">
                      <i class="mdi mdi-swap-horizontal"></i>
                    </div>
                    <div class="transaction-code ml-3">
                      <p class="m-0 text-white">Kode Transaksi</p>
                      <p class="m-0 text-white">T{{ date('dmYHis') }}</p>
                      <input type="text" name="kode_transaksi" value="T{{ date('dmYHis') }}" hidden="">
                    </div>
                  </div>
                  <div class="btn-group mt-h">
                 
                    <button class="btn btn-search btn-scan" data-toggle="modal" data-target="#scanModal" type="button">
                      <i class="mdi mdi-crop-free" style="font-size: 20px"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
            <div class="card card-noborder b-radius shadow-sm">
                <div class="card-body">
                  <div class="form-group col-4 position-relative">
                    <i class="mdi mdi-magnify position-absolute btn-search" style="left: 20px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" class="form-control pl-4" name="search" placeholder="Cari barang">
                </div>
                
                    <div class="row d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex overflow-auto filter-chips-container">
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.transaction', ['category_id' => 'all']) : route('kasir.transaction', ['category_id' => 'all', 'slug_market' => session('slug_market')]) }}" 
                           class="btn btn-outline-primary category-link btn-sm me-2 mr-1 {{ request('category_id') == 'all' || !request('category_id') ? 'active' : ''  }}" data-category-id="all" >
                            All
                        </a>
                        @foreach ($categories as $category)
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.transaction', ['category_id' => $category->id]) : route('kasir.transaction', ['category_id' => $category->id, 'slug_market' => session('slug_market')]) }}" 
                           class="btn btn-outline-primary category-link btn-sm me-2 mr-1 {{ request('category_id') == $category->id ? 'active' : '' }}" data-category-id="{{ $category->id }}" >
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>

                </div>

                    <div class="row card-product">
                        @foreach ($products as $product)
                        <div class="col-12 col-md-6 col-lg-3 col-sm-4 mb-2 product-item">
                            <div class="productCard border rounded shadow-sm h-100 d-flex flex-column">
                                <div class="productImage position-relative pb-3" style="border-bottom: 1px solid #efefef;">
                                    
                                            @php
                                      $cart = session()->get('cart', []);
                                      $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
                                      @endphp
                                        <img class="card-img-top gambar img-fluid rounded-top p-1"
                                            src="{{ asset('pictures/product/'. $product->image) }}"
                                            alt="{{ $product->nama_barang }}"
                                            style="cursor: {{ $jumlahStok == 0 ? 'not-allowed' : 'pointer' }};"
                                            onclick="{{ $product->stok > 0 ? 'this.closest(\'form\').submit();return false;' : '' }}">
                                        <div
                                            data-product-id="{{ $product->id }}" data-product-kode="{{ $product->kode_barang }}"
                                            class="overlay-cart position-absolute ml-2 mb-1 rounded text-center {{ $jumlahStok == 0 ? 'bg-secondary disabled' : 'bg-primary' }}"
                                            style="bottom: 0; opacity: 0.9;">
                                            <p style="display:none">{{$product->kode_barang}}</p>
                                            <button type="submit" onclick="addToCart('{{$product->kode_barang}}')" data-product-id="{{ $product->id }}" data-product-kode="{{ $product->kode_barang }}"  class="btn btn-sm btn-pilih text-white {{ $jumlahStok == 0 ? 'disabled' : '' }}">
                                                <i class="mdi mdi-cart"></i>
                                            </button>
                                        </div>
                                  
                                </div>
                                <div class=" text-center flex-column flex-grow-1" style="margin-bottom: 2%; margin-top:2%; padding-bottom:5%">
                                    
                                    <h6 class="card-title font-weight-bold text-truncate"
                                        title="{{ $product->nama_barang }}">{{ Str::words($product->nama_barang, 4) }}
                                    </h6>
                                    @php
                                      $cart = session()->get('cart', []);
                                      $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
                                      @endphp
                                    <h6 class="card-title font-weight-bold text-truncate">
                                      (<span class="stok-display" data-product-id="{{ $product->id }}" data-product-kode="{{ $product->kode_barang }}">{{ $jumlahStok }}</span>)
                                    </h6>
                                    <p class="card-text">{{$product->market->nama_toko}}</p>
                                    <p class="card-text text-success font-weight-bold">Rp. {{ number_format($product->harga_jual, 2, ',', '.') }}</p>
                                  
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                  <div class="pagination-container">
                      <ul class="pagination"></ul>
                  </div>
                    @if ($products->count() === 0)
                    <p>Produk Kosong</p>
                    @endif
                </div>
            </div>
        </div>
        
      </div>
    </div>
    
    <div class="col-lg-5 col-md-5 col-sm-12">
      <div class="card card-noborder b-radius">
        <div class="card-body">
        <form method="POST" name="transaction_form" id="transaction_form" action="{{ url('/transaction/process') }}">
            @csrf
          <div class="row">
            <div class="col-12 d-flex justify-content-start align-items-center">
                <div class="cart-icon mr-3">
                  <i class="mdi mdi-cart-outline"></i>
                </div>
                <p class="m-0 text-black-50">Daftar Pesanan</p>
              </div>
              <div class="col-12 mt-3 table-responsive">
                <table class="table table-checkout" style="font-size: 8px">
                  @if(session('cart'))
                  @foreach(session('cart') as $id => $details)
                  <tr>
                    <td>
                      <input type="text" name="kode_barang[]" hidden="" value="${kode}">
                      <span class="nama-barang-td text-truncate">{{$details['name']}}</span>
                      <span class="kode-barang-td">{{$details['kode_barang']}}</span>
                    </td>
                    <td>
                      <input type="text" name="harga_barang[]" hidden="" value="${harga}">
                      <span class="numeric-barang-td">Rp. {{$details['price']}}</span>
                    </td>
                    <td>
                      <div class="d-flex justify-content-start align-items-center">
                        <input type="text" name="jumlah_barang[]" hidden="" value="1">
                        <a href="#" class="btn-operate mr-1 btn-tambah" onClick="increaseQuantity({{$id}})">
                          <i class="mdi mdi-plus-box" style="color: green; font-size:20px"></i>
                        </a>
                        <span class="ammount-product mr-1" unselectable="on" onselectstart="return false;" onmousedown="return false;">
                          <p class="jumlah_barang_text">{{$details['quantity']}}</p>
                        </span>
                        <a href="#" class="btn-operate btn-kurang" onClick="decreaseQuantity({{$id}})">
                          <i class="mdi mdi-minus-box" style="color: red; font-size: 20px"></i>
                        </a>
                      </div>
                    </td>
                    <td>
                      <input type="text" class="total_barang" name="total_barang[]" hidden="" value="${harga}">
                      <span class="numeric-barang-td">Rp. {{$details['subtotal']}}</span>
                    </td>
                    <td>
                      <a href="#" class="btn btn-hapus" onClick="removeItem({{$id}})">
                        <i class="mdi mdi-delete" style="font-size: 20px; color:red"></i>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                  @else
                    <tr>
                        <td>Keranjang Kosong</td>
                    </tr>
                    @endif
                </table>
              </div>
            <div class="col-12 payment-1">
              <table class="table-payment-1">
                <tr>
                  <td class="text-left">Kode Transaksi</td>
                  <td class="text-right"> T{{ date('dmYHis') }}</td>
                  <input type="text" name="kode_transaksi" value="T{{ date('dmYHis') }}" hidden="">
                </tr>
                <tr>
                  <td class="text-left">Tanggal</td>
                  <td class="text-right">{{ date('d M, Y') }}</td>
                </tr>
                <tr>
                  <td class="text-left">Waktu</td>
                  <td class="text-right">{{ date('H:i') }}</td>
                </tr>
                <tr>
                  <td class="text-left">Kasir</td>
                  <td class="text-right">{{ auth()->user()->nama }}</td>
                </tr>
              </table>
            </div>
            <div class="col-12 mt-4">
              <table class="table-payment-2">
                <tr>
                  <td class="text-left">
                    <span class="subtotal-td">Subtotal</span>
                    <span class="jml-barang-td">{{$totalQuantity}} Barang</span>
                  </td>
                  <td class="text-right nilai-subtotal1-td">{{$totalSubtotal}}</td>
                  <td hidden=""><input type="text" class="nilai-subtotal2-td" name="subtotal" value="{{$totalSubtotal}}"></td>
                </tr>
                <tr>
                  <td class="text-left">
                    <span class="diskon-td">Diskon</span>
                    <a href="#" class="ubah-diskon-td">Ubah diskon</a>
                    <a href="#" class="simpan-diskon-td" hidden="">Simpan</a>
                  </td>
                  <td class="text-right d-flex justify-content-end align-items-center pt-2">
                    <input type="number" class="form-control diskon-input mr-2" min="0" max="100" name="diskon" value="0" hidden="">
                    <span class="nilai-diskon-td mr-1">0</span>
                    <span>%</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-center nilai-total1-td">Rp. {{number_format($totalSubtotal, 2, ',', '.')}}</td>
                  <td hidden=""><input type="text" class="nilai-total2-td" name="total" value="{{$totalSubtotal}}"></td>
                </tr>
              </table>
            </div>
            <div class="col-12 mt-2">
              <table class="table-payment-3">
                <tr>
                  <label for="payment_method">Metode Pembayaran</label>
                </tr>
                <tr>
                  <td>
                    <div class="input-group">
                      <select name="payment_method" id="payment_method" class="form-control">
                        <option value="Tunai">Tunai</option>
                        <option value="Transfer">Transfer</option>
                      </select>
                    </div>
                  </td>
                </tr>
                <tr id="bayar-row">
                  <td>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                      </div>
                      <input type="text" class="form-control number-input input-notzero bayar-input" name="bayar" placeholder="Masukkan nominal bayar">
                    </div>
                  </td>
                </tr>
                <tr class="nominal-error" hidden="">
                  <td class="text-danger nominal-min">Nominal bayar kurang</td>
                </tr>
                <tr>
                  
                  <td class="text-right">
                    <button class="btn btn-bayar" type="button"> <i class="mdi mdi-check"></i> Bayar</button>
                    <button type="button" class="btn btn-warning" id="btn-utang" data-toggle="modal" data-target="#utangModal">
                      <i class="mdi mdi-cash"></i> Utang
                    </button>
                  </td>
                </tr>
              </table>

              <!-- Modal Utang -->
              <div class="modal fade" id="utangModal" tabindex="-1" role="dialog" aria-labelledby="utangModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!-- input lain dari transaksi -->
                    <input type="hidden" name="action" value="utang">
                    <!-- isian transaksi (produk, total, dll) bisa dikirim ulang via input hidden atau disimpan di session -->
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="utangModalLabel">Data Pengutang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Nama Pengutang</label>
                          <input type="text" name="nama_pengutang" id="nama_pengutang" class="form-control" required>
                        </div>
                        <div class="form-group">
                          <label>DP (Uang Muka)</label>
                          <input type="number" name="dp" id="dp" class="form-control" min="0" placeholder="Boleh kosong">
                          <input type="hidden" class="total_utang" value="{{$totalSubtotal}}">
                          <span class="nominal-utang-error text-danger nominal-min" hidden="">dp tidak boleh lebih besar atau sama dengan total</span>
                        </div>

                      </div>
                      <div class="modal-footer">
                        <button type="button" id="submitUtangBtn" class="btn btn-primary">Simpan Utang</button>
                      </div>
                    </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
@section('script')

<script src="{{ asset('plugins/js/quagga.min.js') }}"></script>
<script src="{{ asset('js/transaction/script.js') }}"></script>
<script src="{{ asset('js/transaction/pagination.js') }}"></script>
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{config('midtrans.client_key')}}"></script>



<script type="text/javascript">

@if (Session('transaction_success') && !session('snapToken'))
  $('#successModal').modal('show');
@endif

@if ($message = Session::get('transaction_error'))
    swal(
        "",
        "{{ $message }}",
        "error"
    );
  @endif

// Fungsi untuk menambahkan data ke keranjang
function addToCart(kodeBarang) {
        $.ajax({
            url: '/transaction/product/' + kodeBarang, // Sesuaikan dengan route Anda
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    // alert(response.message); 
                    console.log(response); // Debug data keranjang
                    tambahData(response.cart)
                     // Update stok di tampilan
                   $(`.stok-display[data-product-kode="${kodeBarang}"]`).text(response.jumlahStok);

                   if(response.jumlahStok === 0){
                    $(`.overlay-cart[data-product-kode="${kodeBarang}"]`).addClass('bg-secondary disabled')
                    $(`.btn-pilih[data-product-kode="${kodeBarang}"]`).addClass('disabled')
                   }else {
                    $(`.overlay-cart[data-product-kode="${kodeBarang}"]`).removeClass('bg-secondary disabled').addClass('bg-primary')
                    $(`.btn-pilih[data-product-kode="${kodeBarang}"]`).removeClass('disabled')
                   }
                   
                } else if (response.error){
                          swal(
                    "",
                    response.message,
                    "error"
                );
                } else {

                  alert('Terjadi kesalahan.');
                }
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseJSON.error);
            }
        });
    }

   // Fungsi untuk mengurangi quantity produk 
function decreaseQuantity(id) {
  $.ajax({
      url: '/cart/decrease/' + id,
      method: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function (response) {
          if (response.success) {
              tambahData(response.cart); // Refresh keranjang

               // Update stok di tampilan
            $(`.stok-display[data-product-id="${id}"]`).text(response.jumlahStok);

            if(response.jumlahStok == 0){
                    $(`.overlay-cart[data-product-id="${id}"]`).addClass('bg-secondary disabled')
                    $(`.btn-pilih[data-product-id="${id}"]`).addClass('disabled')
                   }else {
                    $(`.overlay-cart[data-product-id="${id}"]`).removeClass('bg-secondary disabled').addClass('bg-primary')
                    $(`.btn-pilih[data-product-id="${id}"]`).removeClass('disabled')
                   }
                   

          } else {
              alert('Gagal mengurangi quantity.');
          }
      },
      error: function (xhr) {
          alert('Error: ' + xhr.responseJSON.message);
      }
  });
}

// Fungsi untuk menghapus item dari keranjang
function removeItem(id) {
  $.ajax({
      url: '/cart/remove/' + id,
      method: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function (response) {
          if (response.success) {
              tambahData(response.cart); // Refresh keranjang
               // Update stok di tampilan
            $(`.stok-display[data-product-id="${id}"]`).text(response.jumlahStok);

            if(response.jumlahStok == 0){
                    $(`.overlay-cart[data-product-id="${id}"]`).addClass('bg-secondary disabled')
                    $(`.btn-pilih[data-product-id="${id}"]`).addClass('disabled')
                   }else {
                    $(`.overlay-cart[data-product-id="${id}"]`).removeClass('bg-secondary disabled').addClass('bg-primary')
                    $(`.btn-pilih[data-product-id="${id}"]`).removeClass('disabled')
                   }

          } else {
              alert('Gagal menghapus item.');
          }
      },
      error: function (xhr) {
          alert('Error: ' + xhr.responseJSON.message);
      }
  });
}

// Fungsi untuk menambah quantity produk
function increaseQuantity(id) {
  $.ajax({
      url: '/cart/increase/' + id,
      method: 'POST',
      data: { _token: '{{ csrf_token() }}' },
      success: function (response) {
          if (response.success) {
              tambahData(response.cart); // Refresh keranjang
               // Update stok di tampilan
            $(`.stok-display[data-product-id="${id}"]`).text(response.jumlahStok);

            if(response.jumlahStok == 0){
                    $(`.overlay-cart[data-product-id="${id}"]`).addClass('bg-secondary disabled')
                    $(`.btn-pilih[data-product-id="${id}"]`).addClass('disabled')
                   }else {
                    $(`.overlay-cart[data-product-id="${id}"]`).removeClass('bg-secondary disabled').addClass('bg-primary')
                    $(`.btn-pilih[data-product-id="${id}"]`).removeClass('disabled')
                   }

          } else if (response.error){
            swal(
                    "",
                    response.message,
                    "error"
                );
          }else {
              alert('Gagal menambah quantity.');
            
          }
      },
      error: function (xhr) {
          alert('Error: ' + xhr.responseJSON.message);
      }
  });
}


function startScan() {
  Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      target: document.querySelector('#area-scan')
    },
    decoder : {
      readers : ["ean_reader", "upc_reader", "code_128_reader", "code_39_reader"],
      multiple: false
    },
    locate: false
  }, function(err) {
      if (err) {
          console.log(err);
          return
      }
      console.log("Initialization finished. Ready to start");
      Quagga.start();
  });

  Quagga.onDetected(function(data){
    $('#area-scan').prop('hidden', true);
    $('#btn-scan-action').prop('hidden', false);
    $('.barcode-result').prop('hidden', false);
    $('.barcode-result-text').html(data.codeResult.code);
    $('.kode_barang_error').prop('hidden', true);
    stopScan();
  });
}

$(document).on('click', '.btn-scan', function(){
  $('#area-scan').prop('hidden', false);
  $('#btn-scan-action').prop('hidden', true);
  $('.barcode-result').prop('hidden', true);
  $('.barcode-result-text').html('');
  $('.kode_barang_error').prop('hidden', true);
  startScan();
});

$(document).on('click', '.btn-repeat', function(){
  $('#area-scan').prop('hidden', false);
  $('#btn-scan-action').prop('hidden', true);
  $('.barcode-result').prop('hidden', true);
  $('.barcode-result-text').html('');
  $('.kode_barang_error').prop('hidden', true);
  startScan();
});

// Menangani klik pada tombol "Lanjutkan scan"
$(document).on('click', '.btn-continue', function(e){
  e.stopPropagation();
  var kode_barang = $('.barcode-result-text').text();
  $.ajax({
    url: "{{ url('/transaction/product/check') }}/" + kode_barang,
    method: "GET",
    success:function(response){
      if(response.success){
          tambahData(response.cart);
          $(`.stok-display[data-product-kode="${kode_barang}"]`).text(response.jumlahStok);
          $('.close-btn').click();  
      
      }else if (response.errorBarang){
        swal("", response.message,"error");
      }  else if (response.errorKode){
        $('.kode_barang_error').prop('hidden', false);
      } else {
        swal("", "Terjadi kesalahan", "error");
      }
    }
  });
});


// Menangani klik pada tombol "Bayar"
$(document).on('click', '.btn-bayar', function(){
  let paymentMethod = $('select[name="payment_method"]').val();
  var check_barang = parseInt($('.jumlah_barang_text').length);

 
  
if (paymentMethod === 'Tunai') {
  var total = parseInt($('.nilai-total2-td').val());
  var bayar = parseInt($('.bayar-input').val());
  if(bayar >= total){
    $('.nominal-error').prop('hidden', true);
    if(check_barang != 0){
      if($('.diskon-input').attr('hidden') != 'hidden'){
        $('.diskon-input').addClass('is-invalid');
      }else{

        $('<input>').attr({
        type: 'hidden',
        name: 'action',
        value: 'bayar'
    }).appendTo('#transaction_form');
    
        sessionStorage.removeItem('disc')
        sessionStorage.removeItem('diskon')
        $('#transaction_form').submit();
      }
    }else{
      swal(
          "",
          "Pesanan Kosong",
          "error"
      );
    }
  }else{
    if(isNaN(bayar)) {
      $('.bayar-input').valid();
    }else{
      $('.nominal-error').prop('hidden', false);
    }
    
    if(check_barang == 0){
      swal(
          "",
          "Pesanan Kosong",
          "error"
      );
    }
  }
} else {
  if(check_barang != 0){
      if($('.diskon-input').attr('hidden') != 'hidden'){
        $('.diskon-input').addClass('is-invalid');
      }else{
        $('<input>').attr({
        type: 'hidden',
        name: 'action',
        value: 'bayar'
        }).appendTo('#transaction_form');

        sessionStorage.removeItem('disc')
        sessionStorage.removeItem('diskon')
        $('#transaction_form').submit();
      }
    }else{
      swal(
          "",
          "Pesanan Kosong",
          "error"
      );
    }
}
});

// Menangani klik pada tombol "Simpan Utang"
$(document).on('click', '#submitUtangBtn', function () {

// Cek apakah ada barang dalam keranjang
    var check_barang = parseInt($('.jumlah_barang_text').length);
    const bayarRow = document.getElementById("bayar-row");

    var total = parseInt($('.total_utang').val());
    var bayar = parseInt($('#dp').val());
    if (check_barang != 0) {
      if ($('.diskon-input').attr('hidden') != 'hidden') {
        $('.diskon-input').addClass('is-invalid');
      } else {
        if (isNaN(bayar) || bayar < total) {
          sessionStorage.removeItem('disc')
          sessionStorage.removeItem('diskon')
          bayarRow.style.display = "none"; 
          $('#transaction_form').submit();
        } else {
          $('.nominal-utang-error').prop('hidden', false);
        }
      }
    } else {
      swal(
          "",
          "Pesanan Kosong",
          "error"
      );
    }
});




$(document).on('change', '.market-select', function () {
    const url = new URL(window.location.href); // Ambil URL saat ini

    // Tambahkan atau ubah query parameter 'market_id'
    if ($(this).val() !== 'all') {
        url.searchParams.set('market_id', $(this).val());
    } else {
        url.searchParams.delete('market_id');
    }

    // Redirect ke URL yang diperbarui
    window.location.href = url.toString();

});

$(document).on('click', '.category-link', function (e) {
    e.preventDefault(); // Mencegah aksi default link
    const url = new URL(window.location.href); // Ambil URL saat ini
    const category_id = $(this).data('category-id'); // Ambil ID kategori dari atribut data

    // Tambahkan atau ubah query parameter 'category_id'
    if (category_id !== 'all') {
        url.searchParams.set('category_id', category_id);
    } else {
        url.searchParams.delete('category_id');
    }

    // Redirect ke URL yang diperbarui
    window.location.href = url.toString();
});


document.addEventListener("DOMContentLoaded", function () {
    const paymentMethod = document.getElementById("payment_method");
    const bayarRow = document.getElementById("bayar-row");
    const btnUtang = document.getElementById("btn-utang");

    // Fungsi untuk mengontrol tampilan input
    function toggleBayarInput() {
      if (paymentMethod.value === "Tunai") {
        btnUtang.disabled = false;
        btnUtang.classList.remove("btn-secondary");
        btnUtang.classList.add("btn-warning");

        bayarRow.style.display = ""; // Tampilkan
      } else {
        btnUtang.classList.remove('btn-warning');
        btnUtang.classList.add('btn-secondary');
        bayarRow.style.display = "none"; // Sembunyikan
        btnUtang.disabled = true;

      }
    }

    // Panggil fungsi saat halaman dimuat
    toggleBayarInput();

    // Tambahkan event listener untuk perubahan select
    paymentMethod.addEventListener("change", toggleBayarInput);
  });




</script>



<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{config('midtrans.client_key')}}"></script>
@if (session('snapToken'))
    <script>
         snap.pay('{{ session('snapToken') }}', {
            onSuccess: function(result) {
                alert('Pembayaran berhasil!');
                 // Trigger buka modal setelah sukses
                 $('#successModal').modal('show');

                
            },
            onPending: function(result) {
                alert('Pembayaran tertunda.');
            },
            onError: function(result) {
                alert('Terjadi kesalahan pembayaran.');
            },
            onClose: function() {
                alert('Anda belum menyelesaikan pembayaran.');
            }
        });
    </script>
    
@endif



@endsection