<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Categoria extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'nombre',
        'alias',
    ];

    protected $sortable = [
        'nombre',
        'alias',
    ];

    protected $guarded = [
        'id',
    ];

    public function scopeFilter($query, array $filters)
    {
    $query->when($filters['search'] ?? false, function ($query, $search) {
      return $query->where(function ($query) use ($search) {
        $query->where('nombre', 'like', '%' . $search . '%')
          ->orWhere('alias', 'like', '%' . $search . '%');
      });
    });
    }


    public function getRouteKeyName()
    {
        return 'alias';
    }
}
