@extends('templates/main')
@section('css')
<link rel="stylesheet" href="{{ asset('css/utang/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/manage_product/product/pagination.css') }}">

<style>
  .tersedia-span {
    color: #19d895;
    font-weight: bold;
    
}

.tersedia-span:hover {
    color: #19d895;
}
  .habis-span {
    color: #cbd819;
    font-weight: bold;
 
}

.habis-span:hover {
    color: #d2f109;
}

.btn-search {
    background-color: #fff;
    color: #2196f3;
    font-size: 18px !important;
}

.btn-filter-chart,
.btn-filter-chart:hover {
    background-color: #435df2;
    color: #fff;
    font-weight: bold;
}

</style>
@endsection
@section('content')
<div class="row page-title-header">
  <div class="col-12">
    <div class="page-header d-flex justify-content-between align-items-center">
      <h4 class="page-title">Transaksi Utang</h4>
      <div class="print-btn-group">
        @if(Auth::user()->role == 'admin')
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="mdi mdi-export print-icon"></i>
            </div>
            <button class="btn btn-print" type="button" data-toggle="modal" data-target="#cetakModal">Export Laporan</button>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
<div class="row modal-group">
  <div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cetakModalLabel">Export Laporan</h5>
          <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{url('/debt/export/utang')}}" name="export_form" method="POST" target="_blank">
            @csrf
            <div class="row">
              <div class="col-12">
                <div class="form-group row">
                  <div class="col-5 border rounded-left offset-col-1">
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="jns_laporan" value="period" checked> Periode</label>
                    </div>
                  </div>
                  <div class="col-5 border rounded-right">
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="jns_laporan" value="manual"> Manual</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 period-form">
                <div class="form-group row">
                  <div class="col-10 p-0 offset-col-1">
                    <p>Pilih waktu dan periode</p>
                  </div>
                  <div class="col-4 p-0 offset-col-1">
                    <input type="number" class="form-control form-control-lg time-input number-input dis-backspace input-notzero" name="time" value="1" min="1" max="4">
                  </div>
                  <div class="col-6 p-0">
                    <select class="form-control form-control-lg period-select" name="period">
                      <option value="minggu" selected="">Minggu</option>
                      <option value="bulan">Bulan</option>
                      <option value="tahun">Tahun</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-12 manual-form" hidden="">
                <div class="form-group row">
                  <div class="col-10 p-0 offset-col-1">
                    <p>Pilih tanggal awal dan akhir</p>
                  </div>
                  <div class="col-10 p-0 offset-col-1 input-group mb-2">
                    <input type="date" name="tgl_awal_export" class="form-control form-control-lg date" placeholder="Tanggal awal">
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <i class="mdi mdi-calendar calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-10 p-0 offset-col-1 input-group">
                    <input type="date" name="tgl_akhir_export" class="form-control form-control-lg date" placeholder="Tanggal akhir">
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <i class="mdi mdi-calendar calendar-icon"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 ">
                <div class="form-group row">
                  <div class="col-10 p-0 offset-col-1">
                    <p>Pilih Status Utang</p>
                  </div>
                  <div class="col-10 p-0 offset-col-1 input-group mb-2">
                    <select name="statusExport" class="form-control form-control-lg me-2 mr-2">
                      <option value="all" {{ $statusExport === 'all' ? 'selected' : '' }}>Semua</option>
                      <option value="lunas" {{ $statusExport === 'lunas' ? 'selected' : '' }}>Lunas</option>
                      <option value="pending" {{ $statusExport === 'pending' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                  <div class="input-group-append">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row">
                  <div class="col-2  offset-col-1">
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="format" value="pdf" checked> pdf</label>
                    </div>
                  </div>
                  <div class="col-2 ">
                    <div class="form-radio">
                      <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="format" value="excel"> excel</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-export">Export</button>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12 mb-4">
    <div class="card card-noborder b-radius">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3 ">
              <h5 class="mb-0">Statistik Utang</h5>
              <form name="filter_form_chart" method="GET" action="{{ url('/debt') }}">
                @csrf
                <div class="form-group row filter-group mx-3 px-1 w-100">
                  <div class="col-lg-3 col-md-12 col-sm-12 col-12 search-div">
                  <select name="statusChart" class="form-control form-control-lg market-select">
                    <option value="all" {{ $statusChart === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="lunas" {{ $statusChart === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ $statusChart === 'pending' ? 'selected' : '' }}>Belum Lunas</option>     
                  </select>
                  </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 input-group">
                    <input type="date" name="tgl_awal_chart" class="form-control form-control-lg date" placeholder="Tanggal awal">
                  </div>
                  <div class="col-lg-3 col-md-4 col-sm-6 col-12 input-group ">
                    <input type="date" name="tgl_akhir_chart"  class="form-control form-control-lg date" placeholder="Tanggal akhir">
                  </div>
                  <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                    <button class="btn btn-filter-chart btn-sm btn-block" type="button">Filter</button>
                  </div>
                </div>
              </form>
            </div>
            
          </div>
          <div class="col-12 mt-4">
            <div style="width: 100%; height: 300px;">
                <canvas id="myChart" ></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 grid-margin">
    <div class="card card-noborder b-radius">
      <div class="card-body">
        <div class="row">
          <div class="col-12 mb-2">
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 mb-3 search-div ml-auto">
              <i class="mdi mdi-magnify position-absolute btn-search" style="left: 20px; top: 50%; transform: translateY(-50%);"></i>
              <input type="text" name="search" class="form-control form-control-lg pl-4" placeholder="Cari transaksi">
            </div>
            <form name="filter_form" method="GET" action="{{ url('/debt') }}">
              @csrf
              <div class="form-group row align-items-center filter-group">

                <div class="col-lg-4 col-md-12 col-sm-12 col-12 search-div">
                <select name="status" class="form-control form-control-lg market-select">
                  <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                  <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                  <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Belum Lunas</option>     
                </select>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 input-group">
                  <input type="date" name="tgl_awal" class="form-control form-control-lg date" placeholder="Tanggal awal">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 input-group tgl-akhir-div">
                  <input type="date" name="tgl_akhir"  class="form-control form-control-lg date" placeholder="Tanggal akhir">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12 col-12 filter-btn-div">
                  <button class="btn btn-filter btn-sm btn-block" type="button">Filter</button>
                </div>
              </div>
            </form>
          </div>
        	<div class="col-12">
            <ul class="list-date">
              @foreach($dates as $date)
              <div class="product-item">
              <li class="txt-light">{{ date('d M, Y', strtotime($date)) }}</li>
              {{-- <h4>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h4> --}}
              <div class="table-responsive">
                <table class="table table-custom">
                  <tr>
                    <th>Nama Pengutang</th>
                    <th>DP</th>
                    <th>Jumlah Angsur</th>
                    <th>sisa</th>
                    <th>total</th>
                    <th>status</th>
                    <th>angsur</th>
                    <th></th>
                  </tr>
                  @foreach($debts->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59') as $trx)
                  <tr class="debt-row">
                    <td class="td-1">
                      <span class="d-block font-weight-bold big-font">{{ $trx->nama_pengutang }}</span>
                      <span class="d-block mt-2 txt-light">{{ \Carbon\Carbon::parse($trx->created_at)->locale('id')->translatedFormat('l, d F, Y'). ' pada ' . date('H:i', strtotime($trx->created_at)) }}</span>
                    </td>
                    <td>Rp. {{ number_format($trx->dp,2,',','.') }}</td>
                    <td class="text-success font-weight-bold">Rp. {{ number_format($trx->total_angsuran,2,',','.') }}</td>
                    <td class="text-danger font-weight-bold">Rp. {{ number_format($trx->sisa,2,',','.') }}</td>
                    <td><span class="ammount-box bg-green"><i class="mdi mdi-coin"></i></span>Rp. {{ number_format($trx->transaction->total,2,',','.') }}</td>
                    <td class="text-center">
                      @if($trx->status == 'lunas')
                      <span class="btn tersedia-span">Lunas</span>
                      @elseif($trx->status == 'pending')
                      <span class="btn habis-span">Belum Lunas</span>
                      @endif
                    </td>
                    <td> 
                        @if($trx->status == 'pending')
                        <button class="btn btn-sm btn-cetak" data-toggle="modal" data-target="#angsurModal{{$trx->id}}" style=" background-color: #03c02c; color: #fff; font-weight: bold;">Angsur</button>
                        @else
                        @if(auth()->user()->role == 'admin')
                        <form action="{{url('/debt/delete/'. $trx->id)}}" method="POST" onsubmit="return confirmDelete(event)" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-secondary ml-1 btn-delete" style="background-color: rgb(255, 0, 0);color: #fff; font-weight: bold;" >
                              Hapus
                          </button>
                      </form>
                        @endif
                        @endif
                    </td>
                    <td>
                      <button class="btn btn-selengkapnya font-weight-bold" type="button" data-target="#dropdownTransaksi{{ $trx->id }}"><i class="mdi mdi-chevron-down arrow-view"></i></button>
                    </td>
                  </tr>
                  <tr id="dropdownTransaksi{{ $trx->id }}" data-status="0" class="dis-none">
                    <td colspan="5" class="dropdown-content">
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-wrap gap-3">
                        <div class="kasir mr-3 mb-3">
                          Kasir : {{ auth()->user()->nama }}
                        </div>
                        <div class="total-barang mt-2">
                          total Angsuran : {{ $trx->payments->count() }}
                        </div>
                       </div>
                        <div class="filter-btn-div mb-3">
                          <a href="" target="_blank" data-toggle="modal" data-target="#detailModal{{$trx->id}}" class="btn btn-sm btn-cetak" style=" background-color: #1d0ee9; color: #fff; font-weight: bold;">Detail</a>
                        </div>
                      </div>
                      <table class="table">
                        @if( $trx->payments && $trx->payments->count() > 0)
                        <tr>
                            <th>Nomor</th>
                            <th>Jumlah Angsuran</th>
                            <th>Sisa Angsuran</th>
                            <th>dibayar oleh</th>
                            <th>tanggal</th>
                          </tr>
                          @endif
                        @foreach($trx->payments as $angsur)

                        <tr>
                          <td><span class="numbering">{{ $loop->iteration }}</span></td>
                          <td>
                            <span class="bold-td">Rp. {{ number_format($angsur->jumlah_bayar,2,',','.') }}</span>
                          </td>
                          <td>
                            <span class="bold-td">Rp. {{ number_format($angsur->sisa_angsuran,2,',','.') }}</span>
                          </td>
                          <td>{{ $angsur->dibayar_oleh }}</td>
                          <td><span class="d-block mt-2 txt-light">{{ \Carbon\Carbon::parse($angsur->created_at)->locale('id')->translatedFormat('l, d F, Y') . ' pada ' . date('H:i', strtotime($angsur->created_at)) }}</span></td>
                          
                        </tr>
                        @endforeach
                      </table>
                    
                    </td>
                  </tr>
                  @endforeach
                </table>

                

              </div>
            </div>
              @endforeach
            </ul>
            <div class="pagination-container">
              <ul class="pagination"></ul>
            </div>
        	</div>
        </div>
      </div>
    </div>
  </div>
</div>


{{-- modal dipindah ke sini --}}
@foreach($debts as $trx)

  {{-- Modal untuk menampilkan form angsuran --}}
    <div class="modal fade" id="angsurModal{{$trx->id}}" tabindex="-1" role="dialog" aria-labelledby="angsurModalLabel{{$trx->id}}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Data Angsuran {{$trx->nama_pengutang}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form method="post" action="{{url('/debt/payment/'. $trx->id)}}" id="angsuran_form_{{$trx->id}}" name="angsuran_form">
                @csrf
                <div class="form-group">
                <label>Angsuran</label>
                <input type="number" name="jumlah_bayar" id="jumlah_bayar{{$trx->id}}" class="form-control" min="0" required >
                <span class="nominal-utang-error text-danger nominal-min" style="font-size: 15px" hidden="">jumlah bayar tidak boleh lebih besar dari total utang</span>
                <span class="nominal-utang-error2 text-danger nominal-min" style="font-size: 15px" hidden="">jumlah pembayaran tidak boleh kosong</span>
                </div>
                <div class="form-group">
                    <label>Nama Pembayar</label>
                    <input type="text" name="dibayar_oleh" id="nama_pengutang" class="form-control" placeholder="Boleh kosong" >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="submitAngsurBtn{{$trx->id}}" class="btn btn-primary">Simpan Angsuran</button>
            </div>
        </div>
    </form>
        </div>
    </div>

  
    <div class="row modal-group">
      <div class="modal fade" id="detailModal{{$trx->id}}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{$trx->id}}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            
            <div class="modal-header">
              <h5 class="modal-title">Detail Transaksi Utang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
    
            <div class="modal-body">
              <div class="container-fluid">
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Nomor Transaksi</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">{{ $trx->transaction->kode_transaksi }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Nama Pengutang</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">{{ $trx->nama_pengutang }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Tanggal Transaksi</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">
                      {{ \Carbon\Carbon::parse($trx->created_at)->locale('id')->translatedFormat('l, d F Y') . ' pada ' . date('H:i', strtotime($trx->created_at)) }}
                    </p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">DP</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">Rp {{ number_format($trx->dp,2,',','.') }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Jumlah Angsur</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">{{ $trx->total_angsuran }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Sisa Angsuran</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">{{ $trx->sisa }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Total</label>
                  <div class="col-sm-8">
                    <p class="form-control-plaintext mb-0">Rp {{ number_format($trx->transaction->total,2,',','.') }}</p>
                  </div>
                </div>
    
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label font-weight-bold">Status</label>
                  <div class="col-sm-8">
                    @if($trx->status == 'lunas')
                      <span class="badge badge-success">Lunas</span>
                    @elseif($trx->status == 'pending')
                      <span class="badge badge-warning">Belum Lunas</span>
                    @endif
                  </div>
                </div>
    
              </div>
            </div>

            <!-- Tabel Poduk Yang Dibeli-->
            <div class="row mb-4 mx-3">
              <div class="col-12 col-sm-8">
                <h6 class="font-weight-bold">Produk yang Dibeli</h6>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($trx->transaction->item as $index => $product)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->product->nama_barang }}</td>
                        <td>{{ $product->total_barang }}</td>
                        <td>Rp {{ number_format($product->product->harga_jual, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->subtotal, 2, ',', '.') }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tabel Angsuran-->
            <div class="row mb-4 mx-3">
              <div class="col-12 col-sm-8">
                <h6 class="font-weight-bold">Tabel Angsuran</h6>
                <div class="table-responsive">
                  <table class="table table-bordered table-sm">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>jumlah bayar</th>
                        <th>Sisa Angsuran</th>
                        <th>Dibayar Oleh</th>
                        <th>Tanggal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($trx->payments as $index => $angsur)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>Rp. {{ number_format($angsur->jumlah_bayar,2,',','.') }}</td>
                        <td>Rp. {{ number_format($angsur->sisa_angsuran,2,',','.') }}</td>
                        <td>{{ $angsur->dibayar_oleh }}</td>
                        <td>{{ \Carbon\Carbon::parse($angsur->created_at)->locale('id')->translatedFormat('l, d F, Y') . ' pada ' . date('H:i', strtotime($angsur->created_at)) }}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
    
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    
@endforeach


@endsection
@section('script')
<script src="{{ asset('plugins/js/Chart.min.js') }}"></script>
<script src="{{ asset('js/utang/script.js') }}"></script>
<script src="{{ asset('js/report/report_transaction/pagination.js') }}"></script>
<script type="text/javascript">
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartData->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M Y'))) !!},
        datasets: [{
            label: 'Pemasukan',
            data:{!! json_encode($chartData->pluck('jumlah')) !!},
            backgroundColor: 'RGB(255, 0, 0)',
            borderColor: 'RGB(255, 0, 0)',
            borderWidth: 0
        }]
    },
    options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 0
            },
            title: {
            display: false,
            text: ''
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                      if (parseInt(value) >= 1000) {
                         return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                      } else {
                         return 'Rp. ' + value;
                      }
                   }
                }
            }],
            xAxes: [{
                barPercentage: 0.1
            }]
        },
        legend: {
            display: false
        },
        tooltips: {
            callbacks: {
               label: function(tooltipItem) {
                      return tooltipItem.yLabel;
               }
            }
        }
    }
});

// Menangani klik pada tombol "Simpan Utang"
@foreach($debts as $trx)
$(document).on('click', '#submitAngsurBtn{{ $trx->id }}', function () {
    var bayar = parseInt($('#jumlah_bayar{{ $trx->id }}').val());
    var total = parseInt({{ $trx->sisa }});
        if (bayar <= total) {
          $('#angsuran_form_{{$trx->id}}').submit();
        } else if (bayar > total) {
          // Jika jumlah bayar lebih besar dari total utang, tampilkan pesan error
          $('.nominal-utang-error').prop('hidden', false);
          $('.nominal-utang-error2').prop('hidden', true);
        } else {
            $('.nominal-utang-error').prop('hidden', true);
            $('.nominal-utang-error2').prop('hidden', false);
        }
      
    
});
@endforeach

@if ($message = Session::get('success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif


  function confirmDelete(event) {
        event.preventDefault(); // Menghentikan form dari pengiriman langsung

        swal({
      title: "Apa Anda Yakin?",
      text: "Data Utang akan terhapus, klik oke untuk melanjutkan",
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
@endsection