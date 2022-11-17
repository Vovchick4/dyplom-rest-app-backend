<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \TCG\Voyager\Models\User as VoyagerUser;

class Admin extends VoyagerUser
{
    use HasFactory;
}
