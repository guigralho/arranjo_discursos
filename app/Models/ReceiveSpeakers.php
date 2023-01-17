<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveSpeakers extends Model
{
    use HasFactory;

    protected $fillable = [
        'speech_id',
        'date',
        'speaker',
    ];

    /**
     * @return mixed
     */
    public function speech()
    {
        return $this->belongsTo(Speech::class);
    }

    /**
     * @return mixed
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}