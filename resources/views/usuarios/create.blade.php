@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Crear usuario</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Imagen de perfil -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ asset('assets/images/user/1.png') }}" alt="foto de perfil">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="input-group col-lg-6">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*" onchange="previewImage();">
                                    <label class="custom-file-label" for="foto">Elegir archivo</label>
                                </div>
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Datos del usuario -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-12">
                                <label for="nombre">Nombre completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required placeholder="Ingrese el nombre completo">
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="usuario">Nombre de usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario') }}" required placeholder="Ingrese el nombre de usuario">
                                @error('usuario')
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
                                <label for="contrasena">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('contrasena') is-invalid @enderror" id="contrasena" name="contrasena" required autocomplete="off" placeholder="Ingrese la contraseña">
                                @error('contrasena')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="contrasena_confirmation">Confirmar contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('contrasena_confirmation') is-invalid @enderror" id="contrasena_confirmation" name="contrasena_confirmation" required placeholder="Repita la contraseña">
                                @error('contrasena_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Selección de rol -->
                            <div class="form-group col-md-6">
                                <label for="rol">Rol</label>
                                <select class="form-control @error('rol') is-invalid @enderror" name="rol">
                                    <option selected disabled>-- Seleccione un rol --</option>
                                    @php
                                        $roles = ['cliente', 'administrador'];
                                    @endphp
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>
                                            {{ ucfirst($rol) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Fin de datos del usuario -->

                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                            <a class="btn bg-danger" href="{{ route('usuarios.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.preview-img-form')
@endsection
