<?php

namespace App\Http\Controllers;

use App\Exports\LaporanAngsuranUtangExport;
use App\Exports\LaporanUtangExport;
use App\Models\Debt;
use App\Models\DebtPayment;
use App\Models\Market;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DebtController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();

        // filter status utang
        $statusChart = $request->statusChart ?? 'all';
        $statusExport = $request->statusExport ?? 'all';
        $status = $request->status ?? 'all';


    
        // Filter tanggal awal dan akhir
        $dataStart = $request->tgl_awal ?? Debt::min('created_at');
        $dataEnd = $request->tgl_akhir ?? Debt::max('created_at');
        $dataStartChart = $request->tgl_awal_chart ?? Debt::min('created_at');
        $dataEndChart = $request->tgl_akhir_chart ?? Debt::max('created_at');
    
    
    
        // Ambil data Utang dengan transactions dan product terkait (eager loading)  
        $debts = Debt::with(['transaction.item.product', 'payments'])
            ->whereBetween('created_at', [$dataStart, $dataEnd])
            ->when($status != 'all', function ($query) use ($status) {
                    return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

    
        // Ambil daftar tanggal unik dari transaksi
        $dates = $debts->pluck('created_at')->map(fn($dt) => $dt->toDateString())->unique()->values()->all();
    
        // Siapkan data untuk chart: group by tanggal dan jumlahkan total
        $chartData = Debt::whereBetween('created_at', [$dataStartChart, $dataEndChart])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(id) as jumlah')
            ->when($statusChart != 'all', function ($query) use ($statusChart) {
                return $query->where('status', $statusChart);
            })
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
    
        return view('utang.index', [
            'debts' => $debts,
            'dates' => $dates,
            'chartData' => $chartData,
            'dataStart' => $dataStart,
            'dataEnd' => $dataEnd,
            'dataStartChart' => $dataStartChart,
            'dataEndChart' => $dataEndChart,
            'status' => $status,
            'statusChart' => $statusChart,
            'statusExport' => $statusExport
        ]);
    }

    public function debtPayment(Request $request, $id){
        $debt = Debt::find($id);
        $debt->payments()->create([
            'debt_id' => $debt->id,
            'jumlah_bayar' => $request->jumlah_bayar,
            'dibayar_oleh' => $request->dibayar_oleh ?? $debt->nama_pengutang
        ]);

        return redirect()->back()->with('success', 'Pembayaran utang berhasil dilakukan');
    }


    
public function exportUtang(Request $req)
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

    $debts = Debt::whereBetween('created_at', [$tgl_awal, $tgl_akhir])
        ->when($statusExport != 'all', function ($query) use ($statusExport){
            return $query->where('status', $statusExport);
        })
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

    $totalAngsuran = 0;
    $totalSisa = 0;
    $totalUtang = 0;
    foreach ($debts as $debtGroup) {
        foreach ($debtGroup as $debt) {
            $totalAngsuran += $debt->total_angsuran;
            $totalSisa += $debt->sisa;
            $totalUtang += $debt->transaction->total;

        }
    }

    $market = Market::first();

    $pdf = PDF::loadView('utang.export_debt', [
        'groupedDebt' => $debts,
        'tgl_awal' => $tgl_awal,
        'tgl_akhir' => $tgl_akhir,
        'market' => $market,
        'totalAngsuran' => $totalAngsuran,
        'totalSisa' => $totalSisa,
        'totalUtang' => $totalUtang,
    ]);

    $tgl_awal_judul = Carbon::parse($tgl_awal)->format('Y-m-d');
    $tgl_akhir_judul = Carbon::parse($tgl_akhir)->format('Y-m-d');
    $status_judul =  $statusExport == 'all' ? 'Semua' : ($statusExport == 'completed' ? 'Lunas' : 'Belum_Lunas');

    if($req->input('format') == 'excel'){
        return Excel::download(new LaporanUtangExport($tgl_awal, $tgl_akhir, $statusExport, $debts, $totalAngsuran, $totalSisa, $totalUtang, $market), "laporan-utang-{$tgl_awal_judul}-sampai-{$tgl_akhir_judul}.xlsx");
    }

    return $pdf->stream("laporan_utang_{$tgl_awal_judul}_sampai_{$tgl_akhir_judul}_status_{$status_judul}.pdf", [
        'Attachment' => false
    ]);
}

