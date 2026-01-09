<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceiveSpeakersExport implements FromCollection, ShouldAutoSize, WithCustomCsvSettings, WithHeadings
{
    public function __construct(public Schedule $schedule) {}

    public function getCsvSettings(): array
    {
        return [
            'enclosure' => '',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->schedule->toReceive->map(function ($receiveSpeaker) {
            return [
                'Date' => $receiveSpeaker->date ?? '',
                'Congregation' => $this->schedule->congregation ?? '',
                'PublicSpeaker' => $receiveSpeaker->speaker ?? '',
                'OutlineNumber' => $receiveSpeaker->speech->number ?? '',
                'OutlineName' => $receiveSpeaker->speech->theme ?? '',
                'Song' => '',
                'Confirmed' => '',
                'AvailableForHospitality' => '',
                'Notes' => '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Congregation',
            'PublicSpeaker',
            'OutlineNumber',
            'OutlineName',
            'Song',
            'Confirmed',
            'AvailableForHospitality',
            'Notes',
        ];
    }
}
