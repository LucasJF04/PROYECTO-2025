@extends('panel.body.main')

@section('container')
<div class="container-fluid mb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card car-transparent">
                <div class="card-body p-0">
                    <div class="profile-image position-relative">
                        <img src="{{ asset('assets/images/page-img/profile.png') }}" class="img-fluid rounded h-30 w-100" alt="imagen de portada">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row px-3">
        <!-- Lado izquierdo: información resumida -->
        <div class="col-lg-4 card-profile mb-5 h-50">
            <div class="card card-block card-stretch card-height mb-5">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="profile-img position-relative">
                            <img src="{{ $empleado->foto ? asset('storage/empleados/' . $empleado->foto) : asset('assets/images/user/1.png') }}" class="img-fluid rounded avatar-110" alt="foto de perfil">
                        </div>
                        <div class="ml-3">
                            <h4 class="mb-1">{{ $empleado->nombre }}</h4>
                            <p class="mb-2">Empleado</p>
                            <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-primary font-size-14">Editar</a>
                            <a href="{{ route('empleados.index') }}" class="btn btn-danger font-size-14">Volver</a>
                        </div>
                    </div>
                    <ul class="list-inline p-0 m-0">
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-envelope mr-3"></i>
                                <p class="mb-0">{{ $empleado->correo }}</p>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-phone mr-3"></i>
                                <p class="mb-0">{{ $empleado->telefono }}</p>
                            </div>
                        </li>
                        <li class="mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-city mr-3"></i>
                                <p class="mb-0">{{ $empleado->ciudad ?? 'Desconocida' }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Lado derecho: información detallada -->
        <div class="col-lg-8 card-profile">
            <div class="card card-block card-stretch mb-0">
                <div class="card-header px-3">
                    <div class="header-title">
                        <h4 class="card-title">Información del Empleado</h4>
                    </div>
                </div>
                <div class="card-body p-3">
                    <ul class="list-inline p-0 mb-0">
                        @php 
                            $campos = [
                                'Nombre' => $empleado->nombre,
                                'Correo' => $empleado->correo,
                                'Teléfono' => $empleado->telefono,
                                'Experiencia' => $empleado->experiencia,
                                'Salario' => '$'.$empleado->salario,
                                'Vacaciones' => $empleado->vacaciones,
                                'Ciudad' => $empleado->ciudad,
                                'Dirección' => $empleado->direccion
                            ]; 
                        @endphp

                        @foreach($campos as $label => $valor)
                            <li class="col-lg-12 mb-2">
                                <div class="form-group row">
                                    <div class="col-sm-3 col-4">
                                        <label class="col-form-label">{{ $label }}</label>
                                    </div>
                                    <div class="col-sm-9 col-8">
                                        @if($label === 'Dirección')
                                            <textarea class="form-control bg-white" readonly>{{ $valor }}</textarea>
                                        @else
                                            <input type="text" class="form-control bg-white" value="{{ $valor }}" readonly>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
