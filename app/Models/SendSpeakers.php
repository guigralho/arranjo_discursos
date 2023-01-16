<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendSpeakers extends Model
{
    use HasFactory;

    protected $fillable = [
        'speech_id',
        'date',
        'speaker_id',
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

    /**
     * @return mixed
     */
    public function speaker()
    {
        return $this->belongsTo(Speaker::class);
    }
}
