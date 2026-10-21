<?php

namespace App\Models;

use App\Models\Scopes\CongregationScope;
use App\Observers\DeleteObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(DeleteObserver::class)]
class Speaker extends Model
{
    use HasFactory, SoftDeletes;

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

    public function lastSpeechMade()
    {
        return $this->hasOne(SendSpeakers::class)->orderBy('date', 'desc');
    }

    public function speeches()
    {
        return $this->belongsToMany(Speech::class)->orderBy('number');
    }

    public function getCreatedAtAttribute($date): ?string
    {
        return $date ? Carbon::parse($date, 'UTC')->setTimezone(config('app.timezone'))->format('d/m/Y') : null;
    }

    public function getUpdatedAtAttribute($date): ?string
    {
        return $date ? Carbon::parse($date, 'UTC')->setTimezone(config('app.timezone'))->format('d/m/Y H:i:s') : null;
    }
}
