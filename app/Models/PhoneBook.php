<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    protected $table = 'phone_book';
    protected $fillable = ['phone_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
