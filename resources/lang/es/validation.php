<?php

return [

    'required' => 'El campo :attribute es obligatorio.',
    
    'max' => [
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    
    'unique' => 'El :attribute ya está en uso.',
    
    'confirmed' => 'La confirmación de :attribute no coincide.',
    
    'regex' => 'El campo :attribute no cumple con el formato requerido.',
    
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guion bajo.',

    'attributes' => [
        'nombre' => 'nombre',
        'usuario' => 'nombre de usuario',
        'correo' => 'correo electrónico',
        'contrasena' => 'contraseña',
        'contrasena_confirmation' => 'confirmación de contraseña',
    ],

    // Mensajes personalizado
    'custom' => [
        'nombre' => [
            'regex' => 'El nombre no puede contener números ni caracteres especiales.',
            'max' => 'El nombre no puede superar los :max caracteres.',
        ],
        'usuario' => [
            'alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones (-) y guion bajo (_).',
            'unique' => 'Este nombre de usuario ya está en uso.',
            'min' => 'El nombre de usuario debe tener al menos :min caracteres.',
            'max' => 'El nombre de usuario no puede superar los :max caracteres.',
        ],
        'correo' => [
            'email' => 'Debe ingresar un correo electrónico válido.',
            'unique' => 'Este correo electrónico ya está registrado.',
            'max' => 'El correo electrónico no puede superar los :max caracteres.',
        ],
        'contrasena' => [
            'regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número.',
            'min' => 'La contraseña debe tener al menos :min caracteres.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
        ],
    ],
];
