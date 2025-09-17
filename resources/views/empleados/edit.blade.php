@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar Empleado</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <!-- Input Imagen -->
                        <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <div class="profile-img-edit">
                                    <div class="crm-profile-img-edit">
                                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" src="{{ $empleado->foto ? asset('storage/empleados/' . $empleado->foto) : asset('assets/images/user/1.png') }}" alt="foto-perfil">
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

                        <!-- Input Datos -->
                        <div class="row align-items-center">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="correo">Correo <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $empleado->correo) }}" required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $empleado->telefono) }}" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="experiencia">Experiencia</label>
                                <select class="form-control" name="experiencia">
                                    <option value="">Seleccionar años...</option>
                                    @for($i=0;$i<=5;$i++)
                                        <option value="{{ $i }} Año" @if(old('experiencia', $empleado->experiencia) == "$i Año") selected @endif>{{ $i }} Año</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="salario">Salario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('salario') is-invalid @enderror" id="salario" name="salario" value="{{ old('salario', $empleado->salario) }}" required>
                                @error('salario')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="vacaciones">Vacaciones</label>
                                <input type="text" class="form-control @error('vacaciones') is-invalid @enderror" id="vacaciones" name="vacaciones" value="{{ old('vacaciones', $empleado->vacaciones) }}">
                                @error('vacaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ciudad">Ciudad <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $empleado->ciudad) }}" required>
                                @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="direccion">Dirección <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('direccion') is-invalid @enderror" name="direccion" required>{{ old('direccion', $empleado->direccion) }}</textarea>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                            <a class="btn bg-danger" href="{{ route('empleados.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.preview-img-form')
@endsection
