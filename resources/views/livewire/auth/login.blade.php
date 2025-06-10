<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $usuario = Auth::user();
        // 1.admin 2.colegio 3.docente 4.estudiante 5.acudiente
        // solo redirigir a su correspondiente igualmente necesitas el rol para entrar.
        switch ($usuario->role_id) {
            case 1:
                // Por defecto: redirige al dashboard general
                $this->redirectRoute('administrador-principal', navigate: true);
                break;
            case 2:
                // Por defecto: redirige al dashboard general
                $this->redirectRoute('colegio-inicio', navigate: true);
                break;
            case 3:
                // Por defecto: redirige al dashboard general
                $this->redirectRoute('docente-inicio', navigate: true);
                break;
            case 4:
                // Por defecto: redirige al dashboard general
                $this->redirectRoute('estudiante-inicio', navigate: true);
                break;
            default:
                # code...
                // Por defecto: redirige al dashboard general
        $this->redirectRoute('dashboard', navigate: true);
        }

        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>
<div class="  dark:bg-[#121212] flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white dark:bg-[#1A1A1A] rounded-xl shadow-lg p-8">
        <h1 class="text-3xl lg:text-5xl font-bold mb-4 text-center">
           <span class="text-blue-600 dark:text-yellow-400">{{ env("APP_NAME") }}</span>
        </h1>
        <!-- Título -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Inicio de Sesión</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ingresa tu nombre y contraseña</p>
        </div>

        <!-- Estado de sesión -->
        <x-auth-session-status class="text-center text-sm text-green-600 dark:text-green-400" :status="session('status')" />

        <!-- Formulario -->
        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                <input type="text" wire:model="email" required class="mt-1 block text-black w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-[#2A2A2A] dark:text-white shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tu Email">
                @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                <input type="password" wire:model="password" required class="mt-1 block text-black w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-[#2A2A2A] dark:text-white shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="••••••••">
                @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <button type="submit" class="w-full cursor-pointer py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                    Ingresar
                </button>
            </div>
        </form>
        <p class="mt-6 text-xs text-center text-gray-400">
            Corporalma &copy; {{ now()->year }}. Todos los derechos reservados.
        </p>
    </div>
</div>






