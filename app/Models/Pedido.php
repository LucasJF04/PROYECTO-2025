<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Pedido extends Model
{
    use HasFactory, Sortable;

    // Nombre de la tabla
    protected $table = 'pedidos';

    // Clave primaria
    protected $primaryKey = 'id_pedido';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_cliente',
        'fecha_pedido',
        'estado_pedido',
        'total_productos',
        'subtotal',
        'iva',
        'numero_factura',
        'total',
        'estado_pago',
        'pago',
        'deuda',
    ];

    // Columnas que se pueden ordenar
    public $sortable = [
        'id_pedido',
        'id_cliente',
        'fecha_pedido',
        'pago',
        'deuda',
        'total',
    ];

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id');
    }

    // Relación con los detalles del pedido
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    // Accesor para calcular total pagado automáticamente si no existe
    public function getPagoAttribute($value)
    {
        return $value ?? 0;
    }

    // Accesor para calcular deuda automáticamente si no existe
    public function getDeudaAttribute($value)
    {
        return $value ?? $this->total;
    }
}
