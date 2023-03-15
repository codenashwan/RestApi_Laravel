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

    protected $guarded=[];
    protected $casts = [
        'address' => 'json',
        'images' => 'array',
    ];

    protected $appends = ['photos'];
    public function getPhotosAttribute(){
        $photos = [];
        foreach($this->images as $image){
            $photos[] = asset('upload/properties/'.$image);
        }
        return $photos;
    }
}
