@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
                <div class="alert text-white bg-success" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Lista de empleados</h4>
                    <p class="mb-0">
                        Un panel de control de empleados le permite recopilar y visualizar fácilmente los datos <br>
                        de los empleados para optimizar su experiencia y garantizar su retención.
                    </p>
                </div>
                <div>
                    <a href="{{ route('empleados.create') }}" class="btn btn-primary add-list">
                        <i class="fa-solid fa-plus mr-3"></i>Añadir empleado
                    </a>
                    <a href="{{ route('empleados.index') }}" class="btn btn-danger add-list">
                        <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('empleados.index') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Filas:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="row">
                                <option value="10" @if(request('row') == '10') selected @endif>10</option>
                                <option value="25" @if(request('row') == '25') selected @endif>25</option>
                                <option value="50" @if(request('row') == '50') selected @endif>50</option>
                                <option value="100" @if(request('row') == '100') selected @endif>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-3 align-self-center" for="search">Buscar:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search" placeholder="Buscar empleado" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary">
                                        <i class="fa-solid fa-magnifying-glass font-size-20"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Salario</th>
                            <th>Ciudad</th>

                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($empleados as $empleado)
                        <tr>
                            <td>{{ (($empleados->currentPage() - 1) * $empleados->perPage()) + $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $empleado->foto ? asset('storage/empleados/' . $empleado->foto) : asset('assets/images/user/1.png') }}">
                            </td>
                            <td>{{ $empleado->nombre }}</td>
                            <td>{{ $empleado->correo }}</td>
                            <td>{{ $empleado->telefono }}</td>
                            <td>{{ $empleado->salario }}.Bs</td>
                            <td>{{ $empleado->ciudad }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="Ver" href="{{ route('empleados.show', $empleado->id) }}">
                                        <i class="ri-eye-line mr-0"></i>
                                    </a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Editar" href="{{ route('empleados.edit', $empleado->id) }}">
                                        <i class="ri-pencil-line mr-0"></i>
                                    </a>
                                    <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" style="margin-bottom: 5px">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="badge bg-warning mr-2 border-none" onclick="return confirm('¿Está seguro de eliminar este registro?')" data-toggle="tooltip" title="Eliminar">
                                            <i class="ri-delete-bin-line mr-0"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger">Datos no encontrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $empleados->links() }}
        </div>
    </div>
</div>
@endsection
