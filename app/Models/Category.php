<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'image',
        'active',
        'parent_id',
        'restaurant_id',
    ];

    protected $casts = [
        'active' => 'integer',
        'restaurant_id' => 'integer'
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
    public function plates()
    {
        return $this->belongsToMany(Plate::class, 'plate_categories');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($builder)
    {
        return $builder->where('categories.active', 1);
    }
    public function scopeInactive($builder)
    {
        return $builder->where('categories.active', 0);
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getLinkAttribute()
    {
        return "/restaurant/{$this->restaurant->slug}/menu/{$this->slug}";
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
