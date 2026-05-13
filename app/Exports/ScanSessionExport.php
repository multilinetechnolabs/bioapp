<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

use App\Models\ScanSession;

class ScanSessionExport implements FromView, WithColumnWidths, WithEvents
{
    use RegistersEventListeners;

    public $scan_session;

    public function __construct(int $id)
    {
        $this->scan_session = ScanSession::find($id);
    }

    public function view(): View
    {
        return view('exports.scan_sessions', [
            'scan_session' => $this->scan_session
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 24,
            'B' => 14,
            'C' => 18,
            'D' => 34,
            'E' => 38,
            'F' => 24,
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        $styles = [
            'default' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'size' => '12'
            ],
            'headers' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'font' => [
                    'bold' => true,
                    'size' => '12'
                ]
            ],
            'pairs' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ],
                'font' => [
                    'size' => '12'
                ]
            ]
        ];
        
        // Heading section
        $event->sheet->getDelegate()->getStyle('A1:A5')->applyFromArray($styles['headers']);

        // Client Information section
        $event->sheet->getDelegate()->getStyle('A7:A7')->applyFromArray($styles['headers']);
        $event->sheet->getDelegate()->getStyle('A8:A12')->applyFromArray($styles['default']);

        // Pairs List section
        $pairsStartRow = 13;
        $pairsEndRow = $event->sheet->getHighestRow();
        if ($pairsStartRow == $pairsEndRow) {
            $startRow = $pairsStartRow + 1;
            $event->sheet->append(['No records.'], 'A'.$startRow);
            $pairsEndRow++;
        }

        $event->sheet->getDelegate()->getStyle('A1:F'.$pairsEndRow)->getAlignment()->setWrapText(true);
        $event->sheet->getDelegate()->getStyle('A1:F'.$pairsEndRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A'.$pairsStartRow.':F'.$pairsStartRow)->applyFromArray($styles['headers']);
        $event->sheet->getDelegate()->getStyle('A'.$pairsStartRow.':F'.$pairsEndRow)->applyFromArray($styles['pairs']);

        foreach (range(13, $pairsEndRow) as $row) {
            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(-1);
        }

        $event->sheet->getDelegate()->getPageSetup()->setFitToWidth(1);
        $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(0);
    }
}
