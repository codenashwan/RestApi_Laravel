<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class properties extends Model
{
    use HasFactory;
    protected $hidden = [
        'created_at',
        'updated_at',
        'images'
    ];

    protected $guarded = [];
    protected $casts = [
        'address' => 'json',
        'images' => 'array',
    ];

    protected $appends = ['photos'];
    public function getPhotosAttribute()
    {
        $photos = [];
        foreach ($this->images as $image) {
            $photos[] = asset('upload/properties/' . $image);
        }
        return $photos;
    }

    public function scopeOfUser($query, $user_id)
    {
        if ($user_id) {
            return $query->where('user_id', $user_id);
        } else {
            return $query;
        }
    }
    public function scopeOfCategory($query, $category_id)
    {
        if ($category_id) {
            return $query->where('category_id', $category_id);
        } else {
            return $query;
        }
    }
    public function scopeOfCity($query, $city_id)
    {
        if ($city_id) {
            return $query->where('city_id', $city_id);
        } else {
            return $query;
        }
    }
    public function scopeOfSearch($query, $search)
    {
        if ($search) {
            return $query->where('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%');
        } else {
            return $query;
        }
    }
    public function scopeOfPrice($query, $price)
    {
        if ($price) {
            return $query->wherebetween('price', [$price[0], $price[1]]);
        } else {
            return $query;
        }
    }

    public function category()
    {
        return $this->belongsTo(categories::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function city()
    {
        return $this->belongsTo(cities::class);
    }
}
