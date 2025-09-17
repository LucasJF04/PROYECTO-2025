@extends('auth.body.main')

@section('container')
<div class="container">
    <div class="row align-items-center justify-content-center height-self-center">
        <div class="col-lg-8">
            <div class="card auth-card">
                <div class="card-body p-0">
                    <div class="d-flex align-items-center auth-content">
                        <!-- Formulario izquierdo -->
                        <div class="col-lg-7 align-self-center">
                            <div class="p-3">

                                <h2 class="mb-2">Registrarse</h2>
                                <p>Crea tu cuenta.</p>

                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="row">

                                        <!-- Nombre completo -->
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control @error('nombre') is-invalid @enderror"
                                                       type="text"
                                                       name="nombre"
                                                       placeholder=" "
                                                       autocomplete="off"
                                                       value="{{ old('nombre') }}"
                                                       required>
                                                <label>Nombre completo</label>
                                            </div>
                                            @error('nombre')
                                            <div class="mb-4" style="margin-top: -20px">
                                                <div class="text-danger small">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Nombre de usuario -->
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control @error('usuario') is-invalid @enderror"
                                                       type="text"
                                                       name="usuario"
                                                       placeholder=" "
                                                       autocomplete="off"
                                                       value="{{ old('usuario') }}"
                                                       required>
                                                <label>Nombre de usuario</label>
                                            </div>
                                            @error('usuario')
                                            <div class="mb-4" style="margin-top: -20px">
                                                <div class="text-danger small">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Correo -->
                                        <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control @error('correo') is-invalid @enderror"
                                                       type="email"
                                                       name="correo"
                                                       placeholder=" "
                                                       autocomplete="off"
                                                       value="{{ old('correo') }}"
                                                       required>
                                                <label>Email</label>
                                            </div>
                                            @error('correo')
                                            <div class="mb-4" style="margin-top: -20px">
                                                <div class="text-danger small">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Contraseña -->
                                        <div class="col-lg-6">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control @error('contrasena') is-invalid @enderror"
                                                       type="password"
                                                       name="contrasena"
                                                       placeholder=" "
                                                       autocomplete="off"
                                                       required>
                                                <label>Contraseña</label>
                                            </div>
                                            @error('contrasena')
                                            <div class="mb-4" style="margin-top: -20px">
                                                <div class="text-danger small">{{ $message }}</div>
                                            </div>
                                            @enderror
                                        </div>

                                        <!-- Confirmar contraseña -->
                                        <div class="col-lg-6">
                                            <div class="floating-label form-group">
                                                <input class="floating-input form-control"
                                                       type="password"
                                                       name="contrasena_confirmation"
                                                       placeholder=" "
                                                       autocomplete="off"
                                                       required>
                                                <label>Confirmar contraseña</label>
                                            </div>
                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-primary">Registrar</button>

                                    <p class="mt-3">
                                        ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-primary">Iniciar sesión</a>
                                    </p>
                                </form>
                            </div>
                        </div>

                        <!-- Imagen derecha -->
                        <div class="col-lg-5 content-right">
                            <img src="{{ asset('assets/images/login/logo.png') }}" class="img-fluid image-right" alt="">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
