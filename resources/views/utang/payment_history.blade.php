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
      <h4 class="page-title">Histori Angsuran</h4>
      <div class="print-btn-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <i class="mdi mdi-export print-icon"></i>
            </div>
            <button class="btn btn-print" type="button" data-toggle="modal" data-target="#cetakModal">Export Laporan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row modal-group">
  <div class="modal fade" id="cetakModal" tabindex="-1" role="dialog" aria-labelledby="cetakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cetakModalLabel">Export Histori Angsuran</h5>
          <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{url('/debt/export/histori/utang')}}" name="export_form" method="POST" target="_blank">
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
                    
                  </div>
                  <div class="col-10 p-0 offset-col-1 input-group">
                    <input type="date" name="tgl_akhir_export" class="form-control form-control-lg date" placeholder="Tanggal akhir">
                    
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
              <h5 class="mb-0">Statistik Angsuran</h5>
              <form name="filter_form_chart" method="GET" action="{{ url('/debt/payment/history') }}">
                @csrf
                <div class="form-group row filter-group mx-3 px-1 w-100">
                 
                  <div class="col-lg-4 col-md-4 col-sm-6 col-12 input-group">
                    <input type="date" name="tgl_awal_chart" class="form-control form-control-lg date" placeholder="Tanggal awal">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-6 col-12 input-group ">
                    <input type="date" name="tgl_akhir_chart"  class="form-control form-control-lg date" placeholder="Tanggal akhir">
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 col-12">
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
            <form name="filter_form" method="GET" action="{{ url('/debt/payment/history') }}">
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
                    <th>Jumlah Bayar</th>
                    <th>sisa Angsuran</th>
                    <th>Total</th>
                    <th>Dibayar Oleh</th>
                    <th>status</th>
                 
                  </tr>
                  @foreach($debtPayments->where('created_at', '>=', $date . ' 00:00:00')->where('created_at', '<=', $date . ' 23:59:59') as $trx)
                  <tr class="debt-row">
                    <td class="td-1">
                      <span class="d-block font-weight-bold big-font">{{ $trx->debt->nama_pengutang }}</span>
                      <span class="d-block mt-2 txt-light">{{ \Carbon\Carbon::parse($trx->created_at)->locale('id')->translatedFormat('l, d F, Y'). ' pada ' . date('H:i', strtotime($trx->created_at)) }}</span>
                    </td>
                    <td class="text-success font-weight-bold">Rp. {{ number_format($trx->jumlah_bayar,2,',','.') }}</td>
                    <td class="text-danger font-weight-bold">Rp. {{ number_format($trx->sisa_angsuran,2,',','.') }}</td>
                    <td><span class="ammount-box bg-green"><i class="mdi mdi-coin"></i></span>Rp. {{ number_format($trx->debt->transaction->total,2,',','.') }}</td>
                    <td>{{ $trx->dibayar_oleh }}</td>
                    <td class="text-center">
                      @if($trx->debt->status == 'lunas')
                      <span class="btn tersedia-span">Lunas</span>
                      @elseif($trx->debt->status == 'pending')
                      <span class="btn habis-span">Belum Lunas</span>
                      @endif
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
            backgroundColor: 'RGB(44, 77, 240)',
            borderColor: 'RGB(44, 77, 240)',
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



@if ($message = Session::get('success'))
    swal(
        "Berhasil!",
        "{{ $message }}",
        "success"
    );
  @endif

</script>
@endsection