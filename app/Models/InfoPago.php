<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPago extends Model
{
    use HasFactory;

    // Nombre correcto de la tabla
    protected $table = 'informacion_pago';

    // Opcional: si tu clave primaria no es 'id'
    protected $primaryKey = 'id_info';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre_titular',
        'banco',
        'numero_cuenta',
        'tipo_cuenta',
        'qr_imagen',
    ];
}
