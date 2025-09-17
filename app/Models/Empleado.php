<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Empleado extends Model
{
    use HasFactory, Sortable;

    // Nombre de la tabla
    protected $table = 'empleados';

    // Columnas que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'experiencia',
        'foto',
        'salario',
        'vacaciones',
        'ciudad',
    ];

    // Columnas que se pueden ordenar con sortable
    public $sortable = [
        'nombre',
        'correo',
        'telefono',
        'salario',
        'ciudad',
    ];

    // Columnas protegidas
    protected $guarded = [
        'id'
    ];

    // Filtro de búsqueda
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nombre', 'like', '%' . $search . '%');
        });
    }

    // Relación con salarios adelantados (si la mantienes)
    public function salarios_adelantados()
    {
        return $this->hasMany(SalarioAdelantado::class);
    }
}
