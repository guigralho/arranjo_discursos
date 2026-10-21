<?php

namespace App\Models;

use App\Observers\DeleteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(DeleteObserver::class)]
class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['total', 'saldo', 'formatted_amount'];

    /**
     * @return mixed
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
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

    public function getTotalAttribute()
    {
        return ($this->friday + $this->saturday + $this->sunday) * Setting::get('valor_onibus', 0);
    }

    public function getSaldoAttribute()
    {
        return $this->amount - $this->total;
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, ',', '.');
    }
}
