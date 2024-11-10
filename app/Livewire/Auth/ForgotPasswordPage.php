<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mockery\Generator\StringManipulation\Pass\Pass;

#[Title('Recuperar Contraseña - Ecommerce')]
class ForgotPasswordPage extends Component
{
    public $email;
    public function save()
    {
        $this->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);
        $status = Password::sendResetLink(['email' => $this->email]);
        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('success', 'Se ha enviado un correo electrónico con un enlace para restablecer la contraseña');
            $this->email = '';
        }
        //return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
