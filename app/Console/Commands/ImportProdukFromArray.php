<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ImportProdukFromArray extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produk:import-array';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import produk dari array';

    /**
     * Execute the console command.
     */
     public function handle()
    {
        $market = \App\Models\Market::first();

     $produk = [
[
    'kode_barang' => '8991002101234',
    'image' => 'default.png',
    'nama_barang' => 'Indomie Goreng',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2000,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888000002',
    'image' => 'default.png',
    'nama_barang' => 'Mie Sedap Ayam Bawang',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2200,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997011324102',
    'image' => 'default.png',
    'nama_barang' => 'Chitato Sapi Panggang 68g',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 7000,
    'harga_jual' => 8500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8996001350019',
    'image' => 'default.png',
    'nama_barang' => 'Silver Queen Chunky Bar',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 10000,
    'harga_jual' => 12000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8991001702025',
    'image' => 'default.png',
    'nama_barang' => 'Taro Net Seaweed',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 6000,
    'harga_jual' => 7500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998866809020',
    'image' => 'default.png',
    'nama_barang' => 'Aqua Botol 600ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 2500,
    'harga_jual' => 3500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998899020011',
    'image' => 'default.png',
    'nama_barang' => 'Teh Botol Sosro 350ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 3000,
    'harga_jual' => 4000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992753111115',
    'image' => 'default.png',
    'nama_barang' => 'Floridina Jeruk Pet 360ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 3200,
    'harga_jual' => 4500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992988610016',
    'image' => 'default.png',
    'nama_barang' => 'Ultra Milk Cokelat 250ml',
    'satuan' => 'kotak',
    'stok' => 0,
    'harga_beli' => 4800,
    'harga_jual' => 6000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8996001301010',
    'image' => 'default.png',
    'nama_barang' => 'Susu Dancow Instant Box 400g',
    'satuan' => 'box',
    'stok' => 0,
    'harga_beli' => 40000,
    'harga_jual' => 48000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8886008100019',
    'image' => 'default.png',
    'nama_barang' => 'Rinso 900gr',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 15000,
    'harga_jual' => 18000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992772102222',
    'image' => 'default.png',
    'nama_barang' => 'Sabun Lifebuoy Merah 80gr',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2500,
    'harga_jual' => 3500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992722000113',
    'image' => 'default.png',
    'nama_barang' => 'Pepsodent Pasta Gigi 190gr',
    'satuan' => 'tube',
    'stok' => 0,
    'harga_beli' => 11000,
    'harga_jual' => 14000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999999000112',
    'image' => 'default.png',
    'nama_barang' => 'Sikat Gigi Formula',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 5000,
    'harga_jual' => 7000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997215211111',
    'image' => 'default.png',
    'nama_barang' => 'Bayclin Pemutih Botol 500ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 6500,
    'harga_jual' => 8000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998765432100',
    'image' => 'default.png',
    'nama_barang' => 'Minyak Goreng Bimoli 1L',
    'satuan' => 'liter',
    'stok' => 0,
    'harga_beli' => 17500,
    'harga_jual' => 20000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888001234',
    'image' => 'default.png',
    'nama_barang' => 'Beras Ramos 5kg',
    'satuan' => 'karung',
    'stok' => 0,
    'harga_beli' => 60000,
    'harga_jual' => 68000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8996001200020',
    'image' => 'default.png',
    'nama_barang' => 'Gula Pasir Gulaku 1kg',
    'satuan' => 'kg',
    'stok' => 0,
    'harga_beli' => 13000,
    'harga_jual' => 15000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997001200989',
    'image' => 'default.png',
    'nama_barang' => 'Garam Cap Kapal 500g',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 3000,
    'harga_jual' => 4000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999909090001',
    'image' => 'default.png',
    'nama_barang' => 'Telur Ayam Ras 1kg',
    'satuan' => 'kg',
    'stok' => 0,
    'harga_beli' => 25000,
    'harga_jual' => 28000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888111111',
    'image' => 'default.png',
    'nama_barang' => 'Pop Mie Ayam Bawang',
    'satuan' => 'cup',
    'stok' => 0,
    'harga_beli' => 4500,
    'harga_jual' => 6000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8991002202234',
    'image' => 'default.png',
    'nama_barang' => 'Nextar Brownies Cokelat',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2000,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998009002222',
    'image' => 'default.png',
    'nama_barang' => 'Beng-Beng',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 1500,
    'harga_jual' => 2000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8991102000001',
    'image' => 'default.png',
    'nama_barang' => 'Roma Malkist Cokelat',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 3500,
    'harga_jual' => 5000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992201101100',
    'image' => 'default.png',
    'nama_barang' => 'Qtela Singkong Balado',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 6000,
    'harga_jual' => 7500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992727123444',
    'image' => 'default.png',
    'nama_barang' => 'Pilus Garuda Pedas',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 1800,
    'harga_jual' => 2500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992703123456',
    'image' => 'default.png',
    'nama_barang' => 'Fruit Tea Apel 350ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 3200,
    'harga_jual' => 4500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992772154321',
    'image' => 'default.png',
    'nama_barang' => 'Good Day Cappuccino',
    'satuan' => 'sachet',
    'stok' => 0,
    'harga_beli' => 1800,
    'harga_jual' => 2500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888999999',
    'image' => 'default.png',
    'nama_barang' => 'Mizone Lychee Lemon',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 4000,
    'harga_jual' => 5000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992772200000',
    'image' => 'default.png',
    'nama_barang' => 'Nu Green Tea 330ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 3000,
    'harga_jual' => 4000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8992999222222',
    'image' => 'default.png',
    'nama_barang' => 'Shampoo Lifebuoy 170ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 14500,
    'harga_jual' => 18000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8991111100003',
    'image' => 'default.png',
    'nama_barang' => 'Dettol Sabun Cair 250ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 21000,
    'harga_jual' => 25000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999888777770',
    'image' => 'default.png',
    'nama_barang' => 'Tissue Paseo Travel Pack',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 4500,
    'harga_jual' => 6000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997744222211',
    'image' => 'default.png',
    'nama_barang' => 'Hand Sanitizer 100ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 8000,
    'harga_jual' => 10000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997700112233',
    'image' => 'default.png',
    'nama_barang' => 'Sarden ABC 425g',
    'satuan' => 'kaleng',
    'stok' => 0,
    'harga_beli' => 15000,
    'harga_jual' => 18000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8997710000011',
    'image' => 'default.png',
    'nama_barang' => 'Kecap ABC Manis 135ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 5000,
    'harga_jual' => 7000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999111001234',
    'image' => 'default.png',
    'nama_barang' => 'Sambal Indofood Extra Pedas',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 7500,
    'harga_jual' => 9000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998223000123',
    'image' => 'default.png',
    'nama_barang' => 'Telur Rebus Kupas 2pcs',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 5000,
    'harga_jual' => 6000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999911111111',
    'image' => 'default.png',
    'nama_barang' => 'Pulpen Standard AE7',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2000,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999922222222',
    'image' => 'default.png',
    'nama_barang' => 'Buku Tulis Sidu 38 Lbr',
    'satuan' => 'buku',
    'stok' => 0,
    'harga_beli' => 3000,
    'harga_jual' => 4000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999933333333',
    'image' => 'default.png',
    'nama_barang' => 'Roti Sobek Cokelat',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 5500,
    'harga_jual' => 7000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999944444444',
    'image' => 'default.png',
    'nama_barang' => 'Pasta Gigi Pepsodent Sachet',
    'satuan' => 'sachet',
    'stok' => 0,
    'harga_beli' => 1500,
    'harga_jual' => 2000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8999955555555',
    'image' => 'default.png',
    'nama_barang' => 'Minyak Kayu Putih Caplang 60ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 10000,
    'harga_jual' => 12000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888881',
    'image' => 'default.png',
    'nama_barang' => 'Oreo Vanilla 137g',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 7000,
    'harga_jual' => 9000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888882',
    'image' => 'default.png',
    'nama_barang' => 'Tic Tac Permen Mint',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 3500,
    'harga_jual' => 5000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888883',
    'image' => 'default.png',
    'nama_barang' => 'Astor Cokelat 150g',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 9500,
    'harga_jual' => 12000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888884',
    'image' => 'default.png',
    'nama_barang' => 'Nabati Wafer Keju',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2000,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888885',
    'image' => 'default.png',
    'nama_barang' => 'Permen Kopiko',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 1500,
    'harga_jual' => 2000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888886',
    'image' => 'default.png',
    'nama_barang' => 'Choki-Choki 3pcs',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 1800,
    'harga_jual' => 2500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888891',
    'image' => 'default.png',
    'nama_barang' => 'Le Minerale 600ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 2500,
    'harga_jual' => 3500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888892',
    'image' => 'default.png',
    'nama_barang' => 'S-tee Jasmine Tea',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 3000,
    'harga_jual' => 4000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888893',
    'image' => 'default.png',
    'nama_barang' => 'Pocari Sweat Botol 500ml',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 7500,
    'harga_jual' => 9000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888894',
    'image' => 'default.png',
    'nama_barang' => 'You C1000 Lemon',
    'satuan' => 'botol',
    'stok' => 0,
    'harga_beli' => 6500,
    'harga_jual' => 8000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888895',
    'image' => 'default.png',
    'nama_barang' => 'Sabun Mandi Lux 80gr',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2500,
    'harga_jual' => 3500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888896',
    'image' => 'default.png',
    'nama_barang' => 'Sampo Clear Sachet',
    'satuan' => 'sachet',
    'stok' => 0,
    'harga_beli' => 1500,
    'harga_jual' => 2000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888897',
    'image' => 'default.png',
    'nama_barang' => 'Sabun Cuci Muka Ponds',
    'satuan' => 'tube',
    'stok' => 0,
    'harga_beli' => 11000,
    'harga_jual' => 14000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888898',
    'image' => 'default.png',
    'nama_barang' => 'Tissue Nice Box',
    'satuan' => 'box',
    'stok' => 0,
    'harga_beli' => 9500,
    'harga_jual' => 12000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888899',
    'image' => 'default.png',
    'nama_barang' => 'Kornet Pronas 198g',
    'satuan' => 'kaleng',
    'stok' => 0,
    'harga_beli' => 15000,
    'harga_jual' => 18000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888900',
    'image' => 'default.png',
    'nama_barang' => 'Susu Kental Manis Frisian Flag',
    'satuan' => 'sachet',
    'stok' => 0,
    'harga_beli' => 10000,
    'harga_jual' => 12000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888901',
    'image' => 'default.png',
    'nama_barang' => 'Bumbu Indofood Rendang',
    'satuan' => 'sachet',
    'stok' => 0,
    'harga_beli' => 2200,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888902',
    'image' => 'default.png',
    'nama_barang' => 'Mie Telur Cap 3 Ayam 200g',
    'satuan' => 'pak',
    'stok' => 0,
    'harga_beli' => 3500,
    'harga_jual' => 4500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888903',
    'image' => 'default.png',
    'nama_barang' => 'Penggaris 30cm',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 1500,
    'harga_jual' => 2500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888904',
    'image' => 'default.png',
    'nama_barang' => 'Pensil 2B Faber Castell',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 2000,
    'harga_jual' => 3000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888905',
    'image' => 'default.png',
    'nama_barang' => 'Penghapus Putih Joyko',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 1000,
    'harga_jual' => 1500,
    'market_id' => $market->id,
    'keterangan' => 'habis'
],
[
    'kode_barang' => '8998888888906',
    'image' => 'default.png',
    'nama_barang' => 'Stipo Kecil',
    'satuan' => 'pcs',
    'stok' => 0,
    'harga_beli' => 3500,
    'harga_jual' => 5000,
    'market_id' => $market->id,
    'keterangan' => 'habis'
]
];

        
        if (!$market) {
            $this->error("Market belum ada di database.");
            return 1;
        }
foreach ($produk as $data) {
    Product::updateOrCreate(
        ['kode_barang' => $data['kode_barang']],
        [
            'image' => $data['image'],
            'nama_barang' => $data['nama_barang'],
            'satuan' => $data['satuan'],
            'stok' => $data['stok'],
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'market_id' => $data['market_id'],
            'keterangan' => $data['keterangan'],
        ]
    );
}
        $this->info("Produk berhasil di-import!");
        return 0;
    }
}
