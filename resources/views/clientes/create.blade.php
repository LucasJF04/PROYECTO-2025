@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Añadir Cliente</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- begin: Imagen de perfil -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ asset('assets/images/user/1.png') }}" alt="foto de perfil">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="foto">Elegir archivo</label>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Imagen de perfil -->

                        <!-- begin: Datos del cliente -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre del cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ingrese el nombre del cliente">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre_tienda">Nombre de la tienda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_tienda') is-invalid @enderror" id="nombre_tienda" name="nombre_tienda" value="{{ old('nombre_tienda') }}" required placeholder="Ingrese el nombre de la tienda">
                                @error('nombre_tienda')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="correo">Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" required placeholder="Ingrese el correo electrónico">
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="telefono">Número de contacto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" required placeholder="Ingrese el número de contacto">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="titular_cuenta">Titular de la cuenta</label>
                                <input type="text" class="form-control @error('titular_cuenta') is-invalid @enderror" id="titular_cuenta" name="titular_cuenta" value="{{ old('titular_cuenta') }}" placeholder="Nombre del titular de la cuenta">
                                @error('titular_cuenta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre_banco">Nombre del banco</label>
                                <select class="form-control @error('nombre_banco') is-invalid @enderror" name="nombre_banco" id="nombre_banco">
                                    <option value="">Seleccione banco..</option>
                                    <option value="BRI" @if(old('nombre_banco')=='BRI') selected @endif>BRI</option>
                                    <option value="BNI" @if(old('nombre_banco')=='BNI') selected @endif>BNI</option>
                                    <option value="BCA" @if(old('nombre_banco')=='BCA') selected @endif>BCA</option>
                                    <option value="BSI" @if(old('nombre_banco')=='BSI') selected @endif>BSI</option>
                                    <option value="Mandiri" @if(old('nombre_banco')=='Mandiri') selected @endif>Mandiri</option>
                                </select>
                                @error('nombre_banco')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="numero_cuenta">Número de cuenta</label>
                                <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta') }}" placeholder="Ingrese el número de cuenta">
                                @error('numero_cuenta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sucursal_banco">Sucursal del banco</label>
                                <input type="text" class="form-control @error('sucursal_banco') is-invalid @enderror" id="sucursal_banco" name="sucursal_banco" value="{{ old('sucursal_banco') }}" placeholder="Ingrese la sucursal del banco">
                                @error('sucursal_banco')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" required placeholder="Ingrese la ciudad">
                                @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="direccion">Dirección <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" name="direccion" id="direccion" required placeholder="Ingrese la dirección">{{ old('direccion') }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Datos del cliente -->

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                            <a class="btn bg-danger" href="{{ route('clientes.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin de página -->
</div>

@include('components.preview-img-form')
@endsection
