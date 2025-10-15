<?php

namespace App\Models;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    use HasFactory, Sortable;

    // Campos que se pueden llenar en asignación masiva
    protected $fillable = [
        'nombre',
        'nombre_tienda',
        'correo',
        'telefono',
        'foto',
        'tipo',
        'titular_cuenta',
        'numero_cuenta',
        'banco',
        'sucursal',
        'ciudad',
        'direccion',
    ];
    
    
    

    // Campos ordenables (para el sortable)
    public $sortable = [
        'nombre',
        'correo',
        'telefono',
        'nombre_tienda',
        'tipo',
        'ciudad',
    ];

    // Campos protegidos (no se pueden asignar masivamente)
    protected $guarded = [
        'id',
    ];

    // Filtro de búsqueda
    public function scopeFilter($query, array $filtros)
    {
        $query->when($filtros['buscar'] ?? false, function ($query, $buscar) {
            return $query
                ->where('nombre', 'like', '%' . $buscar . '%')
                ->orWhere('nombre_tienda', 'like', '%' . $buscar . '%');
        });
    }
}
