<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Cliente extends Model
{
    protected $table = 'clientes';

    use HasFactory, Sortable;

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'nombre_tienda',
        'foto',
        'titular_cuenta',
        'numero_cuenta',
        'nombre_banco',
        'sucursal_banco',
        'ciudad',
    ];

    public $sortable = [
        'nombre',
        'correo',
        'telefono',
        'nombre_tienda',
        'ciudad',
    ];

    protected $guarded = [
        'id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nombre', 'like', '%' . $search . '%')
                         ->orWhere('nombre_tienda', 'like', '%' . $search . '%');
        });
    }
}
