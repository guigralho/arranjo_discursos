<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScheduleExport implements FromCollection, ShouldAutoSize, WithCustomCsvSettings, WithHeadings
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
        return collect([
            [
                'Name' => $this->schedule->congregation ?? '',
                'Circuit' => '',
                'KingdomHallAddress' => $this->schedule->address ?? '',
                'WeekendMeetingDay' => $this->translateDay($this->schedule->day),
                'WeekendMeetingTime' => $this->schedule->hour ?? '',
                'COBEFirstName' => '',
                'COBELastName' => '',
                'COBEPhone' => '',
                'COBEEmail' => '',
                'PTCFirstName' => $this->schedule->contact ?? '',
                'PTCLastName' => '',
                'PTCPhone' => $this->schedule->phone ?? '',
                'PTCEmail' => '',
                'Notes' => '',
            ],
        ]);
    }

    private function translateDay(?string $day): string
    {
        $days = [
            'Domingo' => 'Sunday',
            'Sábado' => 'Saturday',
        ];

        return $days[$day] ?? '';
    }

    public function headings(): array
    {
        return [
            'Name',
            'Circuit',
            'KingdomHallAddress',
            'WeekendMeetingDay',
            'WeekendMeetingTime',
            'COBEFirstName',
            'COBELastName',
            'COBEPhone',
            'COBEEmail',
            'PTCFirstName',
            'PTCLastName',
            'PTCPhone',
            'PTCEmail',
            'Notes',
        ];
    }
}
