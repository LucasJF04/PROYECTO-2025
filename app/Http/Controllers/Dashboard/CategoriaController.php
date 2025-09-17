<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        return view('categorias.index', [
            'categorias' => Categoria::filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nombre' => 'required|unique:categorias,name',
            'alias' => 'required|unique:categorias,slug|alpha_dash',
        ];

        $validatedData = $request->validate($rules);

        Categoria::create($validatedData);

        return Redirect::route('categorias.index')->with('success', 'Categoria has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', [
            'categoria' => $categoria
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $rules = [
            'nombre' => 'required|unique:categorias,nombre,'.$categoria->id,
            'alias' => 'required|alpha_dash|unique:categorias,alias,'.$categoria->id,
        ];

        $validatedData = $request->validate($rules);

        Categoria::where('alias', $categoria->alias)->update($validatedData);

        return Redirect::route('categorias.index')->with('success', 'Categoria has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        Categoria::destroy($categoria->alias);

        return Redirect::route('categorias.index')->with('success', 'Categoria has been deleted!');
    }
}
