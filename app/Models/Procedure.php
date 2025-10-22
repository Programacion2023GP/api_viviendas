<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    //
     protected $primaryKey = 'id';
    protected $table = 'procedure';
    protected $fillable = ['active', 'name'];

}
