@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            @if (session()->has('success'))
                <div class="alert text-white bg-success" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert text-white bg-danger" role="alert">
                    <div class="iq-alert-text">{{ session('error') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Lista de productos</h4>
                    <p class="mb-0">Un panel de control de productos le permite recopilar y visualizar fácilmente los datos del producto <br>
                        para optimizar la experiencia del usuario y garantizar la retención del producto.</p>
                </div>
                <div>
                    <a href="{{ route('productos.importView') }}" class="btn btn-success add-list">Importar</a>
                    <a href="{{ route('productos.exportData') }}" class="btn btn-warning add-list">Exportar</a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary add-list">Añadir producto</a>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('productos.index') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Filas:</label>
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
                        <div class="input-group col-sm-8">
                            <input type="text" id="search" class="form-control" name="search" placeholder="Buscar producto" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                <a href="{{ route('productos.index') }}" class="input-group-text bg-danger"><i class="fa-solid fa-trash"></i></a>
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
                            <th>Categoría</th>
                            <th>Proveedor</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($productos as $producto)
                        <tr>
                            <td>{{ (($productos->currentPage() * 10) - 10) + $loop->iteration }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $producto->imagen_producto ? asset('storage/products/'.$producto->imagen_producto) : asset('assets/images/product/sinfoto.png') }}">
                            </td>
                            <td>{{ $producto->nombre_producto }}</td>
                            <td>{{ $producto->categoria->nombre }}</td>
                            <td>{{ $producto->proveedor->nombre }}</td>
                            <td>{{ $producto->precio_venta }}</td>
                            <td>
                                @if ($producto->fecha_expiracion > Carbon\Carbon::now()->format('Y-m-d'))
                                    <span class="badge rounded-pill bg-success">Válido</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Vencido</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" style="margin-bottom: 5px">
                                    @method('delete')
                                    @csrf
                                    <div class="d-flex align-items-center list-action">
                                        <a class="btn btn-info mr-2" data-toggle="tooltip" data-placement="top" title="Ver"
                                            href="{{ route('productos.show', $producto->id) }}"><i class="ri-eye-line mr-0"></i>
                                        </a>
                                        <a class="btn btn-success mr-2" data-toggle="tooltip" data-placement="top" title="Editar"
                                            href="{{ route('productos.edit', $producto->id) }}"><i class="ri-pencil-line mr-0"></i>
                                        </a>
                                        <button type="submit" class="btn btn-warning mr-2 border-none" onclick="return confirm('¿Está seguro de que desea eliminar este producto?')" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="ri-delete-bin-line mr-0"></i></button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <div class="alert text-white bg-danger" role="alert">
                            <div class="iq-alert-text">No se encontraron productos.</div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $productos->links() }}
        </div>
    </div>
</div>
@endsection
