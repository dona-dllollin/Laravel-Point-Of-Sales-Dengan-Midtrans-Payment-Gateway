<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanPengeluaranExport implements FromArray, WithEvents, ShouldAutoSize
{
    protected $from;
    protected $to;
    protected $data;
    protected $market;
    protected $totalPengeluaran;
    protected array $headerRows = [];

    public function __construct($from, $to, $data, $totalPengeluaran, $market)
    {
        $this->from = $from;
        $this->to = $to;

        $this->market =$market;
        $this->data = $data;
        $this->totalPengeluaran = $totalPengeluaran;
    }

    public function array(): array
    {
        $rows = [];
        $rows[] = [$this->market->nama_toko ?? 'Nama Toko', 'LAPORAN PENGELUARAN'];
        $rows[] = [$this->market->alamat ?? 'Alamat Toko', now()->format('d M Y')];
        $rows[] = [$this->market->no_telp ?? 'No HP Toko', 'Oleh: ' . (Auth::user()->nama ?? 'Admin')];
        $rows[] = [''];
        $rows[] = ['LAPORAN PENGENGUALARAN'];
        $rows[] = [''];
        $rows[] = ['Periode Laporan: ' . Carbon::parse($this->from)->format('d M Y') . ' - ' . Carbon::parse($this->to)->format('d M Y')];
        $rows[] = [''];
    
        $currentRow = 8;

        foreach ($this->data as $tanggal => $transaksiPerTanggal) {
            $rows[] = ['Tanggal: ' . Carbon::parse($tanggal)->format('d M Y')];
            $currentRow++;
        
            $rows[] = ['Kode Barang', 'Nama Barang', 'Jumlah', 'Harga', 'Total', 'Pelaku'];
            $currentRow++; // Tambahkan baris setelah header
        
            $this->headerRows[] = $currentRow; // Sekarang baris header-nya sudah tepat
        
            foreach ($transaksiPerTanggal as $trx) {
                $rows[] = [
                    $trx->kode_barang,
                    $trx->product->nama_barang,
                    number_format($trx->jumlah, 0, ',', '.'),
                    'Rp ' . number_format($trx->harga_beli, 0, ',', '.'),
                    'Rp ' . number_format($trx->jumlah * $trx->harga_beli, 0, ',', '.'),
                    $trx->user?->nama,
                    
                ];
                $currentRow++;
            }
        
            $rows[] = [''];
            $currentRow++;
        }        
    
        $rows[] = [''];
        $currentRow++;
        $rows[] = ['Total Pengeluaran:', 'Rp ' . number_format($this->totalPengeluaran, 0, ',', '.')];
    
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
