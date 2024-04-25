<?php

namespace App\Exports;

use App\Models\Bus;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BusListExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        $busFriday = Bus
            ::query()
            ->with(['passenger'])
            ->where('friday', 1)
            ->get();
        $busSaturday = Bus
            ::query()
            ->with(['passenger'])
            ->where('saturday', 1)
            ->get();
        $busSunday = Bus
            ::query()
            ->with(['passenger'])
            ->where('sunday', 1)
            ->get();

        return [
            new BusListSheet('friday', $busFriday),
            new BusListSheet('saturday', $busSaturday),
            new BusListSheet('sunday', $busSunday),
        ];
    }
}
