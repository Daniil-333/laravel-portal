<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Wl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WlController extends Controller
{

    public function __construct()
    {
        $this->middleware("can:manipulate,App\User");
    }

    public function index()
    {
        $emails = Wl::select('id', 'email')
            ->get();
        return view('emails.index', ['emails' => $emails]);
    }

    public function save(Request $request)
    {

        $validator = $this->validateForm($request);

        if ($validator->fails()) {
            return redirect()->action([static::class, 'index'], ['emails' => Wl::all()])
                ->withErrors($validator, $request->keyform)
                ->withInput();
        }

        if(!$request->has('id')) {
            Wl::create([
                'email' => $request->email
            ]);
            return redirect()->action([static::class, 'index'], ['tags' => Wl::all()])->with("status", "Email '{$request->title}' добавлен");
        }

        $email = Wl::findOrFail($request->id);
        $oldEmail = $email->email;
        $email->fill($request->all())->save();
        return redirect()->action([static::class, 'index'])->with('status', "E-mail {$oldEmail} исправлен на $email->email");
    }

    public function destroy(Wl $wl)
    {
        $email = $wl->email;
        $wl->delete();
        return redirect()->action([static::class, 'index'])
            ->with("status", "E-mail " . $email . " удален из белого списка");
    }

    public function getRules(): array
    {
        return [
            'email' => 'required|email|max:50',
        ];
    }

    public function getMessages(): array
    {
        return [
            'email.required' => 'Введите Email',
            'email.email' => 'Введите корректный Email',
            'email.max' => 'Email не должен быть не длиннее 50 символов'
        ];
    }
}
