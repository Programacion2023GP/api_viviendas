<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Techinical extends Model
{
    use HasFactory;

    protected $table = 'techinical';
    protected $primaryKey = 'id';

    protected $fillable = [
        'procedureId',
        'dependeceAssignedId',
        'userId',
        'firstName',
        'paternalSurname',
        'maternalSurname',
        'street',
        'number',
        'city',
        'section',
        'postalCode',
        'municipality',
        'locality',
        'reference',
        'cellphone',
        'requestDescription',
        'solutionDescription',
        'active',
    ];

  
    // 🔗 Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