// Fungsi untuk menampilkan halaman histori angsuran
public function viewAngsuran(Request $request){
    $user = auth()->user();

    // filter status utang
    $statusChart = $request->statusChart ?? 'all';
    $statusExport = $request->statusExport ?? 'all';
    $status = $request->status ?? 'all';



    // Filter tanggal awal dan akhir
    $dataStart = $request->tgl_awal ?? DebtPayment::min('created_at');
    $dataEnd = $request->tgl_akhir ?? DebtPayment::max('created_at');
    $dataStartChart = $request->tgl_awal_chart ?? DebtPayment::min('created_at');
    $dataEndChart = $request->tgl_akhir_chart ?? DebtPayment::max('created_at');



    // Ambil data Utang dengan transactions dan product terkait (eager loading)  
    $debtPayments = DebtPayment::with('debt')
    ->whereBetween('created_at', [$dataStart, $dataEnd])
    ->when($status != 'all', function ($query) use ($status) {
        return $query->whereHas('debt', function ($q) use ($status) {
            $q->where('status', $status);
        });
    })
    ->orderBy('created_at', 'desc')
    ->get();



    // Ambil daftar tanggal unik dari transaksi
    $dates = $debtPayments->pluck('created_at')->map(fn($dt) => $dt->toDateString())->unique()->values()->all();

    // Siapkan data untuk chart: group by tanggal dan jumlahkan total
    $chartData = DebtPayment::whereBetween('created_at', [$dataStartChart, $dataEndChart])
        ->selectRaw('DATE(created_at) as tanggal, sum(jumlah_bayar) as jumlah')
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    return view('utang.payment_history', [
        'debtPayments' => $debtPayments,
        'dates' => $dates,
        'chartData' => $chartData,
        'dataStart' => $dataStart,
        'dataEnd' => $dataEnd,
        'dataStartChart' => $dataStartChart,
        'dataEndChart' => $dataEndChart,
        'status' => $status,
        // 'statusChart' => $statusChart,
        'statusExport' => $statusExport
    ]);
}

// Fungsi untuk menampilkan halaman histori angsuran
public function exportAngsuran(Request $req)
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

    $debtPayments = DebtPayment::whereBetween('created_at', [$tgl_awal, $tgl_akhir])
        ->when($statusExport != 'all', function ($query) use ($statusExport) {
            return $query->whereHas('debt', function ($q) use ($statusExport) {
                $q->where('status', $statusExport);
            });
        })
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

    $totalAngsuran = 0;
    foreach ($debtPayments as $debtPaymentGroup) {
        foreach ($debtPaymentGroup as $debtPayment) {
            $totalAngsuran += $debtPayment->jumlah_bayar;
       

        }
    }

    $market = Market::first();

    $pdf = PDF::loadView('utang.export_debt_payment', [
        'groupedDebt' => $debtPayments,
        'tgl_awal' => $tgl_awal,
        'tgl_akhir' => $tgl_akhir,
        'market' => $market,
        'totalAngsuran' => $totalAngsuran,
        
    ]);

    $tgl_awal_judul = Carbon::parse($tgl_awal)->format('Y-m-d');
    $tgl_akhir_judul = Carbon::parse($tgl_akhir)->format('Y-m-d');
    $status_judul =  $statusExport == 'all' ? 'Semua' : ($statusExport == 'completed' ? 'Lunas' : 'Belum_Lunas');

    if($req->input('format') == 'excel'){
        return Excel::download(new LaporanAngsuranUtangExport($tgl_awal, $tgl_akhir, $statusExport, $debtPayments, $totalAngsuran, $market), "laporan-utang-{$tgl_awal_judul}-sampai-{$tgl_akhir_judul}.xlsx");
    }


    return $pdf->stream("laporan_angsuran_utang_{$tgl_awal_judul}_sampai_{$tgl_akhir_judul}_status_{$status_judul}.pdf", [
        'Attachment' => false
    ]);
}


public function deleteUtang($id)
{
    DB::beginTransaction();

    try {
        $debt = Debt::with('payments')->findOrFail($id);

        // Hapus angsuran
        $debt->payments()->delete();

        // Hapus utangnya
        $debt->delete();

        DB::commit();

        return redirect()->back()->with('success', 'Data utang dan angsurannya berhasil dihapus.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal menghapus utang: ' . $e->getMessage());
    }
}

}
