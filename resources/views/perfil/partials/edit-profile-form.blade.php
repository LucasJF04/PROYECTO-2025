<!-- begin: Edit Profile -->
<div class="card-header d-flex justify-content-between">
    <div class="iq-header-title">
        <h4 class="card-title">Editar Perfil</h4>
    </div>
</div>
<div class="card-body">
    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')

        <!-- begin: Input Imagen -->
        <div class="form-group row align-items-center">
            <div class="col-md-12">
                <div class="profile-img-edit">
                    <div class="crm-profile-img-edit">
                        <img class="crm-profile-pic rounded-circle avatar-100" id="image-preview" 
                             src="{{ $user->foto ? asset('storage/profile/'.$user->foto) : asset('assets/images/user/1.png') }}" 
                             alt="foto de perfil">
                    </div>
                </div>
            </div>
        </div>

        <div class="input-group mb-4">
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
        <!-- end: Input Imagen -->

        <!-- begin: Input Datos -->
        <div class="row align-items-center">
            <div class="form-group col-md-12">
                <label for="nombre">Nombre completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                       id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="usuario">Usuario <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('usuario') is-invalid @enderror" 
                       id="usuario" name="usuario" value="{{ old('usuario', $user->usuario) }}" required>
                @error('usuario')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="correo">Correo electrónico <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('correo') is-invalid @enderror" 
                       id="correo" name="correo" value="{{ old('correo', $user->correo) }}" required>
                @error('correo')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <!-- end: Input Datos -->

        <div class="mt-2">
            <button type="submit" class="btn btn-primary mr-2">Actualizar</button>
            <a class="btn bg-danger" href="{{ route('perfil.edit') }}">Cancelar</a>
        </div>
    </form>
</div>
<!-- end: Edit Profile -->
