<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Supply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoSupplyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stok:tambah {jumlah=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan stok ke produk dengan stok paling sedikit';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        $jumlahSupply = (int) $this->argument('jumlah');

        $user = User::first(); // Ganti jika kamu ingin user tertentu
        if (!$user) {
            $this->error("Tidak ada user ditemukan.");
            return;
        }

        // Ambil produk dengan stok terendah (urutan dari stok terkecil)
        $produkList = Product::orderBy('stok', 'asc')->take($jumlahSupply)->get();

        if ($produkList->isEmpty()) {
            $this->warn("Tidak ada produk untuk ditambahkan stok.");
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($produkList as $produk) {
                $jumlahTambah = rand(5, 10); // Jumlah stok ditambah secara acak
                // $hargaBeliBaru = $produk->harga_beli + rand(100, 1000); // Harga beli baru acak

                 $createdAt = Carbon::createFromTimestamp(rand(
                    Carbon::create(2025, 1, 1)->timestamp,
                    // now()->timestamp
                    Carbon::create(2025, 5, 30)->timestamp
                ));
                // Simpan ke tabel supply
                // Supply::create([
                //     'kode_barang' => $produk->kode_barang,
                //     'product_id' => $produk->id,
                //     'harga_beli' => $produk->harga_beli,
                //     'jumlah' => $jumlahTambah,
                //     'user_id' => $user->id,
                //     'created_at' => $createdAt,
                //     'updated_at' => $createdAt
                // ]);

                DB::table('supplies')->insert([
                    'kode_barang' => $produk->kode_barang,
                    'product_id' => $produk->id,
                    'harga_beli' => $produk->harga_beli,
                    'jumlah' => $jumlahTambah,
                    'user_id' => $user->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Update stok dan harga_beli produk
                $produk->update([
                    'stok' => $produk->stok + $jumlahTambah,
                    'harga_beli' => $produk->harga_beli,
                    'keterangan' => 'tersedia',
            
                ]);

                $this->info("Stok untuk {$produk->nama_barang} ditambah {$jumlahTambah} pcs.");
            }

            DB::commit();
            $this->info("Stok berhasil ditambahkan untuk {$produkList->count()} produk.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal menambahkan stok: " . $e->getMessage());
        }
    }
}
