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
                    <h4 class="mb-3">Lista de usuarios adminstradores</h4>
                </div>
                <div>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary add-list">
                        <i class="fa-solid fa-plus mr-3"></i>Crear usuario
                    </a>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-danger add-list">
                        <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                    </a>
                </div>
            </div>

            <!-- Botones de filtro por rol -->
          
        </div>

        <!-- Formulario de búsqueda y filas -->
        <div class="col-lg-12">
        <form action="{{ route('usuarios.index') }}" method="get">
            <input type="hidden" name="tipo" value="administrador">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="form-group row">
                    <label for="row" class="col-sm-3 align-self-center">Fila:</label>
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
                            <input type="text" id="search" class="form-control" name="search" placeholder="Buscar usuario" value="{{ request('search') }}">
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

        <!-- Tabla de usuarios -->
        <div class="col-lg-12">
            <div class="table-responsive rounded mb-3">
                <table class="table mb-0">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th>N°</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($usuarios as $item)
                        <tr>
                            <td>{{ (($usuarios->currentPage() * $usuarios->perPage()) - $usuarios->perPage()) + $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $item->foto ? asset('storage/profile/'.$item->foto) : asset('assets/images/user/1.png') }}">
                            </td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->usuario }}</td>
                            <td>{{ $item->correo }}</td>
                            <td>{{ ucfirst($item->rol ?? 'Sin rol') }}</td>
                            <td>
                                <form action="{{ route('usuarios.destroy', $item->usuario) }}" method="POST" style="margin-bottom: 5px">
                                    @method('delete')
                                    @csrf
                                    <div class="d-flex align-items-center list-action">
                                        <a class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="Editar" href="{{ route('usuarios.edit', $item->usuario) }}">
                                            Editar
                                        </a>
                                        <button type="submit" class="btn btn-warning mr-2 border-none" onclick="return confirm('¿Estás seguro que deseas eliminar este usuario?')" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                            <i class="ri-delete-bin-line mr-0"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="alert text-white bg-danger text-center mb-0" role="alert">
                                    Datos no encontrados.
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
