<?php

namespace App\Models;

use App\Observers\DeleteObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['total', 'saldo', 'formatted_amount'];

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
        return ($this->friday + $this->saturday + $this->sunday) * config('app.valor_onibus');
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
