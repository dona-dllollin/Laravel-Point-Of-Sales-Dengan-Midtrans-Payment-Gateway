<?php

namespace App\Http\Controllers;


use App\Models\Categories;
use App\Models\Debt;
use App\Models\Market;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class TransactionController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false; 
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function index(Request $request)
    {
        $categories = Categories::all();
        $user = auth()->user();
        $markets = Market::all();

        $cart = session()->get('cart', []);
        $totalSubtotal = 0; // Untuk menyimpan total harga semua barang
        $totalQuantity = 0; // Untuk menyimpan total kuantitas semua barang

        foreach ($cart as $item) {
            $totalSubtotal += $item['subtotal']; // Menjumlahkan subtotal setiap item
            $totalQuantity += $item['quantity']; // Menjumlahkan quantity setiap item
        }

            $products = Product::query();

            // Filter berdasarkan kategori
            if ($request->has('category_id') && $request->category_id != 'all') {
                $products->whereHas('categories', function ($query) use ($request) {
                    $query->where('categories.id', $request->category_id);
                });
            }

            // Ambil produk berdasarkan filter
            $products = $products->get();
       


        return view('transaction/index', compact('products', 'categories', 'markets', 'totalQuantity', 'totalSubtotal'));
    }


    // Take Transaction Product
    public function transactionProduct($id)
    {

        $product = Product::where('kode_barang', '=', $id)
            ->first();

        $cart = session()->get('cart', []);

        $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
       
        if ($jumlahStok > 0) {

            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity']++;
                $cart[$product->id]['subtotal'] = $cart[$product->id]['price'] * $cart[$product->id]['quantity']; 

            } else {

               
                $cart[$product->id] = [
                    "name" => $product->nama_barang,
                    "price" => $product->harga_jual,
                    "quantity" => 1,
                    "kode_barang" => $product->kode_barang,
                    "subtotal" => $product->harga_jual
                ];
            }

            
            session()->put('cart', $cart);
       

            $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);

           
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditambahkan ke keranjang!',
                'cart' => $cart,
                'jumlahStok' => $jumlahStok
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Stok barang sudah habis'
            ]);
        }
    }

    // fungsi mengurangi item
    public function decreaseQuantity($id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);


        if (isset($cart[$id])) {
            $cart[$id]['quantity']--;
            $cart[$id]['subtotal'] = $cart[$id]['price'] * $cart[$id]['quantity'];

      
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);
        $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'jumlahStok' => $jumlahStok
        ]);
    }

    //hapus item di session
    public function removeItem($id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);


        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);
        $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'jumlahStok' => $jumlahStok
        ]);
    }

    // fungsi menambah item di session
    public function increaseQuantity($id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);

        $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
        if ($jumlahStok > 0) {
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
                $cart[$id]['subtotal'] = $cart[$id]['price'] * $cart[$id]['quantity'];
            }

            session()->put('cart', $cart);
            $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'jumlahStok' => $jumlahStok
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Stok barang sudah habis'
            ]);
        }
    }



    // Check Transaction Product
    public function transactionProductCheck($id)
    {

        $product = Product::where('kode_barang', '=', $id)->first();

        $cart = session()->get('cart', []);
        
        if ($product) {
            $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
           
            if ($jumlahStok > 0) {

                if (isset($cart[$product->id])) {
                    $cart[$product->id]['quantity']++;
                    $cart[$product->id]['subtotal'] = $cart[$product->id]['price'] * $cart[$product->id]['quantity']; // Hitung subtotal

                } else {

               
                    $cart[$product->id] = [
                        "name" => $product->nama_barang,
                        "price" => $product->harga_jual,
                        "quantity" => 1,
                        "kode_barang" => $product->kode_barang,
                        "subtotal" => $product->harga_jual
                    ];
                }

           
                session()->put('cart', $cart);
             

                $jumlahStok = $product->stok - (isset($cart[$product->id]) ? $cart[$product->id]['quantity'] : 0);
              
                return response()->json([
                    'success' => true,
                    'message' => 'Barang berhasil ditambahkan ke keranjang!',
                    'cart' => $cart,
                    'jumlahStok' => $jumlahStok
                ]);
            } else {
                return response()->json([
                    'errorBarang' => true,
                    'message' => 'Stok barang sudah habis'
                ]);
            }
        } else {
            return response()->json([
                'errorKode' => true,
                'message' => 'kode barang tidak tersedia'
            ]);
        }

    }

    // Process Transaction
    public function transactionProcess(Request $req)
    {
        DB::beginTransaction();

        try {

            $cart = session('cart', []);
            if (empty($cart)) {
                return back()->with('empty-cart', 'keranjang belanja kosong!');
            }

            $user = auth()->user();
            $user_id = $user->id;
            $market_id = 0;
            $market_id = $user->market_id ?? $req->market_id;

            // $status = $req->payment_method === 'Tunai' ? 'completed' : 'pending';
            $status = 'completed';
            $bayar = $req->payment_method === 'Tunai' ? $req->bayar : $req->total;

        if($req->action === "bayar"){
            $transaksi = Transaction::create([
                'user_id' => $user_id,
                'kode_transaksi' => $req->kode_transaksi,
                'total_harga' => $req->subtotal,
                'total' => $req->total,
                'diskon' => $req->diskon,
                'bayar' => $bayar,
                'kembali' => $bayar - $req->total,
                'market_id' => $market_id,
                'status' => $status,
                'metode' => $req->payment_method
            ]);

            foreach ($cart as $product_id => $item) {
                OrderItems::create([
                    'transaction_id' => $transaksi->id,
                    'product_id' => $product_id,
                    'total_barang' => $item['quantity'],
                    'subtotal' => $item['subtotal']
                ]);

                $product = Product::find($product_id);
                if ($product_id) {
                    $product->decrement('stok', $item['quantity']);
                }
            }

         
            session()->forget('cart');
            if ($req->payment_method === 'Tunai') {
                DB::commit();
                    Session::flash('transaction_success', $transaksi);
                    return back();
            } else {
                DB::commit();
                $snapToken = $this->createSnapToken($transaksi);
                if ($snapToken) {
                    session()->flash('transaction_success', $transaksi);
                    session()->flash('snapToken', $snapToken);

                    return back();
                } else {
                    return back()->with('transaction_error', 'Gagal mendapatkan snap token.');
                }
            }
                
        } else if($req->action === "utang"){

                $transaksi = Transaction::create([
                    'user_id' => $user_id,
                    'kode_transaksi' => $req->kode_transaksi,
                    'total_harga' => $req->subtotal,
                    'total' => $req->total,
                    'diskon' => $req->diskon,
                    'bayar' => $req->dp ?? 0,
                    'kembali' => 0,
                    'market_id' => $market_id,
                    'status' => 'pending',
                    'metode' => $req->payment_method
                ]);

                foreach ($cart as $product_id => $item) {
                    OrderItems::create([
                        'transaction_id' => $transaksi->id,
                        'product_id' => $product_id,
                        'total_barang' => $item['quantity'],
                        'subtotal' => $item['subtotal']
                    ]);

                    $product = Product::find($product_id);
                    if ($product_id) {
                        $product->decrement('stok', $item['quantity']);
                    }
                }

                $utang = Debt::create([
                    'transaction_id' => $transaksi->id,
                    'nama_pengutang' => $req->nama_pengutang,
                    'dp' => $req->dp ?? 0,
                    'sisa' => $req->total - ($req->dp ?? 0),
                    'total_hutang' => $req->total - ($req->dp ?? 0),
                    'status' => 'pending',
                ]);


            
                session()->forget('cart');

                DB::commit();
                Session::flash('transaction_success', $transaksi);
                return back();
            }
            

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('transaction_error', 'Terjadi kesalahan saat menambahkan transaksi. error' . $e->getMessage());
        }
    }

    // Fungsi untuk mencetak nota transaksi
    public function receiptTransaction($id)
    {
        $transaction = Transaction::find($id);
        $market = Market::find($transaction->market_id);
        $items = OrderItems::where('transaction_id', $id)->get();
        $diskon = $transaction->total_harga * $transaction->diskon / 100;
        $total = $transaction->total_harga - $diskon;
        $customPaper = array(0, 0, 210, 500);
        $pdf = Pdf::loadview('transaction.nota_transaksi', compact('transaction', 'market', 'items', 'diskon', 'total'))->setPaper($customPaper, 'portrait');
        return $pdf->stream("nota_transaksi.pdf");
    }

 

