<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCountry extends Model
{
    protected $table = 'users_countries';
    protected $fillable = ['country_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
