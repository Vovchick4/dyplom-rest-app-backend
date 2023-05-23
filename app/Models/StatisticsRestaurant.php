<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsRestaurant extends Model
{
    use HasFactory;

    protected $table = 'statistics_restaurants';
    protected $primaryKey = 'id';
    protected $fillable = [
        'review',
        'comment',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
