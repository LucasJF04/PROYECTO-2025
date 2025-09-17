@extends('panel.body.main')

@section('container')
<div class="container-fluid" style="padding-top: 105px;"> 

    <div class="row px-3">
       

        <div class="col-lg-8 card-profile">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <!-- begin: Navbar Profile -->
                    @include('perfil.partials.navbar-profile')
                    <!-- end: Navbar Profile -->

                    <!-- begin: Edit Profile -->
                    @include('perfil.partials.edit-profile-form')
                    <!-- end: Edit Profile -->
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.preview-img-form')
@endsection