// Fungsi untuk mencetak nota transaksi dengan printer thermal
public function receiptTransaction2($id)
{
    $transaction = Transaction::find($id);
    $market = Market::find($transaction->market_id);
    $items = OrderItems::where('transaction_id', $id)->get();
    $diskon = $transaction->total_harga * $transaction->diskon / 100;
    $total = $transaction->total_harga - $diskon;

   

     // Coba dengan WindowsPrintConnector
     try {
            $connector = new WindowsPrintConnector("POS-58");
         
            // $connector = new FilePrintConnector(storage_path('app/tes_print.txt'));

            
    } catch (Exception $e) {
        // Jika gagal, coba dengan FilePrintConnector
        $connector = new FilePrintConnector("USB004"); // atau "USB001" tergantung port yang digunakan
    }

    $printer = new Printer($connector);

    try {
        // Header
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(2, 2);
        $printer->text($market->nama_toko . "\n");
        $printer->setTextSize(1, 1);
        $printer->text($market->alamat . "\n");
        $printer->text($market->no_telp . "\n");
        $printer->feed();

        // Info Transaksi
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Kode Transaksi: " . $transaction->kode_transaksi . "\n");
        $printer->text("Kasir: " . explode(' ', $transaction->kasir->nama)[0] . "\n");
        $printer->text("Tanggal: " . date('d M, Y', strtotime($transaction->created_at)) . "\n");
        $printer->feed();

        // Items
        $printer->text("--------------------------------\n");
        foreach ($items as $item) {
            $printer->text($item->product->nama_barang . "\n");
            $printer->text($item->total_barang . " x " . number_format($item->product->harga_jual, 2, ',', '.') . " = " . number_format($item->subtotal, 2, ',', '.') . "\n");
        }
        $printer->text("--------------------------------\n");

        // Total
        $printer->text("Subtotal: " . number_format($transaction->total_harga, 2, ',', '.') . "\n");
        $printer->text("Diskon: " . number_format($diskon, 2, ',', '.') . "\n");
        $printer->text("Total: " . number_format($total, 2, ',', '.') . "\n");
        $printer->text("Bayar: " . number_format($transaction->bayar, 2, ',', '.') . "\n");
        $printer->text("Kembali: " . number_format($transaction->kembali, 2, ',', '.') . "\n");

        // Tambahkan teks UTANG jika statusnya pending
            if ($transaction->status == "pending") {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("*** UTANG ***\n");
            }
            
        $printer->feed();

        // Footer
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Terima Kasih Telah Berkunjung\n");
        $printer->text("barang yang telah dibeli tidak boleh dikembalikan\n");
        $printer->feed(2);

        // Potong kertas
        $printer->cut();
        return back()->with('success', 'Nota berhasil dicetak.');
    } catch (Exception $e) {
        Log::error($e->getMessage());

    }finally {
        $printer->close();
    }

    
}

    // Fungsi untuk membuat Snap Token Midtrans
        public function createSnapToken(Transaction $transaksi)
    {
        $diskon = $transaksi->total_harga * $transaksi->diskon / 100;
        $total = $transaksi->total_harga - $diskon;

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->kode_transaksi,
                'gross_amount' => $total,
            ],

            'customer_details' => [
                'first_name' => auth()->user()->nama,
                'email' => auth()->user()->email
            ]

        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
