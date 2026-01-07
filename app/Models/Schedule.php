<?php

namespace App\Models;

use App\Models\Scopes\CongregationScope;
use App\Observers\DeleteObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['translated_month', 'formatted_month', 'month_year'];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new DeleteObserver);
    }

    protected static function booted()
    {
        static::creating(function ($speaker) {
            $speaker->congregation_id = auth()->user()->congregation_id;
        });

        static::addGlobalScope(new CongregationScope);
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
        return $this->hasMany(ReceiveSpeakers::class)->orderBy('date');
    }

    public function toSend()
    {
        return $this->hasMany(SendSpeakers::class)->orderBy('date');
    }

    public function getFormattedMonthAttribute()
    {
        return Carbon::parse($this->month)->format('d/m/Y');
    }

    public function getMonthYearAttribute()
    {
        return Carbon::parse($this->month)->format('m/Y');
    }

    public function getTranslatedMonthAttribute()
    {
        return strtoupper(Carbon::parse($this->month)->translatedFormat('M/Y'));
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i:s');
    }
}
