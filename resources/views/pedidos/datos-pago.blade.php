@extends('panel.body.main')

@section('container')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5>Configuración de datos de pago</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pedidos.guardarDatosPago') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>Nombre del titular</label>
                        <input type="text" name="nombre_titular" class="form-control"
                            value="{{ old('nombre_titular', $informacion->nombre_titular ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label>Banco</label>
                        <input type="text" name="banco" class="form-control"
                            value="{{ old('banco', $informacion->banco ?? '') }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Número de cuenta</label>
                        <input type="text" name="numero_cuenta" class="form-control"
                            value="{{ old('numero_cuenta', $informacion->numero_cuenta ?? '') }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Tipo de cuenta</label>
                        <input type="text" name="tipo_cuenta" class="form-control"
                            value="{{ old('tipo_cuenta', $informacion->tipo_cuenta ?? '') }}">
                    </div>
                
                    <div class="col-md-6 mt-3">
                        <label>Imagen QR</label><br>
                        @if(!empty($informacion->qr_imagen))
                            <img src="{{ asset('storage/' . $informacion->qr_imagen) }}" alt="QR"
                                 class="img-thumbnail mb-2" width="120">
                        @endif
                        <input type="file" name="qr_imagen" class="form-control-file">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
