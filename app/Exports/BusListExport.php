<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BusListExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        return [
            new BusListSheet('friday'),
            new BusListSheet('saturday'),
            new BusListSheet('sunday'),
        ];
    }
}
