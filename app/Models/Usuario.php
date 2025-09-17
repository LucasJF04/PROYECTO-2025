<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, Sortable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'usuario',
        'correo',
        'contrasena',
        'foto',
        'correo_verificado_en',
        'rol', // 👈 ahora se gestiona aquí
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    protected $casts = [
        'correo_verificado_en' => 'datetime',
    ];

    public $sortable = [
        'nombre',
        'usuario',
        'correo',
    ];

    public function getRouteKeyName()
    {
        return 'usuario';
    }

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nombre', 'like', '%' . $search . '%');
        });
    }
}
