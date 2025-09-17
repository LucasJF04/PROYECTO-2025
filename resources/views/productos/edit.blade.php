@extends('panel.body.main')

@section('specificpagestyles')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar producto</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                        <!-- begin: Input Image -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $producto->imagen_producto ? asset('storage/products/'.$producto->imagen_producto) : asset('assets/images/product/sinfoto.png') }}" alt="profile-pic">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('imagen_producto') is-invalid @enderror" id="image" name="imagen_producto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="imagen_producto">Choose file</label>
                                </div>
                                @error('imagen_producto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Image -->
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-12">
                                <label for="nombre_producto">Nombre producto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_producto') is-invalid @enderror" id="nombre_producto" name="nombre_producto" value="{{ old('nombre_producto', $producto->nombre_producto) }}" required>
                                @error('nombre_producto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="categoria_id">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" name="categoria_id" required>
                                    <option selected="" disabled>-- Seleccionar categoria --</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="proveedor_id">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-control" name="proveedor_id" required>
                                    <option selected="" disabled>-- Seleccionar proveedor --</option>
                                    @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $producto->proveedor_id) == $proveedor->id ? 'selected' : '' }}>{{ $proveedor->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="almacen_producto">Product Garage</label>
                                <input type="text" class="form-control @error('almacen_producto') is-invalid @enderror" id="almacen_producto" name="almacen_producto" value="{{ old('almacen_producto', $producto->almacen_producto) }}">
                                @error('almacen_producto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tienda_producto">Tienda Producto</label>
                                <input type="text" class="form-control @error('tienda_producto') is-invalid @enderror" id="tienda_producto" name="tienda_producto" value="{{ old('tienda_producto', $producto->tienda_producto) }}">
                                @error('tienda_producto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_compra">Fecha Compra</label>
                                <input id="fecha_compra" class="form-control @error('fecha_compra') is-invalid @enderror" name="fecha_compra" value="{{ old('fecha_compra', $producto->fecha_compra) }}" />
                                @error('fecha_compra')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_expiracion">Fecha Expiracion</label>
                                <input id="fecha_expiracion" class="form-control @error('fecha_expiracion') is-invalid @enderror" name="fecha_expiracion" value="{{ old('fecha_expiracion', $producto->fecha_expiracion) }}" />
                                @error('fecha_expiracion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="precio_compra">Precio Compra <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('precio_compra') is-invalid @enderror" id="precio_compra" name="precio_compra" value="{{ old('precio_compra', $producto->precio_compra) }}" required>
                                @error('precio_compra')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="precio_venta">Precio Venta <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('precio_venta') is-invalid @enderror" id="precio_venta" name="precio_venta" value="{{ old('precio_venta', $producto->precio_venta) }}" required>
                                @error('precio_venta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
                            <a class="btn bg-danger" href="{{ route('productos.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

<script>
    $('#buying_date').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
        // https://gijgo.com/datetimepicker/configuration/format
    });
    $('#fecha_expiracion').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
        // https://gijgo.com/datetimepicker/configuration/format
    });
</script>

@include('components.preview-img-form')
@endsection
