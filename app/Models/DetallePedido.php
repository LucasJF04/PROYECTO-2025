<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'detalles_pedido'; // Ajusta si tu tabla tiene otro nombre

    // Llave primaria
    protected $primaryKey = 'id_detalle'; // Cambia si tu tabla tiene otra PK

    // Habilitar timestamps si tu tabla tiene created_at y updated_at
    public $timestamps = true;

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'costo_unitario',
        'total',
        'created_at',
        'updated_at'
    ];

    /**
     * Relación con el modelo Producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }

    /**
     * Relación con el modelo Pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
