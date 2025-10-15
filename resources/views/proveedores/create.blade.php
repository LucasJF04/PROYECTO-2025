@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Añadir Proveedor</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('proveedores.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Imagen del proveedor -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ asset('assets/images/user/1.png') }}" alt="foto-proveedor">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="foto">Seleccionar archivo</label>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombre">Nombre del Proveedor <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Datos del proveedor -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="nombre_tienda">Nombre de la Tienda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_tienda') is-invalid @enderror" id="nombre_tienda" name="nombre_tienda" value="{{ old('nombre_tienda') }}" required>
                                @error('nombre_tienda')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="tipo">Tipo de Proveedor <span class="text-danger">*</span></label>
                                <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="Distribuidor">Distribuidor</option>
                                    <option value="Mayorista">Mayorista</option>
                                </select>
                                @error('tipo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="titular_cuenta">Titular de la Cuenta</label>
                                <input type="text" class="form-control @error('titular_cuenta') is-invalid @enderror" id="titular_cuenta" name="titular_cuenta" value="{{ old('titular_cuenta') }}">
                                @error('titular_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="numero_cuenta">Número de Cuenta</label>
                                <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta') }}">
                                @error('numero_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="banco">Nombre del Banco</label>
                                <input type="text" class="form-control @error('banco') is-invalid @enderror" id="banco" name="banco" value="{{ old('banco') }}">
                                @error('banco')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sucursal">Sucursal Bancaria</label>
                                <input type="text" class="form-control @error('sucursal') is-invalid @enderror" id="sucursal" name="sucursal" value="{{ old('sucursal') }}">
                                @error('sucursal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad del Proveedor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required>
                                @error('ciudad')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="direccion">Dirección del Proveedor <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" required>{{ old('direccion') }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                            <a class="btn bg-danger" href="{{ route('proveedores.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.preview-img-form')
@endsection
