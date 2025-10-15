@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar usuario</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('usuarios.update', $userData->usuario) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        
                        <!-- Imagen de perfil -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $userData->foto ? asset('storage/profile/'.$userData->foto) : asset('assets/images/user/1.png') }}" alt="profile-pic">
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
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Datos del usuario -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $userData->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="usuario">Nombre de usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario', $userData->usuario) }}" required>
                                @error('usuario')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="correo">Correo <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $userData->correo) }}" required>
                                @error('correo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            

                            

                            <div class="form-group col-md-6">
                                <label for="rol">Rol</label>
                                <select class="form-control @error('rol') is-invalid @enderror" name="rol" required>
                                    <option disabled {{ old('rol', $userData->rol) ? '' : 'selected' }}>-- Seleccionar rol --</option>
                                    @php
                                        $roles = ['administrador' => 'Administrador', 'socio' => 'Socio'];
                                    @endphp
                                    @foreach ($roles as $key => $label)
                                        <option value="{{ $key }}" {{ old('rol', $userData->rol) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('rol')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                        </div>

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
