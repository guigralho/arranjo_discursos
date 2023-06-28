<?php

namespace App\Exports;

use App\Models\Bus;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BusListSheet implements FromQuery, WithTitle, ShouldAutoSize, WithMapping, WithStyles, WithHeadings
{
    private $title;

    public function __construct(private $field)
    {
        if ($this->field == 'friday') {
            $this->title = 'Sexta';
        } else if ($this->field == 'saturday') {
            $this->title = 'Sábado';
        } else {
            $this->title = 'Domingo';
        }
    }

    public function query()
    {
        return Bus
            ::query()
            ->with(['passenger'])
            ->where($this->field, 1);
    }

    public function headings(): array
    {
        return [
            ['Lista de arranjo de ônbius - ' . $this->title],
            [
                'Nome',
                'RG',
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->passenger->name,
            $row->passenger->doc
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getDefaultRowDimension()->setRowHeight(0.8, 'cm');

        $sheet->getStyle('A:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A1:B1');

        $sheet->getStyle('A1')->getFont()->setSize(17);
        $sheet->getRowDimension('1')->setRowHeight(1.5, 'cm');
        $sheet->getRowDimension('2')->setRowHeight(0.8, 'cm');

        $sheet->getStyle('A2:B2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('008cb4');
        $sheet->getStyle('A2:B2')->getFont()->setColor(new Color(Color::COLOR_WHITE))->setBold(true);
        $sheet->getStyle('A')->getFont()->setBold(true);

        $sheet->getStyle('A3:A' . $sheet->getHighestRow())->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6f6fb');
    }
}
