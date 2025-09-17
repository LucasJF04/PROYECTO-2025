<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        // Aquí irán tus consultas a ventas, productos, etc.
        return view('reportes.index');
    }
}
