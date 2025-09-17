@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar Cliente</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <!-- begin: Imagen -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $cliente->foto ? asset('storage/clientes/'.$customer->foto) : asset('assets/images/user/1.png') }}" alt="profile-pic">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="image" name="foto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="foto">Elegir archivo</label>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Imagen -->

                        <!-- begin: Datos -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre_tienda">Nombre Tienda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_tienda') is-invalid @enderror" id="nombre_tienda" name="nombre_tienda" value="{{ old('nombre_tienda', $cliente->nombre_tienda) }}" required>
                                @error('nombre_tienda')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="correo">Email Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $cliente->correo) }}" required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="telefono">Teléfono Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="titular_cuenta">Titular Cuenta</label>
                                <input type="text" class="form-control @error('titular_cuenta') is-invalid @enderror" id="titular_cuenta" name="titular_cuenta" value="{{ old('titular_cuenta', $cliente->titular_cuenta) }}">
                                @error('titular_cuenta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="nombre_banco">Banco</label>
                                <select class="form-control @error('nombre_banco') is-invalid @enderror" name="nombre_banco">
                                    <option value="">Seleccionar...</option>
                                    <option value="BRI" @if(old('nombre_banco', $cliente->nombre_banco) == 'BRI') selected @endif>BRI</option>
                                    <option value="BNI" @if(old('nombre_banco', $cliente->nombre_banco) == 'BNI') selected @endif>BNI</option>
                                    <option value="BCA" @if(old('nombre_banco', $cliente->nombre_banco) == 'BCA') selected @endif>BCA</option>
                                    <option value="BSI" @if(old('nombre_banco', $cliente->nombre_banco) == 'BSI') selected @endif>BSI</option>
                                    <option value="Mandiri" @if(old('nombre_banco', $cliente->nombre_banco) == 'Mandiri') selected @endif>Mandiri</option>
                                </select>
                                @error('nombre_banco')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="numero_cuenta">Número de Cuenta</label>
                                <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta', $cliente->numero_cuenta) }}">
                                @error('numero_cuenta')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sucursal_banco">Sucursal Banco</label>
                                <input type="text" class="form-control @error('sucursal_banco') is-invalid @enderror" id="sucursal_banco" name="sucursal_banco" value="{{ old('sucursal_banco', $cliente->sucursal_banco) }}">
                                @error('sucursal_banco')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad Cliente <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $cliente->ciudad) }}" required>
                                @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="direccion">Dirección Cliente <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" name="direccion" required>{{ old('direccion', $cliente->direccion) }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Datos -->

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
                            <a class="btn bg-danger" href="{{ route('clientes.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

@include('components.preview-img-form')
@endsection
