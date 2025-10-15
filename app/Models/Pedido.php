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
    const STATUS_PENDING = 'pendiente';
    const STATUS_VERIFIED = 'verificado';
    const STATUS_EN_PROCESO = 'en_proceso';
    const STATUS_LISTO = 'listo';
    const STATUS_EN_DESPACHO = 'en_despacho';
    const STATUS_ENTREGADO = 'entregado';

    protected $fillable = [
        'id_cliente',
        'fecha_pedido',
        'total_productos',
        'subtotal',
        'total',
        'estado_pedido',
        'metodo_pago',
        'pago',
        'pendiente',
        'comprobante_pago',
        'created_at',
        'updated_at',
        'tipo_entrega',
        'direccion_entrega',
    ];
    public static function allowedStates()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_VERIFIED,
            self::STATUS_EN_PROCESO,
            self::STATUS_LISTO,
            self::STATUS_EN_DESPACHO,
            self::STATUS_ENTREGADO
        ];
    }

    // Columnas que se pueden ordenar
    public $sortable = [
        'id_pedido',
        'id_cliente',
        'fecha_pedido',
        'pago',
        'pendiente',
        'total',
    ];

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'id_cliente', 'id');
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

    // Accesor para calcular pendiente automáticamente si no existe
    public function getPendienteAttribute($value)
    {
        return $value ?? $this->total;
    }


    // COLORES
    public function colorEstado()
    {
        $colores = [
            'pendiente'    => 'badge-warning',
            'verificado'   => 'badge-info',
            'en_proceso'   => 'badge-orange',
            'listo'        => 'badge-primary',
            'en_despacho'  => 'badge-purple',
            'entregado'    => 'badge-success',
        ];

        return $colores[$this->estado_pedido] ?? 'badge-secondary';
    }

}
