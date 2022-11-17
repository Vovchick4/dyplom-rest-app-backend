<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Plate extends Model implements TranslatableContract
{
    use HasFactory;
    use SoftDeletes;
    use Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'restaurant_id',
        'image',
        'active',
        'quantity',
        'weight',
        'price'
    ];

    protected $casts = [
        'active' => 'integer',
        'restaurant_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'float'
    ];
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
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'plate_categories');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($builder)
    {
        return $builder->where('plates.active', 1)->whereNotNull('plates.category_id');
    }

    public function scopeInactive($builder)
    {
        return $builder->where('plates.active', 0);
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getLinkAttribute()
    {
        return "/restaurant/{$this->restaurant->slug}/menu/{$this->category->slug}/{$this->slug}";
    }

    public function getActiveBrowseAttribute()
    {
        return $this->active ? '✔' : ' ';
    }

    public function getActiveReadAttribute()
    {
        return $this->active ? 'Yes' : 'No';
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
