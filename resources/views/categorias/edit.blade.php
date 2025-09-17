@extends('panel.body.main')

@section('container')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Editar categoria</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('categorias.update', $categoria->alias) }}" method="POST">
                    @csrf
                    @method('put')
                        <!-- begin: Input Data -->
                        <div class=" row align-items-center">
                            <div class="form-group col-md-12">
                                <label for="nombre">Nombre Categoria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
                                @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="alias">Alias Categoria <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('alias') is-invalid @enderror" id="alias" name="alias" value="{{ old('alias', $categoria->alias) }}" required readonly>
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
    // Slug Generator
    const title = document.querySelector("#name");
    const slug = document.querySelector("#slug");
    title.addEventListener("keyup", function() {
        let preslug = title.value;
        preslug = preslug.replace(/ /g,"-");
        slug.value = preslug.toLowerCase();
    });
</script>

@include('components.preview-img-form')
@endsection
