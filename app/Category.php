<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $table = 'categories';
    //Relación de uno a muchos
    public function posts(){
        return $this->hasMany ('App\Post');
    }
}
