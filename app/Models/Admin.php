<?php

namespace App\Models;

use App\Http\Traits\child;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    use HasFactory , child;
}
