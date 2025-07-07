<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualanExport;
use App\Models\Market;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function reportTransaction(Request $request)
{
    $user = auth()->user();

    $chartFilter = $request->get('filter', 'tahun');
    $statusChart = $request->statusChart ?? 'all';

    $statusExport = $request->statusExport ?? 'all';

    if ($chartFilter == 'hari') {
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();


    } elseif ($chartFilter == 'minggu') {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();


    } elseif ($chartFilter == 'bulan') {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

    } elseif ($chartFilter == 'tahun') {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now()->endOfYear();

    } else {
        $startDate = Transaction::min('created_at'); // Ambil data dari transaksi pertama
        $endDate = Transaction::max('created_at');
    }
    // Filter tanggal awal dan akhir
    $dataStart = $request->tgl_awal ?? Transaction::min('created_at');
    $dataEnd = $request->tgl_akhir ?? Transaction::max('created_at');
    $status = $request->status ?? 'all';


    // Ambil data transaksi dengan order_items dan product terkait (eager loading)
    $transactions = Transaction::with(['item.product', 'kasir'])
        ->whereBetween('created_at', [$dataStart, $dataEnd])
        ->when($status != 'all', function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->orderBy('created_at', 'desc')
        ->get();

    // Ambil daftar tanggal unik dari transaksi
    $dates = $transactions->pluck('created_at')->map(fn($dt) => $dt->toDateString())->unique()->values()->all();

    // Siapkan data untuk chart: group by tanggal dan jumlahkan total
    $chartData = Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('DATE(created_at) as tanggal, SUM(total) as total')
        ->when($statusChart != 'all', function ($query) use ($statusChart) {
            return $query->where('status', $statusChart);
        })
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    return view('report.report_transaction', [
        'transactions' => $transactions,
        'dates' => $dates,
        'chartData' => $chartData,
        'dataStart' => $dataStart,
        'dataEnd' => $dataEnd,
        'filter' => $chartFilter,
        'status' => $status,
        'statusChart' => $statusChart,
        'statusExport' => $statusExport
    ]);
}


public function exportTransaction(Request $req)
{
    $jenis_laporan = $req->jns_laporan;
    $current_time = Carbon::now()->format('Y-m-d') . ' 23:59:59';
    $statusExport = $req->statusExport;

    if ($jenis_laporan == 'period') {
        if ($req->period == 'minggu') {
            $last_time = Carbon::now()->subWeeks($req->time)->format('Y-m-d') . ' 00:00:00';
        } elseif ($req->period == 'bulan') {
            $last_time = Carbon::now()->subMonths($req->time)->format('Y-m-d') . ' 00:00:00';
        } elseif ($req->period == 'tahun') {
            $last_time = Carbon::now()->subYears($req->time)->format('Y-m-d') . ' 00:00:00';
        }
        $tgl_awal = $last_time;
        $tgl_akhir = $current_time;
    } else {
        $tgl_awal = $req->tgl_awal_export;
        $tgl_akhir = $req->tgl_akhir_export;
    }

    $transactions = Transaction::whereBetween('created_at', [$tgl_awal, $tgl_akhir])
        ->when($statusExport != 'all', function ($query) use ($statusExport){
            return $query->where('status', $statusExport);
        })
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

    $pemasukan = 0;
    foreach ($transactions as $transactionGroup) {
        foreach ($transactionGroup as $transaction) {
            $pemasukan += $transaction->total;
        }
    }

    $market = Market::first();

    $pdf = PDF::loadView('report.export_report_transaction', [
        'groupedTransactions' => $transactions,
        'tgl_awal' => $tgl_awal,
        'tgl_akhir' => $tgl_akhir,
        'market' => $market,
        'pemasukan' => $pemasukan,
    ]);

    $tgl_awal_judul = Carbon::parse($tgl_awal)->format('Y-m-d');
    $tgl_akhir_judul = Carbon::parse($tgl_akhir)->format('Y-m-d');
    $status_judul =  $statusExport == 'all' ? 'Semua' : ($statusExport == 'completed' ? 'Lunas' : 'Belum_Lunas');

    if ($req->input('format') === 'excel') {
        return Excel::download(new LaporanPenjualanExport($tgl_awal, $tgl_akhir, $statusExport, $transactions, $pemasukan, $market), "laporan-pemasukan-{$tgl_awal_judul}-sampai-{$tgl_akhir_judul}-status-{$status_judul}.xlsx");
    }

    return $pdf->stream("laporan_pemasukan_{$tgl_awal_judul}_sampai_{$tgl_akhir_judul}_status_{$status_judul}.pdf", [
        'Attachment' => false
    ]);
}


}

