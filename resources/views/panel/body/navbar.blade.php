<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="{{ route('dashboard') }}" class="header-logo">
                    <img src="../assets/images/logo.png" class="img-fluid rounded-normal" alt="logo">
                    <h5 class="logo-title ml-3"></h5>
                </a>
            </div>

            <div class="iq-search-bar device-search"></div>

            <div class="d-flex align-items-center">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-label="Toggle navigation">
                    <i class="ri-menu-3-line"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-list align-items-center">
                        
                        <!-- Ícono de búsqueda -->
                        <li class="nav-item nav-icon search-content">
                            <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="ri-search-line"></i>
                            </a>
                            <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                                <form action="#" class="searchbox p-2">
                                    <div class="form-group mb-0 position-relative">
                                        <input type="text" class="text search-input font-size-12"
                                            placeholder="type here to search...">
                                        <a href="#" class="search-link"><i class="las la-search"></i></a>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Perfil simplificado -->
                        <li class="nav-item nav-icon dropdown caption-content">
                            <a href="#" class="dropdown-toggle" id="dropdownMenuButton4"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ auth()->user()->photo ? asset('storage/profile/'.auth()->user()->photo) : asset('assets/images/user/1.png') }}" 
                                     class="img-fluid perfil-navbar" 
                                     alt="user">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right p-3 text-center"
                                 aria-labelledby="dropdownMenuButton4"
                                 style="min-width: 180px; border-radius: 8px;">
                                
                                <img src="{{ auth()->user()->photo ? asset('storage/profile/'.auth()->user()->photo) : asset('assets/images/user/1.png') }}" 
                                     class="perfil-img mb-2" 
                                     alt="profile">
                                
                                <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                <div class="mt-2 d-flex flex-column gap-2">
                                    <a href="{{ route('perfil.index') }}" class="btn btn-sm btn-primary btn-block">Perfil</a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger btn-block">Salir</button>
                                    </form>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>






































<style>
/* Imagen del icono superior (cuadrada) */
.perfil-navbar {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
}

/* Imagen dentro del desplegable */
.perfil-img {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    object-fit: cover;
}
</style>
