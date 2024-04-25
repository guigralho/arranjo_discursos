<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BusListSheet implements FromView, WithTitle, ShouldAutoSize, WithColumnWidths, WithStyles
{
    private $title;

    public function __construct(private $field, private $passengers)
    {
        if ($this->field == 'friday') {
            $this->title = 'Sexta';
        } else if ($this->field == 'saturday') {
            $this->title = 'Sábado';
        } else {
            $this->title = 'Domingo';
        }
    }

    public function view(): View
    {
        return view('exports.busList', [
            'passengers' => $this->passengers
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 45,
            'C' => 35,
            'D' => 5,
            'E' => 45,
            'F' => 35,
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            2 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            3 => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_NONE
                    ]
                ],
            ],
            4 => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            5 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ]
                ],
            ],
            6 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'left' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ],
                    'right' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ],
                ],
            ],
            'A7:A29' => [
                'borders' => [
                    'left' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ],
                ],
            ],
            'F7:F29' => [
                'borders' => [
                    'right' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ],
                ],
            ],
            'A29:F29' => [
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => [
                            'rgb' => '00000'
                        ]
                    ],
                ],
            ],

        ];
    }
}
