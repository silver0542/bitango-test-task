<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable; 
    protected $fillable = ['name', 'email'];

    public function countries()
    {
        return $this->hasMany(UserCountry::class);
    }

    public function phoneBook()
    {
        return $this->hasOne(PhoneBook::class);
    }
}
