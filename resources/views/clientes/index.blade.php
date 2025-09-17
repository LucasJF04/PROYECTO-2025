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
                    <h4 class="mb-3">Lista de clientes</h4>
                    <p class="mb-0">
                        Un panel de clientes te permite recopilar y visualizar fácilmente los datos de los clientes <br>
                        para optimizar la experiencia del cliente y garantizar la retención de clientes.
                    </p>
                </div>
                <div>
                    <a href="{{ route('clientes.create') }}" class="btn btn-primary add-list">
                        <i class="fa-solid fa-plus mr-3"></i>Añadir cliente
                    </a>
                    <a href="{{ route('clientes.index') }}" class="btn btn-danger add-list">
                        <i class="fa-solid fa-trash mr-3"></i>Borrar búsqueda
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('clientes.index') }}" method="get">
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
                                <input type="text" id="search" class="form-control" name="search" placeholder="Buscar cliente" value="{{ request('search') }}">
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
                            <th>N°</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th>Nombre de la tienda</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @foreach ($clientes as $customer)
                        <tr>
                            <td>{{ (($clientes->currentPage() * 10) - 10) + $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $customer->foto ? asset('storage/clientes/'.$customer->foto) : asset('assets/images/user/1.png') }}">
                            </td>
                            <td>{{ $customer->nombre }}</td>
                            <td>{{ $customer->correo }}</td>
                            <td>{{ $customer->telefono }}</td>
                            <td>{{ $customer->nombre_tienda }}</td>
                            <td>
                                <div class="d-flex align-items-center list-action">
                                    <a class="badge badge-info mr-2" data-toggle="tooltip" data-placement="top" title="Ver"
                                        href="{{ route('clientes.show', $customer->id) }}">
                                        <i class="ri-eye-line mr-0"></i>
                                    </a>
                                    <a class="badge bg-success mr-2" data-toggle="tooltip" data-placement="top" title="Editar"
                                        href="{{ route('clientes.edit', $customer->id) }}">
                                        <i class="ri-pencil-line mr-0"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $customer->id) }}" method="POST" style="margin-bottom: 5px">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="badge bg-warning mr-2 border-none" onclick="return confirm('¿Estás seguro de eliminar este cliente?')" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
            {{ $clientes->links() }}
        </div>
    </div>
</div>
@endsection
