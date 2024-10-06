<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Branch extends Model
{
    protected $fillable = [
        'name','created_by',
        'branch_start_time',
        'branch_end_time',
    ];


}
