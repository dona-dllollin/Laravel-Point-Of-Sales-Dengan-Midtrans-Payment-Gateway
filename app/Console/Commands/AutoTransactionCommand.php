<?php

namespace App\Console\Commands;

use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:otomatis {jumlah=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat transksi otomatis dengan produk dan waktu acak';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jumlah = (int) $this->argument('jumlah');

        $user = \App\Models\User::first(); // Atau sesuaikan user_id yang kamu pakai
        if (!$user) {
            $this->error('Tidak ada user ditemukan.');
            return;
        }

        for ($i = 1; $i <= $jumlah; $i++) {
            DB::beginTransaction();
            try {
                $produk_acak = Product::where('stok', '>', 0)->inRandomOrder()->take(rand(1, 5))->get();

                if ($produk_acak->isEmpty()) {
                    $this->warn("Transaksi ke-$i dilewati karena semua produk stok habis.");
                    continue;
                }

                $total_harga = 0;
                // $diskon = rand(0, 10000); // diskon acak
                $createdAt = Carbon::create(2025, 1, 1)->addDays(rand(0, now()->diffInDays('2025-01-01')));

                // $transaksi = Transaction::create([
                //     'user_id' => $user->id,
                //     'kode_transaksi' => 'T' . now()->format('YmdHis') . rand(100,999),
                //     'total_harga' => 0, // akan dihitung
                //     'total' => 0,
                //     'diskon' => 0,
                //     'bayar' => 0,
                //     'kembali' => 0,
                //     'market_id' => $user->market_id ?? 1,
                //     'status' => 'completed',
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
                $transaksi->status = 'completed';
                $transaksi->metode = 'Tunai';
                $transaksi->created_at = $createdAt;
                $transaksi->updated_at = $createdAt;
                $transaksi->save();


                 

                $grand_total = 0;
                $ada_item = false;

                foreach ($produk_acak as $produk) {
                    if ($produk->stok <= 0) continue;

                    $maxQty = $produk->stok;
                    $qty = rand(1, 5);
                    $finalQty = min($qty, $maxQty);
                    $subtotal = $produk->harga_jual * $finalQty;

                    if ($finalQty <= 0) continue;

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
                    $this->warn("Transaksi ke-$i dilewati karena semua produk kehabisan stok saat diproses.");
                    continue;
                }

                // update kembali transaksi
                // $total = max($grand_total - $diskon, 0);
                $transaksi->update([
                    'total_harga' => $grand_total,
                    'total' => $grand_total,
                    'bayar' => $grand_total,
                    'kembali' => 0,
                ]);

                DB::commit();
                $this->info("Transaksi ke-$i berhasil.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Gagal pada transaksi ke-$i: " . $e->getMessage());
            }
        }

        $this->info("Selesai membuat $jumlah transaksi otomatis.");
    }
}
