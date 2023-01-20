<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SpeakerSpeechesExport implements FromView, ShouldAutoSize
{

    public function __construct(public $speakers)
    {
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('exports.invoices', [
            'speakers' => $this->speakers
        ]);
    }
}
