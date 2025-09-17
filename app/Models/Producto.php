<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory, Sortable;

    // Nombre de la tabla
    protected $table = 'productos';
    protected $primaryKey = 'id_producto'; // <--- clave primaria real

    // Campos que se pueden llenar
    protected $fillable = [
        'nombre_producto',
        'categoria_id',
        'proveedor_id',
        'codigo_producto',
        'almacen_producto',
        'imagen_producto',
        'tienda_producto',
        'fecha_compra',
        'fecha_expiracion',
        'precio_compra',
        'precio_venta',
        'stock',
    ];

    // Campos que se pueden ordenar
    public $sortable = [
        'nombre_producto',
        'precio_venta',
    ];

    protected $guarded = [
        'id',
    ];

    // Relaciones
    protected $with = [
        'categoria',
        'proveedor'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    // Filtro de búsqueda
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nombre_producto', 'like', '%' . $search . '%');
        });
    }
}
