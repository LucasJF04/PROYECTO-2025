<ul class="d-flex nav nav-pills mb-3 text-center profile-tab">
    <li class="nav-item">
        <a href="{{ route('perfil.index') }}" class="nav-link {{ Request::is('perfil') ? 'active' : '' }}">Perfil</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('perfil.edit') }}" class="nav-link {{ Request::is('perfil/edit') ? 'active' : '' }}">Editar Perfil</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('perfil.change-password') }}" class="nav-link {{ Request::is('perfil/change-password') ? 'active' : '' }}">Cambiar Contraseña</a>
    </li>
</ul>
