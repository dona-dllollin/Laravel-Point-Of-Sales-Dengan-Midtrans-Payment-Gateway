<?php

namespace App\Console\Commands;

use App\Models\Debt;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoUtangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:utang {jumlah=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Buat transaksi uatng otomatis';

    /**
     * Execute the console command.
     */
   public function handle()
    {

      $daftar_nama = [
    // Siswa kelas 7
    'Ahmad Fauzi - 7A',
    'Fatimah Zahra - 7B',
    'Muhammad Zaki - 7C',
    'Salma Nurul - 7A',
    'Ilham Ramadhan - 7B',

    // Siswa 8
    'Aulia Rahmah - 8A',
    'Reza Akbar - 8B',
    'Dina Marwah - 8C',
    'Zahra Amalia - 8A',
    'Teguh Maulana - 8C',

    // Siswa 9
    'Nur Aini - 9A',
    'Hendra Wijaya - 9B',
    'Yuniarti Sari - 9C',
    'Fitriani Lestari - 9B',
    'Nurlaila Azizah - 9C',

    // Guru / Staf
    'Pak Ali Mustofa',          // guru agama
    'Bu Rina Kartika',          // guru bahasa Indonesia
    'Pak Hasan Basri',          // staf tata usaha
    'Bu Suci Wahyuni',          // guru matematika
    'Pak Syamsul Huda'          // kepala sekolah
];



        $jumlah = (int) $this->argument('jumlah');
        $user = User::first();

        if (!$user) {
            $this->error("Tidak ada user ditemukan.");
            return;
        }

        for ($i = 1; $i <= $jumlah; $i++) {
            DB::beginTransaction();
            try {
                $produk_acak = Product::where('stok', '>', 0)->inRandomOrder()->take(rand(1, 5))->get();

                if ($produk_acak->isEmpty()) {
                    $this->warn("Transaksi ke-{$i} dilewati karena stok habis.");
                    continue;
                }

                $createdAt = Carbon::create(2025, 1, 1)->addDays(rand(0, now()->diffInDays('2025-01-01')));
                $grand_total = 0;
                $ada_item = false;

                // $transaksi = Transaction::create([
                //     'user_id' => $user->id,
                //     'kode_transaksi' => 'T' . now()->format('YmdHis') . rand(100,999),
                //     'total_harga' => 0,
                //     'total' => 0,
                //     'diskon' => 0,
                //     'bayar' => 0,
                //     'kembali' => 0,
                //     'market_id' => $user->market_id ?? 1,
                //     'status' => 'pending',
                //     'metode' => 'Tunai',
                //     'created_at' => $createdAt,
                //     'updated_at' => $createdAt
                // ]);

                // Buat transaksi
                $transaksi = new Transaction();
                $transaksi->user_id = $user->id;
                $transaksi->kode_transaksi = 'T' . now()->format('YmdHis') . rand(100, 999);
                $transaksi->total_harga = 0;
                $transaksi->total = 0;
                $transaksi->diskon = 0;
                $transaksi->bayar = 0;
                $transaksi->kembali = 0;
                $transaksi->market_id = $user->market_id ?? 1;
                $transaksi->status = 'pending';
                $transaksi->metode = 'Tunai';
                $transaksi->created_at = $createdAt;
                $transaksi->updated_at = $createdAt;
                $transaksi->save();

                foreach ($produk_acak as $produk) {
                    $maxQty = $produk->stok;
                    $qty = rand(1, 5);
                    $finalQty = min($qty, $maxQty);

                    if ($finalQty <= 0) continue;

                    $subtotal = $produk->harga_jual * $finalQty;

                    // OrderItems::create([
                    //     'transaction_id' => $transaksi->id,
                    //     'product_id' => $produk->id,
                    //     'total_barang' => $finalQty,
                    //     'subtotal' => $subtotal,
                    //     'created_at' => $createdAt,
                    //     'updated_at' => $createdAt
                    // ]);

                    $item = new OrderItems();
                    $item->transaction_id = $transaksi->id;
                    $item->product_id = $produk->id;
                    $item->total_barang = $qty;
                    $item->subtotal = $subtotal;
                    $item->created_at = $createdAt;
                    $item->updated_at = $createdAt;
                    $item->save();

                    $produk->decrement('stok', $finalQty);
                    $grand_total += $subtotal;
                    $ada_item = true;
                }

                if (!$ada_item) {
                    DB::rollBack();
                    $this->warn("Transaksi ke-{$i} dilewati karena stok tidak cukup.");
                    continue;
                }

                $dp = rand(0, $grand_total);
                $sisa = $grand_total - $dp;

                // Debt::create([
                //     'transaction_id' => $transaksi->id,
                //     'nama_pengutang' => $daftar_nama[array_rand($daftar_nama)],
                //     'dp' => $dp,
                //     'sisa' => $sisa,
                //     'total_hutang' => $grand_total,
                //     'status' => 'pending',
                //     'created_at' => $createdAt,
                //     'updated_at' => $createdAt
                // ]);

                $utang = new Debt();
                $utang->transaction_id = $transaksi->id;
                $utang->nama_pengutang = $daftar_nama[array_rand($daftar_nama)];
                $utang->dp = $dp;
                $utang->sisa = $sisa;
                $utang->status = 'pending';
                $utang->created_at = $createdAt;
                $utang->updated_at = $createdAt;
                $utang->save();

                $transaksi->update([
                    'total_harga' => $grand_total,
                    'total' => $grand_total,
                    'bayar' => $dp,
                    'kembali' => 0
                ]);

                DB::commit();
                $this->info("Transaksi utang ke-{$i} berhasil. Total: Rp{$grand_total}, DP: Rp{$dp}");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal transaksi utang ke-{$i}: " . $e->getMessage());
            }
        }

        $this->info("Selesai membuat {$jumlah} transaksi utang otomatis.");
    }
}
