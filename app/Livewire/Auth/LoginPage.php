<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login - Ecommerce')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public function save()
    {
        $this->validate([
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);
        if(!auth()->attempt(['email' => $this->email, 'password' => $this->password])){
            session()->flash('error', 'Email o contraseña incorrectos');
            return;
        }
        return redirect()->intended();

    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
