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
                        <h4 class="card-title">Barcode</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class=" row align-items-center">
                        <div class="form-group col-md-6">
                            <label>Product Code</label>
                            <input type="text" class="form-control bg-white" value="{{  $product->codigo_producto }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Barcode</label>
                            {!! $barcode !!}
                        </div>
                    </div>
                    <!-- end: Show Data -->
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Information Product</h4>
                    </div>
                </div>

                <div class="card-body">
                    <!-- begin: Show Data -->
                    <div class="form-group row align-items-center">
                        <div class="col-md-12">
                            <div class="profile-img-edit">
                                <div class="crm-profile-img-edit">
                                    <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $producto->imagen_producto ? asset('storage/products/'.$producto->imagen_producto) : asset('assets/images/product/default.webp') }}" alt="profile-pic">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" row align-items-center">
                        <div class="form-group col-md-12">
                            <label>Product Name</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->nombre_producto }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Category</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->categoria->nombre }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Proveedor</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->proveedor->nombre }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Garage</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->almacen_producto }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Product Store</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->tienda_producto }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Buying Date</label>
                            <input class="form-control bg-white" id="buying_date" value="{{ $producto->fecha_compra }}" readonly/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Expire Date</label>
                            <input class="form-control bg-white" id="fecha_expiracion" value="{{ $producto->fecha_expiracion }}" readonly />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Buying Price</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->precio_compra }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Selling Price</label>
                            <input type="text" class="form-control bg-white" value="{{  $producto->precio_venta }}" readonly>
                        </div>
                    </div>
                    <!-- end: Show Data -->
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
