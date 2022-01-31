<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['marca'];

    public function veiculos() {
        return $this->hasMany(Veiculo::class);
    }
}
