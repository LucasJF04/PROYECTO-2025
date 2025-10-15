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
                    <h4 class="mb-3">Lista de proveedores</h4>
                    <p class="mb-0">Un panel de control de proveedores le permite recopilar y visualizar fácilmente los datos <br>
                    de los proveedores para optimizar la experiencia del proveedor, garantizando su retención. </p>
                </div>
                <div>
                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary add-list"><i class="fa-solid fa-plus mr-3"></i>Añadir proveedor</a>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-danger add-list"><i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('proveedores.index') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Fila:</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="row">
                                <option value="10" @if(request('row') == '10')selected="selected"@endif>10</option>
                                <option value="25" @if(request('row') == '25')selected="selected"@endif>25</option>
                                <option value="50" @if(request('row') == '50')selected="selected"@endif>50</option>
                                <option value="100" @if(request('row') == '100')selected="selected"@endif>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-sm-3 align-self-center" for="search">Buscar:</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="text" id="search" class="form-control" name="search" placeholder="Buscar proveedor" value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
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
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Nombre tienda</th>
                            <th>Tipo</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($proveedores as $proveedor)
                        <tr>
                            <td>{{ (($proveedores->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $proveedor->photo ? asset('storage/suppliers/'.$proveedor->photo) : asset('assets/images/user/1.png') }}">
                            </td>
                            <td>{{ $proveedor->nombre }}</td>
                            <td>{{ $proveedor->correo }}</td>
                            <td>{{ $proveedor->telefono }}</td>
                            <td>{{ $proveedor->nombre_tienda }}</td>
                            <td>{{ $proveedor->tipo }}</td>
                            <td>
                            <div class="d-flex align-items-center list-action">
                                <!-- Botón Editar -->
                                <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" data-placement="top" title="Editar">
                                     Editar
                                </a>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                    <i class="ri-delete-bin-line mr-0"></i>
                                    </button>
                                </form>
                            </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $proveedores->links() }}
        </div>
    </div>
    <!-- Page end  -->
</div>

@endsection
