<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'placa', 'modelo', 'ano', 'figura', 'ownerid'];

    public function marca() {
        return $this->belongsTo(Marca::class);
    }
}
