<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPengeluaranExport;
use App\Models\Market;
use App\Models\Product;
use App\Models\Supply;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class SupplyController extends Controller
{
        // Show View Supply
        public function index()
        {
        
            $suppliesByDate = Supply::orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($supply) => $supply->created_at->toDateString());
    
                return view('product.supply', compact('suppliesByDate'));
        
        }

          // Show View New Supply
        public function viewNewSupply()
        {
           
                $products = Product::all()
                ->sortBy('kode_barang');

                return view('product.new_supply', compact('products'));
            
        }

        // Take Supply Product
        public function takeSupplyProduct($id) {
                $product = Product::where('kode_barang', '=', $id)->first();
                if($product) {
                    return response()->json(['product' => $product], 200);
                } else {
                    return response()->json(['message' => 'Data not found'], 404);
                }
                
        }

        // check supply product
        public function checkSupplyProduct($id) {
            $check_product = Product::where('kode_barang', '=', $id)->count();
            $product = Product::where('kode_barang', '=', $id)->first();
            if($check_product != 0) {
                return response()->json(['message' => 'Data found', 'product' => $product], 200);
            } else {
                return response()->json(['message' => 'Data not found'], 404);
            }
            
        }

        // Store Supply
        public function storeSupply(Request $request) {
            $supplyData = $request->input('supply');

             DB::beginTransaction();
             try {
              foreach ($supplyData as $supply) {
                // Cari produk berdasarkan kode_barang
                $product = Product::where('kode_barang', $supply['kode_barang'])->first();

                if (!$product) {
                    return response()->json(['message' => 'Produk tidak ditemukan!'], 404);
                 }

            // Simpan data supply ke tabel supplies
            Supply::create([
                'kode_barang' => $supply['kode_barang'],
                'product_id' => $product->id,
                'harga_beli' => $supply['harga_beli'] ?? null, // Simpan null jika harga kosong
                'jumlah' => $supply['jumlah'],
                'user_id' => auth()->id(),
            ]);

            // $satuan = explode(' ', $product->satuan)[1] ?? '';

          

            // Update stok dan keterangan produk
            $updateData = [
                'stok' => $product->stok + $supply['jumlah'], // Tambah stok
                'keterangan' => 'tersedia', // Set keterangan ke 'tersedia'
            ];

            // Update harga_beli hanya jika tidak kosong
            if (!empty($supply['harga_beli'])) {
                $updateData['harga_beli'] = $supply['harga_beli'];
            }

            // Update tabel products
            $product->update($updateData);
        }

        DB::commit();
        return response()->json(['message' => 'Data berhasil disimpan!']);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Terjadi kesalahan!', 'error' => $e->getMessage()], 500);
    }
}


// Export Supply
public function exportSupply(Request $req)
{
    $jenis_laporan = $req->jns_laporan;
    $current_time = Carbon::now()->format('Y-m-d') . ' 23:59:59';

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

    $supplies = Supply::whereBetween('created_at', [$tgl_awal, $tgl_akhir])
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

    $pengeluaran = 0;
    foreach ($supplies as $supplyGroup) {
        foreach ($supplyGroup as $supply) {
            $pengeluaran += $supply->jumlah * $supply->harga_beli;
        }
    }

    $market = Market::first();

    $pdf = PDF::loadView('product.export_report_supply', [
        'groupedSupplies' => $supplies,
        'tgl_awal' => $tgl_awal,
        'tgl_akhir' => $tgl_akhir,
        'market' => $market,
        'pengeluaran' => $pengeluaran,
    ]);

    $tgl_awal_judul = Carbon::parse($tgl_awal)->format('Y-m-d');
    $tgl_akhir_judul = Carbon::parse($tgl_akhir)->format('Y-m-d');

    if($req->input('format') == 'excel'){
        return Excel::download(new LaporanPengeluaranExport($tgl_awal, $tgl_akhir, $supplies, $pengeluaran, $market), "laporan-pengeluaran-{$tgl_awal_judul}-sampai-{$tgl_akhir_judul}.xlsx");
    }

    return $pdf->stream("laporan_pengeluaran_{$tgl_awal_judul}_sampai_{$tgl_akhir_judul}.pdf", [
        'Attachment' => false,
    ]);
}

    
}
