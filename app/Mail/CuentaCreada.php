<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class CuentaCreada extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $password;

    public function __construct(Usuario $usuario, $password)
    {
        $this->usuario = $usuario;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Tu cuenta ha sido creada')
                    ->view('emails.cuenta_creada');
    }
}
