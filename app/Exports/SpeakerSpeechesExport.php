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
        return view('exports.speakers', [
            'speakers' => $this->speakers,
            'congregation_name' => auth()->user()->congregation_id == '' ? 'Vila Medeiros' : auth()->user()->congregation_id,
        ]);
    }
}
