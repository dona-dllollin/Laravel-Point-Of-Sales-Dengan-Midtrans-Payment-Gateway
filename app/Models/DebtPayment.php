<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'debt_id',
        'jumlah_bayar',
        'dibayar_oleh',
        'sisa_angsuran'
    ];
    public $table = 'debt_payments';
  
    public function debt()
{
    return $this->belongsTo(Debt::class);
}


protected static function booted()
{
    static::created(function ($payment) {
       
        $debt = $payment->debt;

        if ($debt) {
            // Kurangi sisa dengan jumlah bayar
            $debt->sisa -= $payment->jumlah_bayar;

            // Pastikan sisa tidak negatif
            $debt->sisa = max($debt->sisa, 0);

             // Simpan sisa ke dalam payment record juga
            $payment->sisa_angsuran = $debt->sisa;
            $payment->save(); // Update payment dengan sisa terbaru

            // Jika sisa sudah 0, update status menjadi completed
            if ($debt->sisa <= 0) {
                $debt->status = 'lunas'; // atau 'lunas'
                $debt->transaction->status = 'completed';
                $debt->transaction->save();
            } else {
                $debt->transaction->status = 'pending';
                $debt->transaction->save();
            }

             // Update juga status transaksi jika ada
             if ($debt->transaction) {
                $debt->transaction->bayar += $payment->jumlah_bayar;
                $debt->transaction->save();
            }

            // Simpan perubahan data debt
            $debt->save();
        }
    });
}

}
