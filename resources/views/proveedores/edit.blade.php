@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar proveedor</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                        <!-- begin: Input Image -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $proveedor->foto ? asset('storage/suppliers/'.$proveedor->foto) : asset('assets/images/user/1.png') }}" alt="profile-pic">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-group mb-4 col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="image" name="foto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="foto">Choose file</label>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Image -->
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="name">Nombre del proveedor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nombre_tienda">Nombre de la tienda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre_tienda') is-invalid @enderror" id="nombre_tienda" name="nombre_tienda" value="{{ old('nombre_tienda', $proveedor->nombre_tienda) }}" required>
                                @error('nombre_tienda')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Correo del proveedor <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $proveedor->correo) }}" required>
                                @error('correo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="telefono">Telefono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" required>
                                @error('telefono')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="titular_cuenta">Titula de la cuenta</label>
                                <input type="text" class="form-control @error('titular_cuenta') is-invalid @enderror" id="titular_cuenta" name="titular_cuenta" value="{{ old('titular_cuenta', $proveedor->titular_cuenta) }}">
                                @error('titular_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="bank_name">Banco</label>
                                <select class="form-control @error('banco') is-invalid @enderror" name="banco">
                                    <option value="">Select Year..</option>
                                    <option value="BRI" @if(old('banco', $proveedor->banco) == 'BRI')selected="selected"@endif>BRI</option>
                                    <option value="BNI" @if(old('banco', $proveedor->banco) == 'BNI')selected="selected"@endif>BNI</option>
                                    <option value="BCA" @if(old('banco', $proveedor->banco) == 'BCA')selected="selected"@endif>BCA</option>
                                    <option value="BSI" @if(old('banco', $proveedor->banco) == 'BSI')selected="selected"@endif>BSI</option>
                                    <option value="Mandiri" @if(old('banco', $proveedor->banco) == 'Mandiri')selected="selected"@endif>Mandiri</option>
                                </select>
                                @error('banco')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="numero_cuenta">Numero de cuenta</label>
                                <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" id="numero_cuenta" name="numero_cuenta" value="{{ old('numero_cuenta', $proveedor->numero_cuenta) }}">
                                @error('numero_cuenta')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sucursal">Sucursal de banco</label>
                                <input type="text" class="form-control @error('sucursal') is-invalid @enderror" id="sucursal" name="sucursal" value="{{ old('sucursal', $proveedor->sucursal) }}">
                                @error('sucursal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="ciudad"> ciudad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $proveedor->ciudad) }}" required>
                                @error('ciudad')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" required>
                                    <option value="">Seleccionar tipo..</option>
                                    <option value="Distributor" {{ old('tipo', $proveedor->tipo) == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                                    <option value="Whole Seller" {{ old('tipo', $proveedor->tipo) == 'Whole Seller' ? 'selected' : '' }}>Whole Seller</option>

                                </select>
                                @error('tipo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="direccion">Direccion <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" name="direccion" required>{{ old('direccion', $proveedor->direccion) }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
                            <a class="btn bg-danger" href="{{ route('proveedores.index') }}">Cancelar</a>
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
