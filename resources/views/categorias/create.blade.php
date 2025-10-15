@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Crear Categoría</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-12">
                                <label for="nombre">Nombre de Categoría <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="alias">Alias <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('alias') is-invalid @enderror" 
                                       id="alias" name="alias" value="{{ old('alias') }}" required readonly>
                                @error('alias')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <!-- end: Input Data -->
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                            <a class="btn bg-danger" href="{{ route('categorias.index') }}">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Page end  -->
</div>

<script>
    // Generador de alias
    const nombre = document.querySelector("#nombre");
    const alias = document.querySelector("#alias");
    nombre.addEventListener("keyup", function() {
        let prealias = nombre.value;
        prealias = prealias.replace(/ /g,"-");
        alias.value = prealias.toLowerCase();
    });
</script>

@include('components.preview-img-form')
@endsection
