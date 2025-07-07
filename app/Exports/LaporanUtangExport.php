<?php

namespace App\Exports;

use App\Models\Market;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanUtangExport implements FromArray, WithEvents, ShouldAutoSize
{
    protected $from;
    protected $to;
    protected $status;
    protected $data;
    protected $market;
    protected $totalAngsuran;
    protected $totalSisa;
    protected $totalUtang;
    protected array $headerRows = [];

    public function __construct($from, $to, $status, $data, $totalAngsuran, $totalSisa, $totalUtang, $market)
    {
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;

        $this->market =$market;
        $this->data = $data;
        $this->totalAngsuran = $totalAngsuran;
        $this->totalSisa = $totalSisa;
        $this->totalUtang = $totalUtang;
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = [$this->market->nama_toko ?? 'Nama Toko', 'LAPORAN UTANG'];
        $rows[] = [$this->market->alamat ?? 'Alamat Toko', now()->format('d M Y')];
        $rows[] = [$this->market->no_telp ?? 'No HP Toko', 'Oleh: ' . (Auth::user()->nama ?? 'Admin')];
        $rows[] = [''];
        $rows[] = ['LAPORAN UTANG'];
        $rows[] = [''];
        $rows[] = ['Periode Laporan: ' . Carbon::parse($this->from)->format('d M Y') . ' - ' . Carbon::parse($this->to)->format('d M Y')];
        $rows[] = [''];
    
        $currentRow = 8;

        foreach ($this->data as $tanggal => $transaksiPerTanggal) {
            $rows[] = ['Tanggal: ' . Carbon::parse($tanggal)->format('d M Y')];
            $currentRow++;
        
            $rows[] = ['Nama Pengutang', 'DP', 'Jumlah Angsur', 'Sisa Angsur', 'Total Utang', 'Status'];
            $currentRow++; // Tambahkan baris setelah header
        
            $this->headerRows[] = $currentRow; // Sekarang baris header-nya sudah tepat
        
            foreach ($transaksiPerTanggal as $trx) {
                $rows[] = [
                    $trx->nama_pengutang,
                    'Rp ' . number_format($trx->dp, 0, ',', '.'),
                    'Rp ' . number_format($trx->total_angsuran, 0, ',', '.'),
                    'Rp ' . number_format($trx->sisa, 0, ',', '.'),
                    'Rp ' . number_format($trx->transaction->total, 0, ',', '.'),
                    strtolower($trx->status) === 'lunas' ? 'Lunas' : 'Belum Lunas',
                ];
                $currentRow++;
            }
        
            $rows[] = [''];
            $currentRow++;
        }        
    
        $rows[] = [''];
        $currentRow++;
        $rows[] = ['Total Angsuran Dibayar:', 'Rp ' . number_format($this->totalAngsuran, 0, ',', '.')];
        $rows[] = ['Total Sisa Utang:', 'Rp ' . number_format($this->totalSisa, 0, ',', '.')];
        $rows[] = ['Total Utang Keseluruhan:', 'Rp ' . number_format($this->totalUtang, 0, ',', '.')];
    
        return $rows;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
    
                $sheet->getStyle('A1:F3')->getFont()->setBold(true);
                $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal('center');
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->getStyle('A5:F5')->getFont()->setBold(true);
                $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal('right');
                $sheet->mergeCells('A5:F5');
                $sheet->getStyle('A7:F7')->getFont()->setBold(true);
                $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal('left');
                $sheet->mergeCells('A7:F7');

                // Center semua baris data transaksi
                foreach (range(1, $sheet->getHighestRow()) as $row) {
                    // Jika bukan header atau total, center-kan isi data transaksi
                    $sheet->getStyle("A{$row}:F{$row}")->getAlignment()->setHorizontal('center');
                }
    
                // Styling tiap baris header transaksi
                foreach ($this->headerRows as $row) {
                    $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("A{$row}:F{$row}")->getFill()
                          ->setFillType('solid')
                          ->getStartColor()
                          ->setARGB('FFEFEFEF');
                    $sheet->getStyle("A{$row}:F{$row}")->getAlignment()->setHorizontal('center');
                }
    
                // Total pemasukan bold
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$lastRow}:B{$lastRow}")->getFont()->setBold(true);
                $sheet->getStyle("B{$lastRow}")->getFont()->getColor()->setRGB('007BFF');
                
                $sheet->getStyle("A" . ($lastRow - 1) . ":B" . ($lastRow - 1))->getFont()->setBold(true);
                $sheet->getStyle("B" . ($lastRow - 1))->getFont()->getColor()->setRGB('FF0000');

                $sheet->getStyle("A" . ($lastRow - 2) . ":B" . ($lastRow - 2))->getFont()->setBold(true);
                $sheet->getStyle("B" . ($lastRow - 2))->getFont()->getColor()->setRGB('28A745');
                // Border semua sel
                $sheet->getStyle("A1:F{$lastRow}")
                      ->getBorders()
                      ->getAllBorders()
                      ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    
                // Auto-size kolom
                foreach (range('A', 'F') as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
    

    

}
