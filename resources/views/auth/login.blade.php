@extends('auth.body.main')

@section('container')
<div class="row align-items-center justify-content-center height-self-center">
    <div class="col-lg-8">
        <div class="card auth-card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center auth-content">
                    <div class="col-lg-7 align-self-center">
                        <div class="p-3">

                            <h2 class="mb-2">Iniciar Sesión</h2>
                            <p>Inicia sesión para mantenerte conectado.</p>

                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="floating-label form-group">
                                            <input class="floating-input form-control @error('usuario_o_correo') is-invalid @enderror" 
                                                   type="text" 
                                                   name="usuario_o_correo" 
                                                   placeholder=" " 
                                                   value="{{ old('usuario_o_correo') }}" 
                                                   autocomplete="off" 
                                                   required 
                                                   autofocus>
                                            <label>Email o Nombre de usuario</label>
                                        </div>
                                        @error('usuario_o_correo')
                                        <div class="mb-4" style="margin-top: -20px">
                                            <div class="text-danger small">{{ $message }}</div>
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="floating-label form-group">
                                            <input class="floating-input form-control @error('contrasena') is-invalid @enderror" 
                                                   type="password" 
                                                   name="contrasena" 
                                                   placeholder=" " 
                                                   required>
                                            <label>Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <p>¿No eres miembro aún? <a href="{{ route('register') }}" class="text-primary">Registrarse</a></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <a href="#" class="text-primary float-right">¿Olvidó su contraseña?</a>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-5 content-right">
                        <img src="{{ asset('assets/images/login/logo.png') }}" class="img-fluid image-right" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
