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
            @if (session()->has('error'))
                <div class="alert text-white bg-danger" role="alert">
                    <div class="iq-alert-text">{{ session('success') }}</div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-3">Stock Product List</h4>
                    <p class="mb-0">A stock product dashboard lets you easily gather and visualize stock product data from optimizing <br>
                        the stock product experience, ensuring stock product retention. </p>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <form action="{{ route('pedidos.stock') }}" method="get">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="form-group row">
                        <label for="row" class="col-sm-3 align-self-center">Row:</label>
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
                        <label class="control-label col-sm-3 align-self-center" for="search">Search:</label>
                        <div class="input-group col-sm-8">
                            <input type="text" id="search" class="form-control" name="search" placeholder="Search product" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text bg-primary"><i class="fa-solid fa-magnifying-glass font-size-20"></i></button>
                                <a href="{{ route('pedidos.stock') }}" class="input-group-text bg-danger"><i class="fa-solid fa-trash"></i></a>
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
                            <th>@sortablelink('nombre_producto', 'nombre')</th>
                            <th>@sortablelink('category.name', 'categoria')</th>
                            <th>@sortablelink('supplier.name', 'proveedor')</th>
                            <th>@sortablelink('precio_venta', 'precio')</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        @forelse ($productos as $producto)
                        <tr>
                            <td>{{ (($productos->currentPage() * 10) - 10) + $loop->iteration  }}</td>
                            <td>
                                <img class="avatar-60 rounded" src="{{ $producto->imagen_producto ? asset('storage/products/'.$producto->imagen_producto) : asset('assets/images/product/default.webp') }}">
                            </td>
                            <td>{{ $producto->nombre_producto }}</td>
                            <td>{{ $producto->categoria->nombre }}</td>
                            <td>{{ $producto->proveedor->nombre }}</td>
                            <td>${{ $producto->precio_venta }}</td>
                            <td>
                                <span class="btn btn-warning text-white mr-2">{{ $producto->tienda_producto }}</span>
                            </td>
                        </tr>

                        @empty
                        <div class="alert text-white bg-danger" role="alert">
                            <div class="iq-alert-text">Datos no encontrados.</div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
    <!-- Page end  -->
</div>

@endsection
