<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wl;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
//        dd(Wl::pluck('email'));
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'captcha' => ['required', 'captcha']
        ], [
            'name.max:255' => 'Имя слишком длинное!',
            'password.confirmed' => 'Пароли не совпадают!',
            'password.min' => 'Пароль должен быть не менее 8 символов!',
            'email.unique' => 'Данный e-mail уже зарегестрирован в системе!',
            'captcha.captcha' => 'Код не верный!',
        ]);

        // разрешаем регистрироваться только Избранным
        $accessEmails = $this->getWhiteListEmail();
        if(!in_array($request->input('email'), $accessEmails)) {
            return redirect()->back()->withErrors([
                'email' => 'Вам нельзя регаться! Вы не избранный'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    private function getWhiteListEmail():array
    {
        return Wl::pluck('email')->toArray();
    }
}
