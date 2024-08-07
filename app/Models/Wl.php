<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wl extends Model
{
    use HasFactory;

    protected $table = 'white_list_email';
    protected $fillable = ['email'];
}
