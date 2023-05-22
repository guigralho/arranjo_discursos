<?php

namespace App\Models;

use App\Models\Scopes\CongregationScope;
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

    protected static function booted()
    {
        static::creating(function ($speaker) {
            $speaker->congregation_id = auth()->user()->congregation_id;
        });

        static::addGlobalScope(new CongregationScope());
    }

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
