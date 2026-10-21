<?php

namespace App\Models;

use App\Observers\DeleteObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(DeleteObserver::class)]
class Speech extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @return mixed
     */
    public function userUpdated()
    {
        return $this->belongsTo(User::class);
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function lastMade()
    {
        return $this->hasOne(ReceiveSpeakers::class)->orderBy('date', 'desc');
    }

    /**
     * @return mixed
     */
    public function userCreated()
    {
        return $this->belongsTo(User::class);
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
