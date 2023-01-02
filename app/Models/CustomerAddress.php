<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'country','state','street'];

    public function country()
    {
        return $this->hasOne(Country::class);
    }

}
