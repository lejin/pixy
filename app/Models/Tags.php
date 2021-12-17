<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tags extends Model
{
    use HasFactory;

    protected $fillable=['image_id','a_x','a_y','b_x','b_y','c_x','c_y','d_x','d_y','label','info'];
}
