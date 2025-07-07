<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('login');
// });

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::get('/register', [AuthController::class, 'create'])->name('registrasi');
    Route::post('/register', [AuthController::class, 'register'])->name('registrasi');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/midtrans/callback', [PaymentController::class, 'handleCallback']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //product
    Route::post('/product/create', [ProductController::class, 'createProduct']);
    Route::get('/product/edit/{id}', [ProductController::class, 'editProduct']);
    Route::post('/product/update', [ProductController::class, 'updateProduct']);


    //supply
    Route::get('/supply', [SupplyController::class, 'index']);
    Route::get('/supply/new', [SupplyController::class, 'viewNewSupply']);
    Route::get('/supply/take/{id}', [SupplyController::class, 'takeSupplyProduct']);
    Route::get('/supply/check/{id}', [SupplyController::class, 'checkSupplyProduct']);
    Route::post('/supply/store', [SupplyController::class, 'storeSupply']);
    Route::post('/supply/statistics/export', [SupplyController::class, 'exportSupply']);

    // transaction
    Route::post('/transaction/product/{id}', [TransactionController::class, 'transactionProduct']);
    Route::get('/transaction/product/check/{id}', [TransactionController::class, 'transactionProductCheck']);
    Route::post('/cart/increase/{id}', [TransactionController::class, 'increaseQuantity']);
    Route::post('/cart/decrease/{id}', [TransactionController::class, 'decreaseQuantity']);
    Route::post('/cart/remove/{id}', [TransactionController::class, 'removeItem']);
    Route::post('/transaction/process', [TransactionController::class, 'transactionProcess']);
    Route::get('/transaction/receipt/{id}', [TransactionController::class, 'receiptTransaction2']);
    Route::get('/transaction/cetak/{id}', [TransactionController::class, 'receiptTransaction']);
    Route::get('/transaction/bismillah', [TransactionController::class, 'bismillah']);

    

    //Debt
    Route::get('/debt', [DebtController::class, 'index']);
    Route::post('/debt/payment/{id}', [DebtController::class, 'debtPayment']);
    Route::post('/debt/export/utang', [DebtController::class, 'exportUtang']);
    Route::post('/debt/export/histori/utang', [DebtController::class, 'exportAngsuran']);
    Route::get('/debt/payment/history', [DebtController::class, 'viewAngsuran']);
    Route::post('/debt/delete/{id}', [DebtController::class, 'deleteUtang']);


    //profile 
    Route::get('/profile', [ProfilController::class, 'profile'])->name('profile');
    Route::post('/profile/change', [ProfilController::class, 'changeData']);
    Route::post('/profile/change/picture', [ProfilController::class, 'changePicture']); 
    Route::post('/profile/change/password', [ProfilController::class, 'changePassword']);
    
});

Route::middleware(['auth', 'role:admin', 'check.toko', 'inject.toko'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('admin.dashboard');
    Route::get('/product', [ProductController::class, 'viewProduct'])->name('admin.product');
    Route::post('/product/delete/{id}', [ProductController::class, 'deleteProduct']);
    Route::get('/product/new', [ProductController::class, 'viewNewProduct']);
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('admin.transaction');

    // report
    Route::get('/report/transaction', [ReportController::class, 'reportTransaction'])->name('report.transaction');
    Route::post('/report/transaction/export', [ReportController::class, 'exportTransaction'])->name('report.transaction.export');

    //karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index'])->name('admin.karyawan');
    Route::post('/karyawan', [KaryawanController::class, 'store']);
    Route::post('/karyawan/delete/{id}', [KaryawanController::class, 'delete']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);

    //satuan
    Route::get('/satuan', [SatuanController::class, 'index'])->name('admin.satuan');
    Route::post('/satuan', [SatuanController::class, 'create']);
    Route::post('/satuan/edit', [SatuanController::class, 'edit']);
    Route::post('/satuan/delete/{id}', [SatuanController::class, 'delete']);


    // kategori
    Route::get('/kategori', [CategoryController::class, 'index'])->name('admin.kategori');
    Route::post('/kategori', [CategoryController::class, 'create']);
    Route::post('/kategori/edit', [CategoryController::class, 'edit']);
    Route::post('/kategori/delete/{id}', [CategoryController::class, 'delete']);

    // Kelola Toko
    Route::get('/toko', [MarketController::class, 'index'])->name('admin.toko');
    Route::post('/toko', [MarketController::class, 'create']);
    Route::post('/toko/update', [MarketController::class, 'update']);
    Route::post('/toko/delete/{id}', [MarketController::class, 'delete']);

    //account
    Route::get('/account', [UserController::class, 'viewAccount'])->name('admin.account');
    Route::post('/account/update', [UserController::class, 'updateAccount']);
    Route::get('/account/add', [UserController::class, 'addAccount']);
    Route::post('/account/create', [UserController::class, 'register']);
    Route::post('/account/delete/{id}', [UserController::class, 'deleteAccount']);
});

Route::middleware(['auth', 'role:kasir', 'check.toko', 'inject.toko'])->group(function () {
    Route::get('/toko/{slug_market}', [DashboardController::class, 'viewDashboard'])->name('kasir.dashboard');
    Route::get('/toko/{slug_market}/product', [ProductController::class, 'viewProduct'])->name('kasir.product');
    Route::post('/toko/{slug_market}/product/delete/{id}', [ProductController::class, 'deleteProductKasir'])->name('product.delete');
    Route::get('/toko/{slug_market}/product/new', [ProductController::class, 'viewNewProduct'])->name('product.new');
    Route::get('/transaksi/{slug_market}', [TransactionController::class, 'index'])->name('kasir.transaction');
});
