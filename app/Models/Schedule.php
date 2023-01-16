<?php

namespace App\Models;

use App\Observers\DeleteObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['translated_month', 'formatted_month'];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new DeleteObserver());
    }

    /**
     * @return mixed
     */
    public function userUpdated()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function userCreated()
    {
        return $this->belongsTo(User::class);
    }

    public function toReceive()
    {
        return $this->hasMany(ReceiveSpeakers::class);
    }

    public function toSend()
    {
        return $this->hasMany(SendSpeakers::class);
    }

    public function getFormattedMonthAttribute()
    {
        return Carbon::parse($this->month)->format('d/m/Y');
    }

    public function getTranslatedMonthAttribute()
    {
        return Carbon::parse($this->month)->format('M - Y');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i:s');
    }
}
