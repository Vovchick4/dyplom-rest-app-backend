<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;

class Order extends Model
{
    use HasFactory;
    use PivotEventTrait;

    protected $fillable = [
        'restaurant_id',
        'client_id',
        'status',
        'payment_status',
        'table',
        'name',
        'person_quantity',
        'people_for_quantity',
        'is_takeaway',
        'is_online_payment',
        'price',
        'code',
        'payment_id',
        'payment_method',
        'payment_response',
    ];

    protected $casts = [
        'client_id' => 'integer',
        'restaurant_id' => 'integer',
        'person_quantity' => 'integer',
        'people_for_quantity' => 'integer',
        'price' => 'float',
        'is_takeaway' => 'integer',
        'is_online_payment' => 'integer'
    ];

    protected $with = ['plates'];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function plates()
    {
        return $this->belongsToMany(Plate::class)->withPivot('price', 'amount', 'comment');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getIsTakeawayBrowseAttribute()
    {
        return $this->is_takeaway ? '✔' : ' ';
    }

    public function getIsOnlinePaymentBrowseAttribute()
    {
        return $this->is_online_payment ? '✔' : ' ';
    }

    public function getIsTakeawayReadAttribute()
    {
        return $this->active ? 'Yes' : 'No';
    }

    public function getIsOnlinePaymentReadAttribute()
    {
        return $this->active ? 'Yes' : 'No';
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
